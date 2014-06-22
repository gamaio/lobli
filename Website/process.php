<?php
    session_start();

    $catches = explode(":", $_SESSION['catch']);
    $catchid = $catches[0];
    $catchVal = $catches[1];

    // Returns will be in the structure: Message code / seperator / extra data and will be formatted client side
    /*
        Message codes:
        0 - Successful shorten
        1 - Existing link found
        2 - Dead link
        3 - Database Error
        4 - Sanitize failed
    */

    $messages = array(
        "
            <div class=\"alert alert-success\" id=\"message\">
                Your link: <a href=\"http://lob.li/$short\" title=\"HTML Title of website being shortened\" target=\"lobli.$short\">lob.li/$short</a>
                <a href=\"#\" id=\"copylink\" title=\"Copy Link\" onclick=\"copyToClipboard('http://lob.li/$short');\">
                    <span class=\"glyphicon glyphicon-link\" style=\"float:right;padding-right:1%;\"></span>
                </a>
            </div>
        ",
        "
            <div class=\"alert alert-warning\" id=\"message\">
                Existing link: <a href=\"http://lob.li/$short\" title=\"HTML Title of website being shortened\" target=\"lobli.$short\">lob.li/$short</a>
                 <a href=\"#\" id=\"copylink\" title=\"Copy Link\" onclick=\"copyToClipboard('http://lob.li/$short');\">
                    <span class=\"glyphicon glyphicon-link\" style=\"float:right;padding-right:1%;\"></span>
                </a>
            </div>
        ",
        "
            <div class=\"alert alert-danger\" id=\"message\">
                ERROR! - Your link: <a href=\"$link\" target=\"$link\">$link</a> didn't resolve to a website. <br />Please check your link and try again.
            </div>
        ", 
        "
            <div class=\"alert alert-danger\" id=\"message\">
                ERROR! - Well this is embarrassing... This never happens, but I appear to have suffered a database error. <br />Here's what I know: $error
            </div>
        ",
        "
            <div class=\"alert alert-danger\" id=\"message\">
                ERROR! - The sanitize function seems to have failed. This shouldn't happen, maybe <a href=\"mailto:c0de@unps.us\">c0de</a> forgot a semi-colon somewhere or something. 
            </div>
        "
        );

    if(empty($_GET['token']) || $_GET['token'] != $_SESSION['token'] || empty($_POST[$catchid]) || $_POST[$catchid] != $catchVal){ 
        die("<div id=\"error\">Oh Noes! Something happened and I can't continue.<br />Please try again by using the form located at <a href=\"http://unps.us\">http://unps.us</a>.</div>");
    } 

	require('Include/PHP/functions.php');

	if(!empty($_POST['link'])){
    	$short = sanitize($_POST['link']);
        if(strpos($short, "http://") === false && strpos($short, "https://") === false){
            $short = "http://$short";
        }
    	//echo shorten($shortdb, $short);
	   }  
    }else{ die("<div id=\"error\">I can't do my job if I'm not given a link to work on...</div>"); }

?>