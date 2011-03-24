<?php
	$dbDir = "../safe/";
	$dbname = 'base';
	
	if(file_exists($dbDir.$dbname)) die("wtf are you doing???");
	
	$base=new SQLiteDatabase($dbDir.$dbname, 0666, $err);
	if ($err){ 
		echo "SQLite NOT supported.\n";
		exit($err);
	}
	
	echo "Successfull!";
?>
