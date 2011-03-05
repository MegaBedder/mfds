<?php 
	define("ID_LENGTH",20);
	define("CHAR_SPACE",'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

	function createRandomFilename(){
		$cs = CHAR_SPACE;
		$string = "";    

		for ($p = 0; $p < ID_LENGTH; $p++) 
			$string .= $cs[mt_rand(0, strlen(CHAR_SPACE)-1)];
		

		return $string;
	}

	function createDwonloadId($link){
		$id = createRandomFilename();
		$filename = "downloads/".$id.".dld";
		$file = fopen($filename, "w");
		fwrite($file,$link."\n");
		fwrite($file,"init_req");
		fclose($file);

		return $id;
	}

	if(isset($_POST["link"])){
		$id = createDwonloadId($_POST["link"]);

		if(!$id) 
			die("err");
		else
			die($id);			
	}
	
	if($_POST["id"]){
		$filename = "downloads/".$_POST["id"].".dld";
		$file = fopen($filename, "r");
		$status = fread($file);
		die(status);
	
	}
?> 
