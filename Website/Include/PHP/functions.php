<?php

	$seperator = "ᐖ"; // Chosen because it looks like a smiling face

	// Returns will be in the structure: response code seperator extra data and will be formatted client side
	/*
		Response codes:
		0 - Successful shorten
		1 - Existing link found
		2 - Dead link
		3 - Database Error
		4 - Sanitize failed
	*/

	function shorten($sdb, $link){
		$sql = "SELECT * FROM `links` WHERE `link` = '$link' LIMIT 1;";
		if($result = $sdb->query($sql)){
			if($row = $result->fetch_assoc()){
				$short = $row['shortlink'];
				return "1$seperator$short";
			}
		}
		if(checkRemoteFile($link) !== true) return "2$seperator$link";
		$short = substr(number_format(time() * mt_rand(),0,'',''),0,5); 
		$short = base_convert($short, 10, 36); 
		
		$dpass = substr(number_format(time() * mt_rand(),0,'',''),0,10); 
		$dpass = base_convert($short.$dpass, 10, 36);

		$sql = "INSERT INTO `links` (link, shortlink, dpass) VALUES ('$link', '$short', '$dpass')";

		
		if($result = $sdb->query($sql)): return "0$seperator$short";
		else: return '3'.$seperator.$sdb->error;
		endif;
	}

	function checkRemoteFile($ip=null){
	    if($ip==null) return false;

	    // Setup the connection and only get the headers
	    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
		$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: ";

	    $curlInit = curl_init($ip);
	    curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
	    curl_setopt($curlInit, CURLOPT_HEADER, true);
	    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curlInit, CURLOPT_USERAGENT, 'UnPS-GAMATechnologies (UnPS WebQuery/4-2.9; +http://lob.li)');
	    curl_setopt($curlInit, CURLOPT_HTTPHEADER, $header);

	    $response = curl_exec($curlInit);
	    curl_close($curlInit);

	    if($response) return true;
	    return false;
	}

	function sanitize($input){
		if ($input == null) die("4");
		$output = strip_tags($input);
		$output = stripslashes($output);
		$output = mysql_real_escape_string($output);
		return $output;
	}

?>