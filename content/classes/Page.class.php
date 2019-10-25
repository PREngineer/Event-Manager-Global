<?php

require_once 'autoloader.php';

class Page
{
  
  //------------------------- Attributes -------------------------
  public $content = "<br><h1>This page was not instantiated correctly.</h1>" . "\r\n";
  public $title = "Event Manager";
  public $keywords = "event manager";
  public $NavBar;
  
  //------------------------- Operations -------------------------
  
  public function __construct()
  {
    $this->NavBar = new PageNavBar();
  }

  public function __Set($name, $value)
  {
    $this->$name = $value;
  }

  public function Display()
  {

    echo '<!DOCTYPE html>
    
    <html lang="en">
    
    <!-- ******************* Header Section ******************* -->
    
    <!-- ******************* Head Section ******************* -->
    <head>
    
      <!-- Encoding and Mobile First -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
      <!-- Bootstrap core CSS -->
      <link href="theme/css/bootstrap-theme.min.css" rel="stylesheet">
      <link href="theme/css/bootstrap.min.css" rel="stylesheet">
      <link href="theme/css/bootstrap-datepicker3.standalone.min.css" rel="stylesheet">
      <link href="theme/css/bootstrap-datepicker3.min.css" rel="stylesheet">
      <link href="theme/css/jquery.timepicker.min.css" rel="stylesheet">
    
      <!-- Importing jQuery and other dependencies -->
      <script src="theme/js/jquery-3.2.1.min.js"></script>
      <script src="theme/js/bootstrap-datepicker.min.js"></script>
      <script src="theme/js/jquery.timepicker.min.js"></script>
      <script src="theme/js/BootstrapValidator.min.js"></script>
      <script src="theme/js/validator.js"></script>
    
      <!-- Bootstrap JavaScript -->
      <script src="theme/js/bootstrap.js"></script>
    
      <!-- PWA Manifest -->
      <link rel="manifest" href="manifest.json">
      <script src="manup.js"></script>
    
      <!-- PWA Service Worker -->
      <script src="sw.js"></script>
      <script src="sw-reg.js"></script>
    
      <!-- Generic App Information -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="A progressive web application to manage events.">
      <meta name="author" content="Jorge Pabon">
    
      <!-- Basic Mobile Information -->
      <link rel="icon" sizes="192x192" href="../images/TLogo.png">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="theme-color" content="#2196F3">
    
      <!-- For Apple Devices -->
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="application-name" content="EM">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <meta name="apple-mobile-web-app-title" content="EM">
      <link rel="apple-touch-icon" href="../images/Logo.png">
    
      <!-- For Microsoft Devices -->
      <meta name="msapplication-TileImage" content="../images/TLogo.png">
      <meta name="msapplication-TileColor" content="#2196F3">
    
      <!-- ... -->
      <meta property="og:title" content="Event Manager">
      <meta property="og:type" content="website">
      <meta property="og:image" content="../images/TLogo.png">
      <meta property="og:url" content="' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">
      <meta property="og:description" content="A progressive web application to manage events.">
    
      <!-- Twitter References -->
      <meta name="twitter:card" content="summary">
      <meta name="twitter:url" content="' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">
      <meta name="twitter:title" content="EM">
      <meta name="twitter:description" content="A progressive web application to manage events.">
      <meta name="twitter:image" content="../images/TLogo.png">
      <meta name="twitter:creator" content=@PianistaPR>
      
      <!-- Page Title -->
      <title>' . $this->title . '</title>
      
      <meta name="keywords" content="Event Manager Platform"/>

    </head>
    
    <body>' . "\r\n";
    
    echo $this->NavBar->Display();
    
    echo '<!-- ******************* Content Section ******************* -->
    <div class="container" id="Content">' . "\r\n";

    echo $this->content;
    
    echo '</div>
    
    <!-- ******************* Footer Section ******************* -->
    <div class="container-fluid">
    
    <!-- ******************* Footer Section ******************* -->
      <div class="container">
        <div class="nav navbar-inverse navbar-fixed-bottom">
          <p class="text-center text-muted">© 2017-'. DATE("Y") . 'My Company</p>
        </div>
      </div>
    </div>
    
    </body>
    
    </html>';
  }

}

?>