<?php
	// test-link http://www.megaupload.com/?d=KHQC5IAA

	function mu_getDlFilename($link){
		$tmp = explode("/",$link);
		$tmp = explode("=",$tmp[count($tmp)-1]);
		$apiLink = "http://megaupload.com/mgr_linkcheck.php";
		
		$args = "id0=".$tmp[count($tmp)-1];	
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$apiLink);
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$msg = curl_exec($curl);
		
		curl_close ($curl);
		
		$tmp = explode("&",$msg);
		if(count($tmp) < 5) return "<404>";		
			
		$tmp = explode("=",$tmp[count($tmp)-1]);
		
		return $tmp[count($tmp)-1];
	}
	
	function mu_fetchCookieFile(){
		$dir = COOKIE_DIR;
		
		$url = "http://www.megaupload.com/";
		$args = "login=1&username=ecocharli&password=eco111ilr";
		
		$cookiefile = $dir."mu_cookie.txt";
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
	

	function mu_load($dlFile,$dlLink,$dlFilename,$cookiefile)
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
	

//	var_dump($msg);
	
//	$dlFile,$dlLink,$dlFilename,$cookiefile
?>
