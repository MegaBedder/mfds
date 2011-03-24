<?php
	//$dlLink ="http://hotfile.com/dl/110921239/506ac05/Thumbs.db.html";
	
	function hf_fetchCookieFile(){
		$dir = COOKIE_DIR;
		
		$args = "user=758948&pass=0nly4me";
		$url = "http://hotfile.com/login.php";
		
		$cookiefile = $dir."hf_cookie.txt";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$msg = curl_exec($curl);
		curl_close ($curl);
		

		return $cookiefile;
	}
	
	function hf_getDlFilename($link){
		$tmp = explode("/",$link);
		return substr($tmp[6],0,strlen($tmp[6])-5);
	}
	
	
	function hf_load($dlFile,$dlLink,$cookiefile)
	{	
		debug("blubb");
		$curl = curl_init();	
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FILE,$dlFile);
		curl_setopt($curl, CURLOPT_NOPROGRESS,false);
		curl_setopt($curl, CURLOPT_PROGRESSFUNCTION,'curlCallback');
		curl_setopt($curl, CURLOPT_BUFFERSIZE, 262144);
		curl_setopt ($curl, CURLOPT_URL,$dlLink);
		curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiefile);
		curl_exec($curl);
		curl_close($curl);
		die();
	}
	
?>
