<?php
	$dbDir = "../safe/";
	$masterKeyFile = "../safe/master.key";
	
	require_once("Cryptastic.php");

	class EncryptedDatabaseWrapper{
	
		public function insert($dbName, $table, $data, $encFields){
				global $dbDir,$masterKeyFile;
		
				$dbasePath = $dbDir.$dbName;
				var_dump($dbasePath);
				$dbase = new SQLiteDatabase($dbasePath);
				
				
				$captions = "(";
				$values = "(";
			
				$crypter = new Cryptastic();
				
				$f  = fopen($masterKeyFile,"r");
				$key = fread($f, filesize($masterKeyFile));
				fclose($f);
				
				
				
				foreach($data as $caption => $value){
					$captions .= "'".$caption."',";
					
					if(in_array($caption, $encFields))
						$values   .= "'".$crypter->encrypt($value,$key)."',";
					else
						$values   .= "'".SQLite3::escapeString($value)."',";
				}
				
				$captions = substr($captions,0,strlen($captions)-1).")";
				$values = substr($values,0,strlen($values)-1).")";
				
				$query = "INSERT INTO ".$table.$captions." VALUES ".$values;

				echo $query;
				$results = $dbase->queryexec($query);
		}
		
	}
	
	$wrapper = new EncryptedDatabaseWrapper();
	$wrapper->insert("base","premium", array("hoster" => "hf", "user" => "758948", "pass" => "0nly4me", "download_amount" => "0"), array("pass"));
	$wrapper->insert("base","premium", array("hoster" => "mu", "user" => "ecocharli", "pass" => "eco111ilr", "download_amount" => "0"), array("pass"));
	$wrapper->insert("base","premium", array("hoster" => "nl", "user" => "593068", "pass" => "jpqBIL", "download_amount" => "0"), array("pass"));
	$wrapper->insert("base","premium", array("hoster" => "ul", "user" => "908465", "pass" => "rippedoff", "download_amount" => "0"), array("pass"));

?>


