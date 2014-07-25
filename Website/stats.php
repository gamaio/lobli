<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>lob.li - Objective Links | Link Statistics</title>

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
          <h2 class="form-shorten-heading">Lob.li Link Statistics</h2>
          <table class="table table-condensed">
            <thead>
              <tr class="success">
                <th></th>
                <th>LinkID</th>
                <th>Resolved Link</th>
                <th>Total Clicks</th>
                <th>Date Added</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td>
                   <div id="theLoader" style="padding-left:10%;">
                    <div class="wrap">
                      <div class="loading">
                        <span class="title">loading....</span>
                        <span class="text">Please Wait</span>
                      </div>
                    </div>
                </td>
              </tr>
              <tr class="success">
                <td></td>
                <td class="centertab"><a href="#">1</a></td>
                <td><a href="#" title="Resolved website title" class="res">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></td>
                <td class="centertab">44444</td>
                <td>00/00/0000</td>
              </tr>
              <tr class="success">
                <td></td>
                <td class="centertab"><a href="#">6</a></td>
                <td><a href="#" title="Resolved website title" class="res">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></td>
                <td class="centertab">44444</td>
                <td>00/00/0000</td>
              </tr>
              <tr class="success">
                <td></td>
                <td class="centertab"><a href="#">236</a></td>
                <td><a href="#" title="Resolved website title" class="res">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></td>
                <td class="centertab">44444</td>
                <td>00/00/0000</td>
              </tr>
              <tr class="success">
                <td></td>
                <td class="centertab"><a href="#">f42</a></td>
                <td><a href="#" title="Resolved website title" class="res">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></td>
                <td class="centertab">44444</td>
                <td>00/00/0000</td>
              </tr>
              <tr class="success">
                <td></td>
                <td class="centertab"><a href="#">tg54<a></td>
                <td><a href="#" title="Resolved website title" class="res">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</a></td>
                <td class="centertab">44444</td>
                <td>00/00/0000</td>
              </tr>
            </tbody>
          </table>
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
        $('#statlink').addClass('active');
      });

      $(function(){
        $(".res").each(function(i){
          len=$(this).text().length;
          if(len > 35){
            $(this).text($(this).text().substr(0, 35) + '...');
          }
        });       
      });
    </script>
  </body>
</html>
