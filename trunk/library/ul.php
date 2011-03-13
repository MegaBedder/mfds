<?php
	$input = ($_POST["link"]);

	function fetchCookieFile(){
		$args = "email=908465&password=rippedoff&submit=Login";
		$url = "http://uploaded.to/login";
		
		$cookiefile = "cookie.txt";
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
	
	function getDlFileName($link){
		$tmp = explode("/",$link);
		$apiLink = "http://uploaded.to/api/file?id=".$tmp[count($tmp)-1];		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$apiLink);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$msg = curl_exec($curl);
						$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
							if($httpCode == 404) {
							echo "Datei nicht gefunden, bitte Link Ueberpruefen";
							die;
							}		
							else {
							$cache1=explode("\n",$data);
							$filename=$cache1[0];
							}
		curl_close ($curl);
		$tmp = explode("\n",$msg);
		return substr($tmp[0],0,strlen($tmp[0])-1);
	}
	
	$cookiefile = (fetchCookieFile($input));
	$dlFilename = getDlFileName($input);
	$downloadingFile = fopen($dlFilename."","w");
	$curl = curl_init();	
	curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FILE,$downloadingFile);
	curl_setopt ($curl, CURLOPT_URL,$input);
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiefile);
	curl_exec($curl);
		
?>
