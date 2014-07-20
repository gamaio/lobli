<?php
  session_start();

  // Generate a token on the fly. This should prevent POST spam attacks directly into process.php
  $token = substr(number_format(time() * mt_rand(),0,'',''),0,10); 
  $token = base_convert($token, 10, 36); 
  $_SESSION['token'] = $token;

  $catchid = substr(number_format(time() * mt_rand(),0,'',''),0,10);
  $catchVal = hash('sha256', $catchid.mt_rand().time().substr(number_format(time() * mt_rand(),0,'',''),0,10));
  $catchVal = base_convert($catchVal.$catchid, 10, 36);
  $_SESSION['catch'] = $catchid.":".$catchVal;

  require('Include/PHP/db.php');
  $redis = new Redis();
  $redis->connect('127.0.0.1', 6379);

  function followLink($shortdb, $redis, $link){
    $link = $shortdb->real_escape_string(strtolower(stripslashes(strip_tags($link))));
    $link = str_replace('/', '', $link);
    
    $sql = "SELECT * FROM `tracking` WHERE `id` = '$link' LIMIT 1;"; // Testing to see if the link has been visited before
    if($result = $shortdb->query($sql)){
      if($row = $result->fetch_assoc()){ 
        $sql = "UPDATE `tracking` SET `clicks` = `clicks` + 1 WHERE `id` = '$link'"; // Yes it has, increment clicks by 1
        if($result = $shortdb->query($sql)){
          if($result->num_rows == 0){
            die ($shortdb->error);
          }
        }
      }
    }else{
      $sql = "INSERT INTO `tracking` (id, clicks) VALUES ('$link', 1)"; // No it hasn't, add 1 click to the table
      if($result = $shortdb->query($sql)){
        if($result->num_rows == 0){
          die ($shortdb->error);
        }
      }
    }

    // Try to find it in the redis db first, if not there, add it

    $short = $redis->get($link);
    if (!$short) {
      $sql = "SELECT * FROM `links` WHERE `shortlink` = '$link' LIMIT 1;";
      if($result = $shortdb->query($sql)){
        if($row = $result->fetch_assoc()){
          $llink = $row['link'];

          $redis->set($link, $llink);

          echo $llink

          //header("location:$link");
          exit(5); // Stop script execution to save on resources
        }
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

  // This has been depreciated. Still here for backwards compatibility with existing links
  if(!empty($_GET['l'])){
    followLink($shortdb, $redis, $_GET['l']);
  }

  // New way to check for valid short links, two characters shorter than the if statement above
  if(!empty($_GET)){
    $key = key($_GET);

    if($key == "stats"){ header("location:http://s.lob.li"); exit(11); }
    if($key == "resolv"){ header("location:http://r.lob.li"); exit(12); }
    if($key == "about"){ header("location:http://a.lob.li"); exit(13); }
    
    followLink($shortdb, $redis, $key);
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
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <h2 class="form-shorten-heading">Please give me a link to shorten...</h2>
          <form class="form-shorten form-inline" id="form-shorten" role="form">
            <div class="input-group">
              <input type="text" class="form-control input-lg" id="link" name="link" placeholder="http://" required autofocus>
              <input type="hidden" name="<?php echo $catchid; ?>" value="<?php echo $catchVal; ?>"/>
              <span class="input-group-btn">
                <button type="submit" class="btn btn-primary btn-lg submitbtn" id="short-button">
                  <span class="glyphicon glyphicon-share-alt icon-rotate"></span>
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
        <p class="text-muted">Copyright &copy; 2014 Unified Programming Solutions - Version: 0.0.1</p>
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
        $.post("process.php?token=<?php echo $token; ?>", $(this).serialize(), function(data){
          $("#message").hide().html(data).slideDown("fast");
          $("#theLoader").hide();
          if($('#danger').length){
            $('#short-button').removeClass("btn-primary btn-success btn-warning").addClass("btn-danger");
          }else if($('#success').length){
            $('#short-button').removeClass("btn-primary btn-danger btn-warning").addClass("btn-success");
          }else if($('#warning').length){
            $('#short-button').removeClass("btn-primary btn-success btn-danger").addClass("btn-warning");
          }
        });
      });
    </script>
  </body>
</html>
