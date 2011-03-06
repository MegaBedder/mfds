<?php
	
	// automated download over different provider api's
	class CurlDownloader{

		var $status;
		
		public function load($url){
			if( strpos($url, "rapidshare") ) {
				$this->rapidshareLoad($url);
				return;
			}
		}

		private function rapidshareLoad($url){
			$user = ("rippedoff01");
			$pwd = ("rippedoff");

			$newlink = preg_replace("%//%","//$user:$pwd@", $url); // add user and pass into url
			$cache = preg_split("%/%",$url); //split url to get filename
			$filename = $cache[count($cache)-1]; 
			
		
			if(strpos($filename,"html") !== false)
				$newfile= preg_replace("%.html%","",$filename);
			elseif(strpos($filename,"htm") !== false) 
				$newfile= preg_replace("%.htm%","",$filename);
			else
				 $newfile= $filename;
			
			
			$curl = curl_init();
			$file = fopen($newfile, "w+");
			
			curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl,CURLOPT_NOPROGRESS,false);
			//	curl_setopt($curl,CURLOPT_PROGRESSFUNCTION,'callback'); // no callback there
			curl_setopt($curl, CURLOPT_BUFFERSIZE, 1024);
			curl_setopt($curl, CURLOPT_FILE,$file);
			curl_setopt ($curl, CURLOPT_URL,$newlink);
			curl_exec ($curl);

			fclose($file);

			echo "Download Successfull, here is your link:" , '<br>'  ;
			echo ("<a href=$newfile>$newfile</a>");				
		}
		
		function netload($link){
			$user=("xxxxx");
			$pwd=("xxxxx");
			
			$replacement=("//$user:$pwd@");
			$pattern=("%//%");
			$newlink=preg_replace($pattern,$replacement, $string); // add user and pass into url
			$cache=preg_split("%/%",$_POST["link"]); //split url to get filename
			$filename=$cache[4]; 
			
			
			if(strpos($filename,"html") !== false) 
				$newfile= preg_replace("%.html%","",$filename);
			
			elseif(strpos($filename,"htm") !== false)
				$newfile= preg_replace("%.htm%","",$filename);
			else
				$newfile= $filename;
			
			$curl = curl_init();
			curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($curl,CURLOPT_NOPROGRESS,false);
			//curl_setopt($curl,CURLOPT_PROGRESSFUNCTION,'callback');
			//curl_setopt($curl, CURLOPT_BUFFERSIZE, 128);
			$file = fopen($newfile, "w+");
			curl_setopt($curl, CURLOPT_FILE,$file);
			curl_setopt ($curl, CURLOPT_URL,$newlink);
			curl_exec ($curl);
			fclose($file);
			echo "Download Successfull, here is your link:" , '<br>'  ;
			echo ("<a href=http://../$newfile>$newfile</a>");		
			}
	}
	
?>
