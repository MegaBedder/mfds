<?php
	$dbDir = "../safe/";
	$dbname = 'base';

	$mytable = "premium";
	
	$base = new SQLiteDatabase($dbDir.$dbname);

	$query = "SELECT * FROM ".$mytable;
	$results = $base->arrayQuery($query, SQLITE_ASSOC);
	var_dump($results);
/*	$arr = $results[0];

	if($results)
	{
	   $title = $arr['post_title'];
	   $content = $arr['post_content']; 
	   $user = $arr['post_author'];
	   $date = $arr['post_date'];
	   $url = $arr['guid'];
	}     */
?>
