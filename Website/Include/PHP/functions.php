<?php

	require('Include/PHP/db.php');

	// Returns will be in the structure: response code seperator extra data and will be formatted client side
	/*
		Response codes:
		0 - Successful shorten
        1 - Existing link found
        2 - Dead link
        3 - Database Error
        4 - Sanitize failed
        5 - Successful lob.li link resolve
        6 - Successful lookup of non-lob.li link
        7 - Unsuccessful lookup of non-lob.li link
        8 - Lookup of link Stats (returns 8 $sep JSONarray)
	*/

	function shorten($redis, $link, $linkage, $seperator){
		$short = $redis->get("llinks:$link");
	    if($short){
	    	$sId = $short;
	    	$short = $redis->lrange("links:$short", 0, 1);
	    	$title = $short[1];
	    	return "1$seperator$sId";
	    }else{
	    	do {
	    		if(checkRemoteFile($link) !== true) return "2$seperator$link";

			    $short = substr(number_format(time() * mt_rand(),0,'',''),0,5); 
			    $short = base_convert($short, 10, 36);

		    	if(!$redis->exists("links:$short")) {
		    		break; 
		    	}
		    } while (1);

		    $now = time(NULL);
		    $xTime = 3136320000; // About 100 years, give or take

		    // Delete the links in 24 hours, 1 week, 1 month respectevly 
		    if($linkage == '0') $xTime = 86400;
		    if($linkage == '1') $xTime = 604800;
		    if($linkage == '2') $xTime = 2628000;

		    $redis->set("llinks:$link", $short);
		    $redis->rpush("links:$short", $link);
		    $redis->rpush("links:$short", date("m/d/Y"));
		    $redis->expireAt("links:$short", $now+$xTime);
		    $redis->expireAt("llinks:$link", $now+$xTime);

		    $redis->zAdd("tracking:clicks", 1, $link);

		    return "0$seperator$short";
 	    }
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
	    curl_setopt($curlInit, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; UnPS-GAMATechnologies (UnPS WebQuery/4-2.9; +http://lob.li))');
	    curl_setopt($curlInit, CURLOPT_HTTPHEADER, $header);

	    $response = curl_exec($curlInit);
	    curl_close($curlInit);

	    if($response) return true;
	    return false;
	}

	function sanitize($input, $seperator){
		if ($input == null) die("4$seperator");
		$output = strip_tags($input);
		$output = stripslashes($output);
		//filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING)
		$output = mysql_real_escape_string($output);
		return $output;
	}

?>
