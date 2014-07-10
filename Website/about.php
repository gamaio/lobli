<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>lob.li - Objective Links | About Us</title>

    <!-- Bootstrap -->
    <link href="include/Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/css/style.css?<?php echo time(); ?>" rel="stylesheet">

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
          <h2 class="form-shorten-heading">About Lob.li</h2>
          <div class="text-success bg-success alert">
            Lob.li is a link shortener. That's the easiest way to describe what this service does and is about.
            Hi, I'm c0de. I developed this website with many hours of work and lots of distractions. 
            This site is very JavaScript and CSS heavy and unfortunatly breaks on text-only browsers and browsers with JavaScript disabled.

            <h3>A little background on this site...</h3>
              The domain lob.li was pretty much an attempt to make a pronounceable and memorable domain
              A lot of the backend code is from my older link shortener, <a href="http://unps.us">UnPS</a>.
              The base code has gone through many iterations, but only versions 2 through 4-2 are supported.

            <h3>Terms of Service...</h3>
            <ul>
              <li>No already shortened links (bit.li, ad.fly, etc)</li>
              <li>No Illegal actions, or websites promoting illegal actions</li>
              <li>No Spam. Nobody likes it, don't use my service to distribute spam</li>
              <li>No Linking to viruses and other malware</li>
              <li>Anything that I feel would cause harm to this service</li>
            </ul>
            NOTE: I clean up the database from time to time and manually inspect links for anything that violates these rules.
            They will be removed without notice.
          </div>
        </div>
        <div class="col-md-3"></div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="include/Bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" language="JavaScript">
      jQuery(document).ready(function(){
        $('#aboutlink').addClass('active');
      });
    </script>
  </body>
</html>
