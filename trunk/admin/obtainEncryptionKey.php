<?php
	include("../library/Cryptastic.php");
	$keyDir = "../safe/";
	$keyFile = "master.key";

	$pass = "";
	$salt = "";



	$cryptastic = new Cryptastic();
	$key = $cryptastic->pbkdf2($pass, $salt, 1000, 32);

	if(file_exists($keyDir.$keyFile)) die("wtf are you doing???");

	$f = fopen($keyDir.$keyFile, "w");
	fwrite($f, $key);
	fclose($f);
?>
