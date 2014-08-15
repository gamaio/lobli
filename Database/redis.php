<?php

	// You need an AI id, if you delete rows from your database (for example tos violation), I recommend resetting it.
	// ALTER TABLE `links` DROP COLUMN `id`;
	// ALTER TABLE `links` ADD COLUMN `id` int AUTO_INCREMENT PRIMARY KEY FIRST;
	// These SQL commands will erase all your ids and add them again, giving you an easily indexed list of ids
	// Take the last id from the table and put that in $ids. It should then add all your links to Redis.
	// It is your job to do what you feel you need to for your sql table.

	$ids = 461;

	echo "mysql to redis migrator\nThis is very specific for the link shortener, and assumes $ids entries in table.\n";
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
