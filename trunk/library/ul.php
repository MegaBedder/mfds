<?php
	//$input = "http://uploaded.to/file/km5mr0";
	$cookieFolder = "cookies/";
	//include("curlCallback.php");

	function ul_fetchCookieFile(){
		global $cookieFolder;
		
		$args = "email=908465&password=rippedoff&submit=Login";
		$url = "http://uploaded.to/login";
		
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

		return $cookiefile;
	}
	
	function ul_getDlFilename($link){
		$tmp = explode("/",$link);
		$apiLink = "http://uploaded.to/api/file?id=".$tmp[count($tmp)-1];		
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$apiLink);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$msg = curl_exec($curl);
		curl_close ($curl);
		
		$tmp = explode("\n",$msg);
		
	
		return substr($tmp[0],0,strlen($tmp[0])-1);;
	}
	
	$test;
	
	function ul_load($dlFile,$dlLink,$dlFilename,$cookiefile)
	{
		global $test;
		
		$test = 1;
	//	$cookiefile = (ul_fetchCookieFile($input));
	//	$dlFilename = ul_getDlFilename($input);
	//	$downloadingFile = fopen($dlFilename,"w");
	
			
	//	$f = fopen("test.est","w");
	//	fwrite($f,$dlFile.$dlLink.$dlFilename.$cookiefile);
	//	fclose($f);	

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
	
		
	function foo(){
		global $test;
		$f = fopen("test.est","w");
		fwrite($f,$test);
		fclose($f);
	}
	
	
	
	
?>
