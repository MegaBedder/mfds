<?php




	$query = "INSERT INTO ".$mytable."(ID, hoster, user, pass, download_amount) 
		        VALUES ('', 'uploaded.to', 'test', 'est', '0')";
	$results = $base->queryexec($query);

?>
