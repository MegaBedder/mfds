<?php
	$masterKeyFile = "safe/master.key";
	
	require_once("Cryptastic.php");

	class AuthProvider{
	
		function getAuth($hoster){
			global $masterKeyFile;
			
			$crypter = new Cryptastic();

			$f  = fopen($masterKeyFile,"r");
			// WARNING: 32 is a workaround to fix strange behaviour in download.php when calling filesize()
			$key = fread($f, 32);
			fclose($f);

			$dbDir = "safe/";
			$dbname = 'base';

			$mytable = "premium";
	
			$base = new SQLiteDatabase($dbDir.$dbname);

			$query = "SELECT * FROM ".$mytable." WHERE hoster = '".$hoster."' ORDER BY ID DESC";
			$results = $base->arrayQuery($query, SQLITE_ASSOC);
	
			$user = $results[0]["user"];
			$pass = $crypter->decrypt($results[0]["pass"],$key);
			
			return array("user" => $user, "pass" => $pass);

		}
	
	}
	

?>
