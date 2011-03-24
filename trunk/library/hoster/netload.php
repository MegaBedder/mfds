<?php
	include("../AuthHandler.php");
	$authHandler = new AuthHandler();
	
	echo $authHandler->getAccData("uploaded");

?>
