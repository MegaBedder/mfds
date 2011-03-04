<?php
	
	// automated download over different provider api's
	class CurlDownloader{

		var $status;
		
		public function load($url, $filename){
			$ch = curl_init($url);
			$fp = fopen($filename, "w");
	
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);

			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
		}


	}

?>
