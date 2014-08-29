<?php

	require("../Include/PHP/db.php");
	require("../Include/PHP/functions.php");

	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // Get true IP of visiter if going through cloudflare
      $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $now = time(NULL);
    $seperator = "ᐖ";

	if(isset($_GET['shorten'])){
        $short = $_GET['url'];
        $expire = 2;
        if(!empty($_GET['time']) && is_numeric($_GET['time'])) $expire = $_GET['time'];
        if(strpos($short, "http://") === false && strpos($short, "https://") === false){ $short = "http://$short"; }

	    $apip = $redis->get("api:ip:$ip");
	    if(!in_array($ip, $apip)){
	      $redis->set("api:ip:$ip", $ip);
	      $redis->expireAt("api:ip:$ip", $now+5); // Five seconds between requests should be okay
	    }else{
	    	die("Too many requests too fast!");
	    }

        $reShort = shorten($redis, $short, $expire, $seperator);
        $reShort = explode($seperator, $reShort);
        $retCode = $reShort[0];

        switch($retCode){
            case "0": // Successful link Shorten
                echo $reShort[1];
                break;

            case "1": // Existing Short Link Found
                echo $reShort[1];
                break;

            case "2": // Dead Link
                echo "dead";
                break;

            case "3": // DB Error
                echo "db";
                break;

            case "4": // Sanitize Failure Error
                echo "sf";
                break;

            default:
                echo "Error";
                break;
        }
        exit;
	}elseif(isset($_GET['resolve'])){ die("Not ready"); 
	}else{ die("Improper Call."); }

?>
