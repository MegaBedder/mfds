<?php 
	define("ID_LENGTH",20);
	define("CHAR_SPACE",'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
	define("TEMP_DIR", "temp/");
	define("DOWNLOAD_DIR", "downloads/");
	$globalID;
	$dlLink;
	
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
		fwrite($file,"init_req\n");
		fwrite($file,$link);
		fclose($file);

		return $id;
	}

	if(isset($_POST["link"])){
		$id = createDwonloadId($_POST["link"]);

		if(!$id) 
			die("error");
		else
			die($id);			
	}
	
	if(isset($_POST["id"]) && isset($_POST["fin"])){
		$dir = TEMP_DIR;

		$filename = $dir.$_POST["id"].".dld";
		unlink($filename);
		return;
	}


	if(isset($_POST["id"])){
		global $globalID,$dlLink;
		
		$dir = TEMP_DIR;
		
		$filename = $dir.$_POST["id"].".dld";
		$file = fopen($filename, "r");

		if(!$file) die("busy");		

		$fsize = filesize($filename);

		if(!$fsize) die("busy");				

		$args = split("\n", fread($file,filesize($filename)));
		fclose($file);
		
		if($args[0] == "init_req"){
			$file = fopen($filename, "w");
			fwrite($file, "0\n".$_POST["id"]);
			fclose($file);
			
			$globalID = $_POST["id"];
			
			startDownload($args[1]);
			die("init");
		}
		else{
			if($args[0] == 1){
				die("fin ".$args[1]);
			}
			die("suc ".$args[0]);
		}
	
	}
	
	function startDownload($link){ 
		global $dlLink;
		
		$cache = preg_split("%/%",$link); //split url to get filename
		$filename = $cache[count($cache)-1]; 
		$dir = DOWNLOAD_DIR; 	
		
		$curl = curl_init();
		$file = fopen($dir.$filename, "w+");
		$dlLink = $dir.$filename; 
			
		curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl,CURLOPT_NOPROGRESS,false);
		curl_setopt($curl,CURLOPT_PROGRESSFUNCTION,'curlCallback');
		curl_setopt($curl, CURLOPT_BUFFERSIZE, 262144);
		curl_setopt($curl, CURLOPT_FILE,$file);
		curl_setopt ($curl, CURLOPT_URL,$link);
		curl_exec ($curl);

		fclose($file);

	}
	
	$callcount;
	
	function curlCallback($downloadSize, $downloaded, $uploadSize, $uploaded){
		global $globalID,$dlLink;
		set_time_limit(60);
		
		$dir = TEMP_DIR;
		
		$filename = $dir.$globalID.".dld";
	
	
		if($downloadSize != 0){
			$file = fopen($filename, "w");
			fwrite($file, $downloaded/$downloadSize."\n".$dlLink);			
			fclose($file);
		}
		
	}

?> 
