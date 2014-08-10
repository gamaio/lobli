<?php
    session_start();

    $catches = explode(":", $_SESSION['catch']);
    $catchid = $catches[0];
    $catchVal = $catches[1];

    $seperator = "ᐖ"; // Chosen because it looks like a smiling face

    // Returns will be in the structure: Message code / seperator / extra data and will be formatted client side
    /*
        Message codes:
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

        $short = "";
        $link = "";
        $error = "";
        $title = "";

    $messages = array(
        "
            <div class=\"alert alert-success\" id=\"success\">
                Your Resolved link: <a href=\"$link\" title=\"$title\">
                <span class=\"longlink\">$link</span></a>
                  
                <a href=\"#\" id=\"copylink\" title=\"Copy Link\" onclick=\"copyToClipboard('$link');\">
                  <span class=\"glyphicon glyphicon-link\" style=\"float:right;padding-right:1%;\"></span>
                </a>
            </div>
        ",
        "
            <div class=\"alert alert-warning\" id=\"warning\">
                Your link: <a href=\"$link\" title=\"$title\">
                <span class=\"longlink2\">$link</span></a> is not a lob.li link.<br> However we found that it has been shortened. <a href=\"http://lob.li/$short\" title=\"$title\">lob.li/$short</a>
                <a href=\"#\" id=\"copylink\" title=\"Copy Link\" onclick=\"copyToClipboard('http://lob.li/$short');\">
                  <span class=\"glyphicon glyphicon-link\" style=\"float:right;padding-right:1%;\"></span>
                </a>
            </div>
        ",
        "
            <div class=\"alert alert-warning\" id=\"warning\">
                Your link: <a href=\"$link\" title=\"$title\">
                <span class=\"longlink\">$link</span></a> is not a lob.li link and has not been shortened.
            </div>
        "
        );

	require('Include/PHP/functions.php');

	if(!empty($_POST['link']) || !empty($_POST['linkage'])){
        if(empty($_GET['token']) || $_GET['token'] != $_SESSION['token'] || empty($_POST[$catchid]) || $_POST[$catchid] != $catchVal){ 
            die("<div id=\"danger\" class=\"alert alert-danger\">Oh Noes! Something happened and I can't continue.<br />Please try again by using the form located at <a href=\"http://lob.li\">lob.li</a>.</div>");
        } 

    	//$short = sanitize($_POST['link'], $seperator);
        $short = $_POST['link'];
        $linkage = $_POST['linkage'];
        //echo $short;
        if(strpos($short, "http://") === false && strpos($short, "https://") === false){
            $short = "http://$short";
        }

        $reShort = shorten($redis, $short, $linkage, $seperator);
        $reShort = explode($seperator, $reShort);
        $retCode = $reShort[0];

        switch($retCode){
            case "0": // Successful link Shorten
                $short = $reShort[1];
                $title = $reShort[2];
                echo "
                    <div class=\"alert alert-success\" id=\"success\">
                        Your link: <a href=\"http://lob.li/$short\" title=\"$title\" target=\"lobli.$short\">lob.li/$short</a>
                        <a href=\"#\" id=\"copylink\" title=\"Copy Link\" onclick=\"copyToClipboard('http://lob.li/$short');\">
                            <!--<a href=\"#\" id=\"newlink\" title=\"New Link\"> This would require changing how I generate links, and I don't feel like doing it right now - 6/22/12 1:21am EST
                              <span class=\"glyphicon glyphicon-refresh\" style=\"float:right;\"></span>
                            </a>-->
                            <span class=\"glyphicon glyphicon-link\" style=\"float:right;padding-right:1%;\"></span>
                        </a>
                    </div>
                ";
                break;

            case "1": // Existing Short Link Found
                $short = $reShort[1];
                $title = $reShort[2];
                echo "
                    <div class=\"alert alert-warning\" id=\"warning\">
                        Existing link: <a href=\"http://lob.li/$short\" title=\"$title\" target=\"lobli.$short\">lob.li/$short</a>
                         <a href=\"#\" id=\"copylink\" title=\"Copy Link\" onclick=\"copyToClipboard('http://lob.li/$short');\">
                            <span class=\"glyphicon glyphicon-link\" style=\"float:right;padding-right:1%;\"></span>
                        </a>
                    </div>
                ";
                break;

            case "2": // Dead Link
                $link = $reShort[1];
                echo "
                    <div class=\"alert alert-danger\" id=\"danger\">
                        ERROR! - Your link: <a href=\"$link\" target=\"$link\">$link</a> didn't resolve to a website. <br />Please check your link and try again.
                    </div>
                ";
                break;

            case "3": // DB Error
                $error = $reShort[1];
                echo "
                    <div class=\"alert alert-danger\" id=\"danger\">
                        ERROR! - Well this is embarrassing... This never happens, but I appear to have suffered a database error. <br />Here's what I know: $error
                    </div>
                ";
                break;

            case "4": // Sanitize Failure Error
                echo "
                    <div class=\"alert alert-danger\" id=\"danger\">
                        ERROR! - The sanitize function seems to have failed. This shouldn't happen, maybe <a href=\"mailto:c0de@unps.us\">c0de</a> forgot a semi-colon somewhere or something. 
                    </div>
                ";
                break;

            default:
                echo "<div id=\"danger\" class=\"alert alert-danger\">Oh Noes! Something happened and I can't continue.<br />Please try again by using the form located at <a href=\"http://lob.li\">lob.li</a>.</div>";
                break;
        }

        exit;

        //foreach($messages as $message){
        //    echo $message;
        //}

    }else{ die("<div id=\"danger\" class=\"alert alert-danger\">I can't do my job if I'm not given a link to work on...</div>"); }

?>