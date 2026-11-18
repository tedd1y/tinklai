<?php
// ===== Prisijungimas prie DB =====
$server   = "mysql";
$db       = "stud";
$user     = "stud";
$password = "stud";
$lentele  = "titasstuopis";

$conn = new mysqli($server, $user, $password, $db);
if ($conn->connect_error) {
    die("Negaliu prisijungti: " . $conn->connect_error);
}

// ===== Naujos žinutės įrašymas (kai forma išsiųsta) =====
if ($_POST != null) {
    $vardas  = substr(htmlspecialchars($_POST['vardas']), 0, 19);
    $epastas = substr(htmlspecialchars($_POST['epastas']), 0, 49);
    $kam     = substr(htmlspecialchars($_POST['kam']), 0, 19);
    $zinute  = htmlspecialchars($_POST['zinute'], ENT_QUOTES);
    $data    = date_create()->format('Y-m-d H:i:s');
    $ip      = $_SERVER['REMOTE_ADDR'];

    // įrašom tik jei visi laukai užpildyti
    if ($vardas && $epastas && $kam && $zinute) {
        $stmt = $conn->prepare(
            "INSERT INTO $lentele (vardas, epastas, kam, zinute, data) VALUES (?, ?, ?, ?, ?)"
        );
        if (false === $stmt) {
            die("Negaliu įrašyti, prepare() klaida: " . $conn->error);
        }
        $stmt->bind_param("sssss", $vardas, $epastas, $kam, $zinute, $data);
        if (!$stmt->execute()) {
            die("Negaliu įrašyti, bind() klaida: " . $stmt->error);
        }
    }
}

// ===== Visų žinučių nuskaitymas =====
$sql = "SELECT * FROM " . $lentele;
if (!$result = $conn->query($sql)) {
    die("Negaliu nuskaityti: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lab2</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<center><h3>Žinučių sistema</h3></center>

<!-- Žinučių lentelė -->
<table style="margin: 0px auto;" id="zinutes">
    <tr>
        <td>Nr</td>
        <td>Kas siuntė</td>
        <td>Siuntėjo e-paštas</td>
        <td>Gavėjas</td>
        <td>Data</td>
        <td>Žinutė</td>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['vardas'] ?></td>
            <td><?= $row['epastas'] ?></td>
            <td><?= $row['kam'] ?></td>
            <td><?= $row['data'] ?></td>
            <td><?= $row['zinute'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<br>

<!-- Naujos žinutės forma -->
<form method='post'>
    Siuntėjo vardas:   <input name='vardas' type='text'><br><br>
    Siuntėjo e-paštas: <input name='epastas' type='email'><br><br>
    Gavėjas:           <input name='kam' type='text'><br><br>
    Žinutė:            <textarea name='zinute'></textarea><br><br>
    <input type='submit' name='ok' value='siųsti'>
</form>

</body>
</html>
