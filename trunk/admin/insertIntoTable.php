<?php
	$dbDir = "../safe/";
	$dbname = 'base';

	$mytable = "premium";
	
	$base = new SQLiteDatabase($dbDir.$dbname);

	$query = "INSERT INTO ".$mytable."(ID, hoster, user, pass, download_amount) 
		        VALUES (null, 'sdfsdfsd', 'dfasqwg', 'edfgddqwdft', '0')";
	$results = $base->queryexec($query);
?>
