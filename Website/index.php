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
    <div class="container">

      <div class="header center-block">
        <h3>lob.li - Objective Links</h3>
      </div>

      <!-- Static navbar -->
      <div class="navbar" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a class="active" href="#">Home</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Stats</a></li>
              <li><a href="#">Resolver</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#">fb</a></li>
              <li><a href="#">tw</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>

      <br class="spacer" />

      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <form class="form-shorten form-inline" role="form">
            <div class="input-group">
              <input type="text" class="form-control input-lg"  placeholder="http://" required autofocus>
              <span class="input-group-btn">
                <button type="button" class="btn btn-success btn-lg btn-block submitbtn">
                  <span class="glyphicon glyphicon-arrow-right"></span>
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
              
            <a href="#" id="newlink" title="New Link">
              <span class="glyphicon glyphicon-refresh" style="float:right;"></span>
            </a>
            <a href="#" id="copylink" title="Copy Link">
              <span class="glyphicon glyphicon-link" style="float:right;padding-right:2%;"></span>
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
  </body>
</html>
