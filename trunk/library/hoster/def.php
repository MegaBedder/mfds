<?php
	// testlink rotnes.dk/carsten

	function def_getDlFilename($dlLink){
		$buffer = explode("/",$dlLink);
		$filename = $buffer[count($buffer)-1]; 
	
		return $filename;
	}

	function def_fetchCookieFile(){
		return "";
	}
	
	function def_load($dlFile,$dlLink,$cookiefile){
		$curlHandle = curl_init();

		curl_setopt ($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_NOPROGRESS,false);
		curl_setopt($curlHandle,CURLOPT_PROGRESSFUNCTION,'curlCallback');
		curl_setopt($curlHandle, CURLOPT_BUFFERSIZE, 524288);
		curl_setopt($curlHandle, CURLOPT_FILE,$dlFile);
		curl_setopt ($curlHandle, CURLOPT_URL,$dlLink);
		
		curl_exec ($curlHandle);
		curl_close($curlHandle);
	}
?>
