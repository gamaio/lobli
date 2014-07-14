<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>lob.li - Objective Links | Link Resolver</title>

    <!-- Bootstrap -->
    <link href="include/Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/css/style.css?<?php echo time(); ?>" rel="stylesheet">
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
          <h2 class="form-shorten-heading">Enter lob.li link to resolve below</h2>
          <form class="form-shorten form-inline" id="form-shorten" role="form">
            <div class="input-group">
              <span class="input-group-addon">http://lob.li/</span>
              <input type="text" class="form-control input-lg" id="link" placeholder="id" required autofocus>
              <input type="hidden" name="<?php echo $catchid; ?>" value="<?php echo $catchVal; ?>"/>
              <span class="input-group-btn">
                <button type="submit" class="btn btn-primary btn-lg submitbtn">
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
    <script src="include/Bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" language="JavaScript">
      jQuery(document).ready(function(){
        $('#link').focus();
        //$('#message').addClass('hide');
        $('#resolink').addClass('active');
      });

      function copyToClipboard(text){
        window.prompt ("Copy to clipboard: Ctrl+C, Enter (when closed I will open your link in a new tab)", text);
      }

      $(function(){
        $(".longlink").each(function(i){
          len=$(this).text().length;
          if(len > 47){
            $(this).text($(this).text().substr(0, 47) + '...');
          }
        });       
      });

      $(function(){
        $(".longlink2").each(function(i){
          len=$(this).text().length;
          if(len > 25){
            $(this).text($(this).text().substr(0, 25) + '...');
          }
        });       
      });
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
