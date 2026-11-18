<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">
     </script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js">
     </script>
<style>
#zinutes {
    font-family: "Trebuchet MS", Arial; border-collapse: collapse; width: 70%;
}
#zinutes td {
    border: 1px solid #ddd; padding: 8px;
}
#zinutes tr:nth-child(even){background-color: #f2f2f2;}
#zinutes tr:hover {background-color: #ddd;}
</style>
	</head>
	
<body>
	<center><h3>Žinučių  sistema</h3></center>
    <table style="margin: 0px auto;" id="zinutes">

<?php
$server = "mysql"; 
$db = "stud";
$user = "stud";
$password = "stud";
$lentele = "zinutes";
$conn = new mysqli($server, $user, $password, $db);
           if($conn->connect_error) die("Negaliu prisijungti: " . $conn->connect_error);
		   
	if($_POST !=null){
		$vardas = substr(htmlspecialchars($_POST['vardas']),0,19);
		$epastas = substr(htmlspecialchars($_POST['epastas']),0,49);
		$kam = substr(htmlspecialchars($_POST['kam']),0,19);
		$zinute = htmlspecialchars($_POST['zinute'], ENT_QUOTES); 
		
		$data = date_create()->format('Y-m-d H:i:s');
		$ip=$_SERVER['REMOTE_ADDR'];
		if ($vardas && $epastas && $kam && $zinute){   //ar visi laukai užpildyti
		$stmt = $conn->prepare("INSERT INTO $lentele (vardas, epastas, kam, zinute, data,ip) VALUES (?, ?, ?, ?, ?, ?)");
		if ( false===$stmt ) {die("Negaliu įrašyti, prepare() klaida: " . $conn->error);}
		$stmt->bind_param("ssssss", $vardas, $epastas, $kam, $zinute, $data,$ip);
		if(!$stmt->execute()){die("Negaliu įrašyti, bind() klaida: " . $stmt->error);}
	}}
			//  nuskaityti viska bei spausdinti 
	echo "<tr><td>Nr</td><td>Kas siuntė</td><td>Siuntėjo e-paštas</td><td>Gavėjas</td><td>Data  (IP)</td><td>Žinutė</td></tr>";
	$sql = "SELECT * FROM ".$lentele;
	// palieku, turbūt nepavojinga, nes konstantė. Kitaip reiktų keisti ir rezultatų paėmimą: if($stmt->rowCount() > 0) {}
	if (!$result = $conn->query($sql)) die("Negaliu nuskaityti: " . $conn->error);
	while($row = $result->fetch_assoc()) 
		{
		echo "<tr><td>".$row['id']."</td><td>".$row['vardas']."</td><td>".$row['epastas']."</td><td>".$row['kam'].
			"</td><td>".$row['data']."(".$row['ip'].")"."</td><td>".$row['zinute']."</td></tr>";
		} 
	echo "</table>";
?>
<br>
<center><h3>Įveskite naują žinutę</h3>. Užpildykite visus laukus</center>		
<div class="container">
  <form method='post'>
     <div class="form-group col-lg-4">
          <label for="vardas" class="control-label">Siuntėjo vardas:</label>
          <input name='vardas' type='text' class="form-control input-sm">
     </div>
     <div class="form-group col-lg-4">
          <label for="epastas" class="control-label">Siuntėjo e-paštas:</label>
          <input name='epastas' id="epastas" type='email' class="form-control input-sm">
     </div>
	  <div class="form-group col-lg-4">
          <label for="kam" class="control-label">Kam skirta:</label>
          <input name='kam' type='text' class="form-control input-sm">
     </div>
     <div class="form-group col-lg-12">
          <label for="zinute" class="control-label">Žinutė:</label>
          <textarea name='zinute' class="form-control input-sm"></textarea>
     </div>
     <div class="form-group col-lg-2">
         <input type='submit' name='ok' value='siųsti' class="btnbtn-default">
     </div>
	  
 </form>
</div>
</body>
