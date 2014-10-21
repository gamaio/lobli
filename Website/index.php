<?php
  session_start();

  /*
      Redis scheme:
      links:
        id:
          link id - set to website
        title:
          link id - set to page description
        date:
          link id - set to today's date
      tracking:
        clicks:
          link id - int, increments with each unique IP
        ip:
          link id - list holding all visiting IPs for that link
  */
  require('Include/PHP/token.php');

  $catchid = substr(number_format(time() * mt_rand(),0,'',''),0,10);
  $catchVal = hash('sha256', $catchid.mt_rand().time().substr(number_format(time() * mt_rand(),0,'',''),0,10));
  $catchVal = base_convert($catchVal.$catchid, 10, 36);
  $_SESSION['catch'] = $catchid.":".$catchVal;

  function followLink($redis, $link){
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // Get true IP of visiter if going through cloudflare
      $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }

    $ipTrack = $redis->lRange("tracking:ip:$link", 0, -1);
    if(!in_array($_SERVER['REMOTE_ADDR'], $ipTrack)){ // Check to see if visiter hit this link before (This would make it a lot easier to skew statistics if anyone would register multiples times)
      $redis->rPush("tracking:ip:$link", $_SERVER['REMOTE_ADDR']);

      $trTtl = $redis->ttl("links:$link");

      if($trTtl != -2){
        $tracking = $redis->zIncrBy("tracking:clicks", 1, $link);
      }else{
        if($trTtl == -2){ // The link has been deleted, no need to track it anymore
          $redis->zRem("tracking:clicks", $link);
        }
      }
    }

    $short = $redis->lRange("links:$link", 0, 0);
    if($short){
      header('location:'.$short[0]);
      exit(5);
    }
  }

  // exit codes:
  /*
    exit 0 - Good script
    exit 5 - Link redirection

    10x exit codes    
      exit 11 - Shortener Stats redirection
      exit 12 - Shortener Resolver redirection
      exit 13 - Shortener About redirection
  */

  if(!empty($_GET)){
    $key = key($_GET);

    if($key == "l") $key = $_GET['l'];

    if($key == "stats"){ header("location:http://s.lob.li"); exit(11); }
    if($key == "resolv"){ header("location:http://r.lob.li"); exit(12); }
    if($key == "about"){ header("location:http://a.lob.li"); exit(13); }
    
    followLink($redis, $key);
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>lob.li - Objective Links</title>

    <!-- Bootstrap -->
    <link href="Include/Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="Include/CSS/style.css?<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Montserrat">

    <link rel="shortcut icon" type="image/ico" href="lobli.ico"/>
    <link rel="shortcut icon" type="image/x-icon" href="lobli.ico"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container center-block">

      <?php include('Include/HTML/navbar.htm') ?>

      <div class="row">
        <div class="col-md-3">
          <div class="linkage">How long should I keep your link?</div>
        </div>
        <div class="col-md-6">
          <h2 class="form-shorten-heading">Please give me a link to shorten...</h2>
          <form class="form-shorten form-inline" id="form-shorten" role="form">
            <div class="input-group">
              <span class="input-group-addon lexp">
                <select name="linkage" id="linkage">
                  <option value="0" selected="selected">24hrs</option>
                  <option value="1">1 Week</option>
                  <option value="2">1 Month</option>
                  <option value="3">Forever</option>
                </select>
              </span>
              <input type="text" class="form-control input-lg" id="link" name="link" placeholder="http://" required autofocus>
              <input type="hidden" name="<?php echo $catchid; ?>" value="<?php echo $catchVal; ?>"/>
              <span class="input-group-btn">
                <button type="submit" class="btn btn-primary btn-lg submitbtn" id="short-button">
                  <span class="glyphicon glyphicon-chevron-down"></span>
                </button>
              </span>
            </div><!-- /input-group -->
          </form>
          <div id="message">
            <div id="theLoader">
              <div class="wrap">
                <div class="loading">
                  <span class="title">loading....</span>
                  <span class="text">Please Wait</span>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="col-md-3"></div>
      </div>
    </div>

    <div id="footer" style="position:absolute;width:100%;bottom:1px;">
      <div class="container">
		<div style="padding-left:100%;"><a href="http://lob.li/1mq4" target="lobli.chrome"><img src="ChromeWebStore_BadgeWBorder_v2_206x58.png" alt="chrome web store" /></a></div>
        <p class="text-muted">Copyright &copy; 2014 Unified Programming Solutions - Version: 0.0.1 </p>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="Include/Bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" language="JavaScript">
      jQuery(document).ready(function(){
        $('#link').focus();
        $('#homelink').addClass('active');
      });

      function copyToClipboard(text){
        window.prompt ("Copy to clipboard: Ctrl+C, Enter (when closed I will open your link in a new tab)", text);
      }
    </script>
    <script type="text/javascript" language="JavaScript">
      // This is our AJAX - Thank you Wizzy <3
      $("#form-shorten").submit(function(event){
        $("#theLoader").fadeIn("fast");
        event.preventDefault();
        event.stopPropagation();
        $.post("process.php", $(this).serialize(), function(data){
          $("#message").hide().html(data).slideDown("fast");
          $("#theLoader").hide();
        });
      });
    </script>
  </body>
</html>
