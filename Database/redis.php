<?php

	$ids = 461;

	echo "mysql to redis migrator\nThis is very specific for the link shortener, and assumes 460 entries in table.\n";
	echo "Connecting to databases...\n";

	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);
	$mysql = new mysqli('localhost', 'short', 'password', 'short');
  	if($mysql->connect_errno > 0) die('Unable to connect to database ['.$mysql->connect_error.']');

  	echo "Starting the migration (This can take a while).\n";

  	for($id=1; $id<=$ids; $id++){
  		echo "Starting id:$id of $ids... ";

  		$sql = "SELECT * FROM `links` WHERE `id`='$id'";
  		if($result = $mysql->query($sql)){
			if($row = $result->fetch_assoc()){
				$link = $row['link'];
				$short = $row['shortlink'];
				$title = "Empty Title"; // Titles aren't in the mysql db
				$date = $row['date'];

				$redis->set("llinks:$link", $short);
		    	$redis->rpush("links:$short", $link);
		    	$redis->rpush("links:$short", $title);
		    	$redis->rpush("links:$short", $date);

		    	echo "Done!\n";
			}
		}
  	}

  	echo "\n";

?>
