<?php
	// testLink "http://uploaded.to/file/z61m0yyt";
	function ul_fetchCookieFile(){
		global $cookieFolder;
		
		$args = "id=908465&pw=rippedoff";
		$url = "http://uploaded.to/io/login";
		
		$cookiefile = "ul_cookie.txt";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$msg = curl_exec($curl);
		curl_close ($curl);
		
		debug($msg);	

		return $cookiefile;
	}
	
	function ul_getDlFilename($link){
		$tmp = explode("/",$link);
		$apiLink = "http://uploaded.to/api/filemultiple?apikey=hP5Y37ulYfr8gSsS97LCT7kG5Gqp8Uug&id_0=".$tmp[count($tmp)-1];		
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$apiLink);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$msg = curl_exec($curl);
		curl_close ($curl);
		
		$tmp = explode(",",$msg);
		return substr($tmp[4],0,strlen($tmp[4])-1);
	}
	
	
	
	function ul_load($dlFile,$dlLink,$dlFilename,$cookiefile)
	{
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
