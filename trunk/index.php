<?php
	
	include "library/CurlDownloader.php";
	
	$cd = new CurlDownloader();
	$cd->load("http://rapidshare.com","test.html");
?>
