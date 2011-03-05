<?php
function rs($link){
$user=("xxxxx");
$pass=("xxxxx");
	$chache=preg_split("%/%",$_POST["link"]);
	$fileid=$chache[4];
	$filename=$chache[5];
	$link= "http://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=download&fileid=$fileid&filename=$filename&login=$user&password=$pass";
		$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($curl, CURLOPT_URL,$link);
			$data=curl_exec ($curl);
		curl_close ($curl);
	$newcache=preg_split("%:%",$data);
	$newercache=preg_split("%,%",$newcache[1]);
	$reallink=$newercache[0];
    $link1= ("http://$reallink/cgi-bin/rsapi.cgi?sub=download&fileid=$fileid&filename=$filename&login=$user&password=$pass");
	$link2= $link1;	
		$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$file = fopen($filename, "w+");
			curl_setopt($curl, CURLOPT_FILE,$file);
			curl_setopt ($curl, CURLOPT_URL,$link2);
			curl_exec ($curl);
			$curl_info = curl_getinfo($curl);
		curl_close ($curl);
		fclose($file);
		echo "Download Successfull, here is your link:" , '<br>'  ;
		echo ("<a href=http://../$filename>$filename</a>");
		}
?>