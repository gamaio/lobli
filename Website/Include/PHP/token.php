<?php
	session_start();
	// Generate time expiring token for process.php
	require('db.php');

	do{ // Generate tokens until one isn't in the redis db
		$token = substr(number_format(time() * mt_rand(),0,'',''),0,40); 
		$token = base_convert($token, 10, 36); 

		if(!$redis->exists("tokens:$token")){
		   break; 
		}
	} while(1);

	$redis->set("tokens:$token", 0); // Store the token forever, when set to 1, don't allow token to be used anymore.
	$_SESSION['token'] = $token;
?>