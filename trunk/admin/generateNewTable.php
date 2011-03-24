<?php
	$dbDir = "../safe/";
	$dbname = 'base';

	$mytable = "premium";
	
	$base = new SQLiteDatabase($dbDir.$dbname);

	$query = "CREATE TABLE ".$mytable."(
		    ID INTEGER PRIMARY KEY,
		    hoster text,
		    user text,
		    pass varbinary(128),
		    download_amount bigint(32)
		    )";
            
	$results = $base->queryexec($query);
	
	echo "Successfull!";

?>
