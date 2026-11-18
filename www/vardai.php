<center><h3>Vardai</h3></center><br>
<?php
	
if($_POST !=null) $vardas = $_POST['vardas'];
else $vardas = "Default";
echo "Galiojantis vardas: ".$vardas."<br>";
?>
<br><center><h3>Įveskite naują vardą</h3></center>		
<form method='post'>
     Vardas:<input name='vardas' type='text' maxlength="10"><br><br>
    <input type='submit' name='ok' value='Patvirtinti'>
</form>
