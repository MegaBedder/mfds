<?php
	//$link= 'http://netload.in/datei64ORNkC1iT.htm';
	function nl_fetchCookieFile(){
		$dir = COOKIE_DIR;
	
		$auth = new AuthProvider();
		$authData = $auth->getAuth("nl");
		$user = $authData["user"];
		$pass = $authData["pass"];

		$args = "txtuser=593068&txtpass=jpqBIL&txtcheck=login&txtlogin=1";
		$url = "http://netload.in/index.php";
		
		$cookiefile = $dir."nl_cookie.txt";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$msg = curl_exec($curl);
		curl_close ($curl);

		return $cookiefile;
	}
	
	function nl_getDlFilename($link){
		$subString= (substr($link,0,strlen($link)-4));
		$tmp=explode("/",$subString);
		$tmp=explode("datei",$subString);
		$tmp=explode(" ",$tmp[1]);
		$id= $tmp[0];
		$apiLink= "http://api.netload.in/info.php";
		$args="auth=BVm96BWDSoB4WkfbEhn42HgnjIe1ilMt&file_id=$id";
		$curl = curl_init();	
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_URL,$apiLink);
		$buffer=curl_exec($curl);
		curl_close($curl);
		$tmp=explode(";",$buffer);
		$dlFilename=$tmp[1];
		
		return $dlFilename;
	}
	
	
	
	function nl_load($dlFile,$dlLink,$cookiefile)
	{
		$curl = curl_init();	
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FILE,$dlFile);
		curl_setopt($curl, CURLOPT_NOPROGRESS, 0);
		curl_setopt($curl, CURLOPT_PROGRESSFUNCTION,'curlCallback');
		curl_setopt($curl, CURLOPT_BUFFERSIZE, 262144);
		curl_setopt ($curl, CURLOPT_URL,$dlLink);
		curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiefile);
		curl_exec($curl);
		curl_close($curl);
		die();
	}
	
?>
