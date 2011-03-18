<?php 
	include("library/ul.php");
	include("library/rs.php");
	include("library/mu.php");

	define("ID_LENGTH",20);
	define("CHAR_SPACE",'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
	define("TEMP_DIR", "temp/");
	define("DOWNLOAD_DIR", "downloads/");
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

	function createDwonloadId($link){
		$dir = TEMP_DIR;
		
		$id = createRandomFilename();
		$filename = $dir.$id.".dld";
		$file = fopen($filename, "w");
		fwrite($file,"init_req\n".$link);
		fclose($file);

		return $id;
	}

	if(isset($_POST["link"]) && isset($_POST["init"])){
		$id = createDwonloadId($_POST["link"]);

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
		
		$args = split("\n", $input);
		
		fclose($file);
		
//		debug($input);
		
			
		if($args[0] == "init_req"){	
			$globalID = $id;
			startDownload($args[1]);
			//die();
		}
		if($args[0] == $args[1]){
			die("fin"."@".$id."@".$args[2]);
		}
		if($args[0] == "abort"){
			die("abort");
		}
		else 	die("suc"."@".$id."@".$args[0]."@".$args[1]."@".$args[2]);
		
		debug("wtf");
//		die();	
	}
	

	function startDownload($link){ 
		global $userLink,$dlFile;
		
		$dir = DOWNLOAD_DIR; 	
		
		if(strpos($link,"uploaded.to") || strpos($link,"ul.to")){
			$dlFilename = ul_getDlFilename($link);
			$cookiefile = ul_fetchCookieFile();
			$dlFile = fopen($dir.$dlFilename, "w");
			$userLink = $dir.$dlFilename; 
			
			debug($userLink);
			ul_load($dlFile,$link,$dlFilename,$cookiefile);
			fclose($dlFile);
		}
		if(strpos($link,"rapidshare.com")){
			$dlFilename = rs_getFilename($link);
			$dlLink = rs_getDlLink($link);
			$userLink = $dir.$dlFilename;

			$dlFile = fopen($userLink,"w");

			rs_load($dlFile, $dlLink, $dlFilename);

			fclose($dlFile);
		}	
		if(strpos($link,"megaupload.com")){
			$dlFilename = mu_getDlFilename($link);
			$dlFile = fopen($dlFilename,"w");
			$cookiefile = mu_fetchCookieFile();
			$userLink = $dir.$dlFilename; 
			
			debug($userLink);
			
			mu_load($dlFile,$link,$dlFilename,$cookiefile);
			fclose($dlFile);
		}		
		else{
			
			$cache = preg_split("%/%",$link); //split url to get filename
			$filename = $cache[count($cache)-1]; 
			$dir = DOWNLOAD_DIR; 	
		
			$curlHandle = curl_init();
			$userLink = $dir.$filename; 
			$dlFile = fopen($userLink, "w+");
			
			curl_setopt ($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curlHandle,CURLOPT_NOPROGRESS,false);
			curl_setopt($curlHandle,CURLOPT_PROGRESSFUNCTION,'curlCallback');
			curl_setopt($curlHandle, CURLOPT_BUFFERSIZE, 524288);
			curl_setopt($curlHandle, CURLOPT_FILE,$dlFile);
			curl_setopt ($curlHandle, CURLOPT_URL,$link);
		
			curl_exec ($curlHandle);
			curl_close($curlHandle);

			fclose($dlFile);
		}
	}
	

	function curlCallback($downloadSize, $downloaded, $uploadSize, $uploaded){
		global $globalID,$userLink,$dlFile,$test;
		set_time_limit(60);
		

		//debug("dlsize:".$downloadSize."  downloaded:".$downloaded);
		
		$dir = TEMP_DIR;
		$filename = $dir.$globalID.".dld";
		
		if(!file_exists($filename)) return;
		
		$file = fopen($filename, "r");
		$args = split("\n", fread($file,filesize($filename)));
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
