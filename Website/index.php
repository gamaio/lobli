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

  // exit codes:
  /*
    exit 0 - Good script
    exit 5 - Link redirection

    10x exit codes    
      exit 11 - Shortener Stats redirection
      exit 12 - Shortener Resolver redirection
      exit 13 - Shortener About redirection
  */

  $shortdb = new mysqli('localhost', 'short', 'password', 'short'); // Connect to link shortener DB
  if($shortdb->connect_errno > 0) die('Unable to connect to database [' . $shortdb->connect_error . '] - Check dbsettings.php');

  // This has been depreciated. Still here for backwards compatibility with existing links
  if(!empty($_GET['l'])){
    $link = $shortdb->real_escape_string(strtolower(stripslashes(strip_tags($_GET['l']))));
    $link = str_replace('/', '', $link);
    $sql = "SELECT * FROM `links` WHERE `shortlink` = '$link' LIMIT 1;";
    if($result = $shortdb->query($sql)){
      if($row = $result->fetch_assoc()){
        $link = $row['link'];
        header("location:$link");
        exit(5); // Stop script execution to save on resources
      }
    }
  }

  // New way to check for valid short links, two characters shorter than the if statement above
  if(!empty($_GET)){
    $key = key($_GET);

    if($key == "stats"){ header("location:http://s.lob.li"); exit(11); }
    if($key == "resolv"){ header("location:http://r.lob.li"); exit(12); }
    if($key == "about"){ header("location:http://a.lob.li"); exit(13); }
    
    $link = $shortdb->real_escape_string(strtolower(stripslashes(strip_tags($key))));
    $link = str_replace('/', '', $link);
    $sql = "SELECT * FROM `links` WHERE `shortlink` = '$link' LIMIT 1;";
    if($result = $shortdb->query($sql)){
      if($row = $result->fetch_assoc()){
        $link = $row['link'];
        header("location:$link");
        exit(5); // Stop script execution to save on resources
      }
    }
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
    <link href="include/Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/css/style.css?<?php echo time(); ?>" rel="stylesheet">

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
              <input type="text" class="form-control input-lg" id="link" placeholder="http://" required autofocus>
              <span class="input-group-btn">
                <button type="button" class="btn btn-primary btn-lg btn-block submitbtn">
                  <span class="glyphicon glyphicon-share-alt icon-rotate"></span>
                </button>
              </span>
            </div><!-- /input-group -->
          </form>
          
          <?php if(isset($_GET['errmsg'])){ ?>

          <div class="alert alert-danger" id="message">
            Oh noes! An error has occured. 
          </div>

          <?php } if(isset($_GET['gomsg'])){ ?> 

          <div class="alert alert-success" id="message">
            Your link: <a href="#" title="HTML Title of website being shortened">lob.li/12345</a>
              
            <!--<a href="#" id="newlink" title="New Link"> This would require changing how I generate links, and I don't feel like doing it right now - 6/22/12 1:21am EST
              <span class="glyphicon glyphicon-refresh" style="float:right;"></span>
            </a>-->
            <a href="#" id="copylink" title="Copy Link" onclick="copyToClipboard('http://lob.li/$short');">
              <span class="glyphicon glyphicon-link" style="float:right;padding-right:1%;"></span>
            </a>
          </div>

          <?php } ?>

        </div>
        <div class="col-md-3"></div>
      </div>
    </div>

    <div id="footer" style="position:absolute;width:100%;bottom:1px;">
      <div class="container">
        <p class="text-muted">Copyright &copy; 2014 Unified Programming Solutions - Version: 0.0.1 - <a href="?gomsg">Success link</a> <a href="?errmsg">Error Link</a></p>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="include/Bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" language="JavaScript">
      jQuery(document).ready(function(){
        $('#link').focus();
        //$('#message').addClass('hide');
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
          $("#message").hide().slideDown("fast");
          $("#theLoader").hide();
          if($('#error').length){
            $('#short-button').removeClass('btn-primary');
            $('#short-button').removeClass('btn-success');
            $('#short-button').addClass('btn-danger');
          }else if($('#success').length){
            $('#short-button').removeClass('btn-primary');
            $('#short-button').removeClass('btn-danger');
            $('#short-button').addClass('btn-success');
          }
        });
      });
    </script>
  </body>
</html>
