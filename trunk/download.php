<?php 
	include("library/def.php");
	include("library/ul.php");
	include("library/rs.php");
	include("library/mu.php");
	include("library/hf.php");
	include("library/x7.php");
	
	define("ID_LENGTH",20);
	define("CHAR_SPACE",'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
	define("TEMP_DIR", "temp/");
	define("DOWNLOAD_DIR", "downloads/");
	define("COOKIE_DIR", "cookies/");
	$globalID;
	$userLink;
	$dlFile;
	
	function createRandomFilename(){
		$cs = CHAR_SPACE;
		$string = "";    

		for ($p = 0; $p < ID_LENGTH; $p++) 
			$string .= $cs[mt_rand(0, strlen(CHAR_SPACE)-1)];
		

		return $string;
	}

	function createDownloadId($link){
		$dir = TEMP_DIR;
		
		$id = createRandomFilename();
		$filename = $dir.$id.".dld";
		$file = fopen($filename, "w");
		fwrite($file,"init_req\n".$link);
		fclose($file);

		return $id;
	}

	if(isset($_POST["link"]) && isset($_POST["info"])){
		if(strpos($_POST["link"],"uploaded.to") || strpos($_POST["link"],"ul.to")){
			$filename = ul_getDlFilename($_POST["link"]);
		}
		else if(strpos($_POST["link"],"rapidshare.com")){
			$filename = rs_getDlFilename($_POST["link"]);
		}
		else if(strpos($_POST["link"],"megaupload.com")){
			$filename = mu_getDlFilename($_POST["link"]);
		}
		else if(strpos($_POST["link"],"hotfile.com")){
			$filename = hf_getDlFilename($_POST["link"]);
		}
		else if(strpos($_POST["link"],"x7.to")){
			$filename = x7_getDlFilename($_POST["link"]);
		}
		else{
			$tmp = explode("/",$_POST["link"]);
			$filename = $tmp[count($tmp)-1];
		}
		
		die("info@".$filename."@".$_POST["link"]);

	}
	if(isset($_POST["link"]) && isset($_POST["init"])){
		$id = createDownloadId($_POST["link"]);

		if(!$id) 
			die("error");
		else
			die("id"."@".$id."@".$_POST["link"]);			
	}
	
	if(isset($_POST["id"]) && isset($_POST["fin"])){
		$dir = TEMP_DIR;

		$filename = $dir.$_POST["id"].".dld";
		unlink($filename);
		return;
	}

	if(isset($_POST["id"]) && isset($_POST["abort"])){
		global $userLink;

		$dir = TEMP_DIR;
		$filename = $dir.$_POST["id"].".dld";

		while(!$file = fopen($filename, "w"));
		
		fwrite($file, "abort"."\n".$userLink."\n");			
		fclose($file);
		die("abort");
	}


	if(isset($_POST["id"]) && isset($_POST["status"])){
		global $globalID,$userLink;
		
		$ids = explode(",",$_POST["id"]);
		$dir = TEMP_DIR;	

		$id = $_POST["id"];
		$filename = $dir.$id.".dld";
		$file = fopen($filename, "r");
		
		if(!$file) die("busy");

		$fsize = filesize($filename);

		if(!$fsize) die("busy");
		
		$input = fread($file,filesize($filename));
		if($input == "") die("busy");
		
		$args = explode("\n", $input);
		
		fclose($file);
		
		
		if($args[0] == "init_req"){	
			$globalID = $id;
			$file = fopen($filename, "w");
			fclose($file);

			startDownload($args[1]);
		}
		else if($args[0] == $args[1]){
			die("fin"."@".$id."@".$args[2]);
		}
		else if($args[0] == "abort"){
			die("abort");
		}
		else 	die("suc"."@".$id."@".$args[0]."@".$args[1]."@".$args[2]);
	}
	

	function startDownload($link){ 
		global $userLink,$dlFile;
		
		$dir = DOWNLOAD_DIR; 	
		
		if(strpos($link,"uploaded.to") || strpos($link,"ul.to")){
			$prefix = "ul";
		}
		else if(strpos($link,"rapidshare.com")){
			$prefix = "rs";
		}	
		else if(strpos($link,"megaupload.com")){
			$prefix = "mu";
		}
		else if(strpos($link,"hotfile.com")){
			$prefix = "hf";
		}
		else if(strpos($link,"x7.to")){
			$prefix = "x7";
		}			
		else{
			$prefix = "def";
		}
		
		
		$filenameFunction = $prefix."_getDlFilename";
		$cookiFunction = $prefix."_fetchCookieFile";
		$loadFunction = $prefix."_load";

		$dlFilename = $filenameFunction($link);
		$userLink = $dir.$dlFilename; 
		$dlFile = fopen($userLink,"w");
		$cookiefile = $cookiFunction($link);
		
		$loadFunction($dlFile,$link,$cookiefile);
		fclose($dlFile);
		
		
	}
	

	function curlCallback($downloadSize, $downloaded, $uploadSize, $uploaded){
		global $globalID,$userLink,$dlFile;
		set_time_limit(60);
		
//		debug("dlsize:".$downloadSize."  downloaded:".$downloaded);
		
		$dir = TEMP_DIR;
		$filename = $dir.$globalID.".dld";
		
		if(!file_exists($filename)) return;
		
		$file = fopen($filename, "r");
		$args = explode("\n", fread($file,filesize($filename)));
		fclose($file);
				
		if($args[0] == "abort"){			
			fclose($dlFile);
			unlink($userLink);
			unlink($filename);
			die();
		}
		
	
		if($downloadSize != 0){
			//workaround sloopy progress bar
			if($downloaded < $args[0]) return;

			$file = fopen($filename, "w");
			fwrite($file, $downloaded."\n".$downloadSize."\n".$userLink."\n");			
			fclose($file);
		}
	}
	
	function debug($str){
		$f = fopen("debug.log","a");
		fwrite($f, date("d.m.y H:i:s ").$str."\n");
		fclose($f);
	}
?> 
