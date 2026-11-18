<?php
date_default_timezone_set('Europe/Vilnius');

// ===== Prisijungimas prie DB =====
$server   = "mysql";
$db       = "stud";
$user     = "stud";
$password = "stud";
$lentele  = "pasiruosimas";

$conn = new mysqli($server, $user, $password, $db);
if ($conn->connect_error) {
    die("Negaliu prisijungti: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS $lentele (
    id      INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    vardas  VARCHAR(20)  NOT NULL,
    epastas VARCHAR(50)  NOT NULL,
    kam     VARCHAR(20)  NOT NULL,
    lytis   VARCHAR(10)  NOT NULL,
    data    DATETIME     NOT NULL,
    zinute  TEXT         NOT NULL
)");

function h($tekstas)
{
    return htmlspecialchars($tekstas ?? '', ENT_QUOTES, 'UTF-8');
}

$leidziamos_lytys = ['Vyras', 'Moteris'];
$klaida = "";

// ===== CREATE / UPDATE / DELETE  =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $veiksmas = $_POST['veiksmas'] ?? '';

    // ---- DELETE ----
    if ($veiksmas === 'istrinti') {
        $id   = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM $lentele WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: pasiruosimas.php");
        exit;
    }

    // ---- CREATE ir UPDATE  ----
    $vardas  = mb_substr(trim($_POST['vardas'] ?? ''), 0, 20);
    $epastas = mb_substr(trim($_POST['epastas'] ?? ''), 0, 50);
    $kam     = mb_substr(trim($_POST['kam'] ?? ''), 0, 20);
    $lytis   = $_POST['lytis'] ?? '';
    $zinute  = trim($_POST['zinute'] ?? '');

    if (!$vardas || !$epastas || !$kam || !$zinute || !in_array($lytis, $leidziamos_lytys)) {
        $klaida = "Užpildykite visus laukus ir pasirinkite lytį.";
    } elseif ($veiksmas === 'irasyti') {
        // ---- CREATE ----
        $data = date('Y-m-d H:i:s');
        $stmt = $conn->prepare(
            "INSERT INTO $lentele (vardas, epastas, kam, lytis, data, zinute) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssss", $vardas, $epastas, $kam, $lytis, $data, $zinute);
        if (!$stmt->execute()) die("Negaliu įrašyti: " . $stmt->error);
        header("Location: pasiruosimas.php");
        exit;
    } elseif ($veiksmas === 'atnaujinti') {
        // ---- UPDATE ----
        $id   = (int)$_POST['id'];
        $stmt = $conn->prepare(
            "UPDATE $lentele SET vardas = ?, epastas = ?, kam = ?, lytis = ?, zinute = ? WHERE id = ?"
        );
        $stmt->bind_param("sssssi", $vardas, $epastas, $kam, $lytis, $zinute, $id);
        if (!$stmt->execute()) die("Negaliu atnaujinti: " . $stmt->error);
        header("Location: pasiruosimas.php");
        exit;
    }
}

// ===== Redagavimo režimas: ?redaguoti=ID  =====
$redaguojamas = null;
if (isset($_GET['redaguoti'])) {
    $id   = (int)$_GET['redaguoti'];
    $stmt = $conn->prepare("SELECT * FROM $lentele WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $redaguojamas = $stmt->get_result()->fetch_assoc();
}

// klaidos atveju
$forma = $klaida ? $_POST : ($redaguojamas ?? []);

// ===== READ su filtru =====
// ?filtras=visi | Vyras | Moteris      ?paieska=tekstas
$filtras = $_GET['filtras'] ?? 'visi';
$paieska = trim($_GET['paieska'] ?? '');

$salygos  = [];
$reiksmes = [];
$tipai    = "";

if (in_array($filtras, $leidziamos_lytys)) {
    $salygos[]  = "lytis = ?";
    $reiksmes[] = $filtras;
    $tipai     .= "s";
}
if ($paieska !== '') {
    $salygos[]  = "vardas LIKE ?";
    $reiksmes[] = "%" . $paieska . "%";
    $tipai     .= "s";
}

$sql = "SELECT * FROM $lentele";
if ($salygos) {
    $sql .= " WHERE " . implode(" AND ", $salygos);
}
$sql .= " ORDER BY id";

$stmt = $conn->prepare($sql);
if ($reiksmes) {
    $stmt->bind_param($tipai, ...$reiksmes);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pasiruošimas gynimui</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Globalūs stiliai -->
    <style>
        #zinutes {
            font-family: Arial;
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto;
        }

        #zinutes td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #zinutes tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #zinutes tr:hover {
            background-color: #ddd;
            font-weight: bold;
        }

        #zinutes tr.geltona,
        #zinutes tr.geltona:hover {
            background-color: #ffff66;
            font-weight: bold;
        }

        .automobiliai .nav-tabs img {
            width: 90px;
            filter: grayscale(100%);
        }

        .automobiliai .nav-tabs li.active img {
            filter: none;
        }

        .automobiliai .tab-content img {
            width: 100%;
            max-width: 600px;
        }

        .skyrius {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<center><h3>Žinučių sistema – pasiruošimas gynimui</h3></center>

<!-- ===== FILTRAS (GYNIMO UŽD. 3) ===== -->
<div class="container">
    <form method="get" class="form-inline text-center">
        <label for="filtras">Rodyti:</label>
        <select name="filtras" id="filtras" class="form-control input-sm">
            <option value="visi"    <?= $filtras === 'visi'    ? 'selected' : '' ?>>Visi įrašai</option>
            <option value="Vyras"   <?= $filtras === 'Vyras'   ? 'selected' : '' ?>>Vyrų žinutės</option>
            <option value="Moteris" <?= $filtras === 'Moteris' ? 'selected' : '' ?>>Moterų žinutės</option>
        </select>

        <input name="paieska" type="text" placeholder="Ieškoti pagal vardą"
               value="<?= h($paieska) ?>" class="form-control input-sm">

        <input type="submit" value="Filtruoti" class="btn btn-default btn-sm">
        <a href="pasiruosimas.php" class="btn btn-link btn-sm">Išvalyti</a>
    </form>
</div>
<br>

<!-- ===== LENTELĖ (READ) ===== -->
<table id="zinutes">
    <tr class="geltona">
        <td>Nr.</td>
        <td>Kas siuntė</td>
        <td>Siuntėjo e-paštas</td>
        <td>Gavėjas</td>
        <td>Lytis</td>
        <td>Data</td>
        <td>Žinutė</td>
        <td>Veiksmai</td>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= h($row['vardas']) ?></td>
            <td><?= h($row['epastas']) ?></td>
            <td><?= h($row['kam']) ?></td>
            <td><?= h($row['lytis']) ?></td>
            <td><?= $row['data'] ?></td>
            <td><?= h($row['zinute']) ?></td>
            <td>
                <!-- UPDATE: atidaro formą su šio įrašo duomenimis -->
                <a href="?redaguoti=<?= $row['id'] ?>" class="btn btn-default btn-xs">Redaguoti</a>

                <!-- DELETE: atskira mini forma su POST -->
                <form method="post" style="display:inline"
                      onsubmit="return confirm('Tikrai ištrinti?');">
                    <input type="hidden" name="veiksmas" value="istrinti">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="submit" value="Ištrinti" class="btn btn-danger btn-xs">
                </form>
            </td>
        </tr>
    <?php endwhile; ?>

    <?php if ($result->num_rows === 0): ?>
        <tr>
            <td colspan="8"><i>Įrašų nėra</i></td>
        </tr>
    <?php endif; ?>

    <tr class="geltona">
        <td colspan="8">Darbą atliko: Titas Stuopis</td>
    </tr>
</table>

<!-- ===== FORMA (CREATE ir UPDATE) ===== -->
<center>
    <h3><?= $redaguojamas ? "Redaguoti žinutę Nr. " . $redaguojamas['id'] : "Įveskite naują žinutę" ?></h3>
</center>

<div class="container">
    <?php if ($klaida): ?>
        <div class="alert alert-danger"><?= $klaida ?></div>
    <?php endif; ?>

    <form method="post">
        <!-- veiksmas pasako PHP, ar kurti naują, ar atnaujinti esamą -->
        <input type="hidden" name="veiksmas" value="<?= $redaguojamas ? 'atnaujinti' : 'irasyti' ?>">
        <?php if ($redaguojamas): ?>
            <input type="hidden" name="id" value="<?= $redaguojamas['id'] ?>">
        <?php endif; ?>

        <div class="form-group col-lg-3">
            <label for="vardas">Siuntėjo vardas:</label>
            <input name="vardas" id="vardas" type="text" maxlength="20"
                   value="<?= h($forma['vardas'] ?? '') ?>" class="form-control input-sm">
        </div>
        <div class="form-group col-lg-3">
            <label for="epastas">Siuntėjo e-paštas:</label>
            <input name="epastas" id="epastas" type="email" maxlength="50"
                   value="<?= h($forma['epastas'] ?? '') ?>" class="form-control input-sm">
        </div>
        <div class="form-group col-lg-3">
            <label for="kam">Kam skirta:</label>
            <input name="kam" id="kam" type="text" maxlength="20"
                   value="<?= h($forma['kam'] ?? '') ?>" class="form-control input-sm">
        </div>

        <!-- GYNIMO UŽD. 1: Vyras / Moteris -->
        <div class="form-group col-lg-3">
            <label>Lytis:</label><br>
            <label class="radio-inline">
                <input type="radio" name="lytis" value="Vyras"
                    <?= ($forma['lytis'] ?? '') === 'Vyras' ? 'checked' : '' ?>> Vyras
            </label>
            <label class="radio-inline">
                <input type="radio" name="lytis" value="Moteris"
                    <?= ($forma['lytis'] ?? '') === 'Moteris' ? 'checked' : '' ?>> Moteris
            </label>
        </div>

        <div class="form-group col-lg-12">
            <label for="zinute">Žinutė:</label>
            <textarea name="zinute" id="zinute" class="form-control input-sm"><?= h($forma['zinute'] ?? '') ?></textarea>
        </div>

        <div class="form-group col-lg-12">
            <input type="submit" value="<?= $redaguojamas ? 'Išsaugoti' : 'Siųsti' ?>" class="btn btn-primary btn-sm">
            <?php if ($redaguojamas): ?>
                <a href="pasiruosimas.php" class="btn btn-default btn-sm">Atšaukti</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ===== GYNIMO UŽD. 4: automobiliai tabuose ===== -->
<div class="container automobiliai skyrius">
    <h3>Automobiliai</h3>

    <!-- Skirtukai (tabai) – mažos nuotraukos -->
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#auto1"><img src="car-1.jpg" alt="Auto 1"></a></li>
        <li><a data-toggle="tab" href="#auto2"><img src="car-2.jpg" alt="Auto 2"></a></li>
        <li><a data-toggle="tab" href="#auto3"><img src="car-3.jpg" alt="Auto 3"></a></li>
    </ul>

    <!-- Turinys – rodomas tik aktyvaus tabo -->
    <div class="tab-content">
        <div id="auto1" class="tab-pane fade in active">
            <h4>Automobilis 1</h4>
            <img src="car-1.jpg" alt="Auto 1">
        </div>
        <div id="auto2" class="tab-pane fade">
            <h4>Automobilis 2</h4>
            <img src="car-2.jpg" alt="Auto 2">
        </div>
        <div id="auto3" class="tab-pane fade">
            <h4>Automobilis 3</h4>
            <img src="car-3.jpg" alt="Auto 3">
        </div>
    </div>
</div>

<br><br>
</body>
</html>
