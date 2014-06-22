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
            Your link: <a href="#" title="HTML Title of website being resolved">
            <span class="longlink">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
            </span></a>
              
            <a href="#" id="copylink" title="Copy Link">
              <span class="glyphicon glyphicon-link" style="float:right;padding-right:2%;"></span>
            </a>
          </div>

          <?php } if(isset($_GET['warnmsg'])){ ?>

          <div class="alert alert-warning" id="message">
            Your link: <a href="#" title="HTML Title of website being resolved">
            <span class="longlink2">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
            </span></a> is not a lob.li link.<br> However we found that it has been shortened. <a href="#" title="HTML Title of website being shortened">lob.li/123</a>
            <a href="#" id="copylink" title="Copy Link">
              <span class="glyphicon glyphicon-link" style="float:right;padding-right:2%;"></span>
            </a>
          </div>

          <?php } if(isset($_GET['warnmsg2'])){ ?>

          <div class="alert alert-warning" id="message">
            Your link: <a href="#" title="HTML Title of website being resolved">
            <span class="longlink">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
            </span></a> is not a lob.li link and has not been shortened.
          </div>

          <?php } ?>

        </div>
        <div class="col-md-3"></div>
      </div>
    </div>

    <div id="footer" style="position:absolute;width:100%;bottom:1px;">
      <div class="container">
        <p class="text-muted">Copyright &copy; 2014 Unified Programming Solutions - Version: 0.0.1 - <a href="?gomsg">Success link</a> <a href="?errmsg">Error Link</a> <a href="?warnmsg">Warn Link</a> <a href="?warnmsg2">Warn Link 2</a></p>
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
