<?php
	// test-links http://rapidshare.com/files/452549785/0277.JPG
	// http://rapidshare.com/files/452870539/ulli
	// http://rapidshare.com/files/452871539/tony

	function rs_getDlLink($link){
		$auth = new AuthProvider();
		$authData = $auth->getAuth("rs");
		$user = $authData["user"];
		$pass = $authData["pass"];


		// get right server from api
		$cache = explode("/",$link);
		$fileid = $cache[count($cache)-2];
		$filename = $cache[count($cache)-1];
		$link = "http://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=download&fileid=".$fileid."&filename=".$filename."&login=".$user."&password=".$pass;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL,$link);
		$data = curl_exec ($curl);
		curl_close($curl);

		$cache = preg_split("%:%",$data);
		$args = preg_split("%,%",$cache[1]);
		$server=$args[0];
	
		return "http://$server/cgi-bin/rsapi.cgi?sub=download&fileid=$fileid&filename=$filename&login=$user&password=$pass";
	}

	function rs_fetchCookieFile(){
		return "";
	}

	function rs_getDlFilename($link){
		$cache = explode("/",$link);
		$fileid = $cache[count($cache)-2];
		$filename = $cache[count($cache)-1];
		$apiLink = ("http://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=checkfiles_v1&files=$fileid&filenames=$filename");
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL,$apiLink);
		$data = curl_exec ($curl);	
		curl_close($curl);
		$data=explode(",",$data);
		
		if(strtolower($data[2] == "0")) return "<404>";		
		return $data[1];
	}

	function rs_load($dlFile,$dlLink,$cookiefile){
		$dlLink = rs_getDlLink($dlLink);  // this is for rs ONLY
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FILE,$dlFile);
		curl_setopt ($curl, CURLOPT_URL,$dlLink);
		curl_setopt($curl, CURLOPT_NOPROGRESS,false);
		curl_setopt($curl, CURLOPT_PROGRESSFUNCTION,'curlCallback');
		curl_setopt($curl, CURLOPT_BUFFERSIZE, 524288);
		curl_exec ($curl);
		curl_close ($curl);
	}
	
?>
