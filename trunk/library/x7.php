<?php
	//x7_getDlFilename("http://x7.to/3s3quy");
	function x7_fetchCookieFile(){
		global $cookieFolder;
		
		$args = "id=256974&pw=XnpAI";
		$url = "http://x7.to/james/login";
		
		$cookiefile = "x7_cookie.txt";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$msg = curl_exec($curl);
		curl_close ($curl);
		
//		debug($msg);	

		return $cookiefile;
	}
	
	function x7_getDlFilename($link){
		$buffer=(get_meta_tags($link));
		$buffer=(explode("Download: ",$buffer["description"]));
		$buffer=(explode(" (",$buffer[1]));
		$dlFilename=$buffer[0];
		
		
		return $dlFilename;
	}
	
	
	
	function x7_load($dlFile,$dlLink,$cookiefile)
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
