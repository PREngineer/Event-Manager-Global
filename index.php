<?php

require_once ('init.php');

/**
 * This file serves as a router for all the requests to the application.
 * It will call the appropriate class depending on the request type.
 */

require_once 'autoloader.php';

/****************
    Top Pages
****************/

// Handle Announcements
if( $_GET['display'] === 'Announcements' )
{
    $page = new Announcements();
    $page->Display( $_POST['filter'] );
}
// Handle Create New Member
else if( $_GET['display'] === 'CreateMember' )
{
    $page = new CreateMember();
    $page->Display( $_POST );
}
// Handle Current Events
else if( $_GET['display'] === 'CurrentEvents' || !isset($_GET['display']) )
{
    $page = new CurrentEvents();
    $page->Display( $_POST );
}
// Handle Future Events
else if( $_GET['display'] === 'FutureEvents' )
{
    $page = new FutureEvents();
    $page->Display( $_POST );
}
// Handle Future Event Details
else if( $_GET['display'] === 'FutureEventDetails' )
{
    $page = new FutureEventDetails( $_GET['id'] );
    $page->Display();
}
// Handle Login
else if( $_GET['display'] === 'Login' )
{
    $page = new Login();
    $page->Display( $_POST );
}
// Handle Logout
else if( $_GET['display'] === 'Logout' )
{
  unset($_SESSION);
  session_destroy();

  echo '
    <script>
      window.location = "index.php";
    </script>
  ';
}
// Handle MyRSVPs
else if( $_GET['display'] === 'MyRSVPs' )
{
    $page = new MyRSVPs();
    $page->Display( $_POST );
}

/****************
    Global Admin Pages
****************/

// Handle Global Admin
else if( $_GET['display'] === 'GlobalAdminMenu' )
{
    $page = new GlobalAdminMenu();
    $page->Display();
}
// Handle Global Admin
else if( $_GET['display'] === 'AdminMenu' )
{
    $page = new AdminMenu();
    $page->Display();
}
// Handle Global Admin
else if( $_GET['display'] === 'POCMenu' )
{
    $page = new POCMenu();
    $page->Display();
}
// Handle Global Admin
else if( $_GET['display'] === 'ApproverMenu' )
{
    $page = new ApproverMenu();
    $page->Display( $_GET );
}

/****************
    Org Admin Pages
****************/

/****************
    POC Pages
****************/

/****************
    Approver Pages
****************/


// Not instantiated properly
else
{
    $page = new Page();
    $page->Display();
}


echo '<br><br>';
// print_r($_POST);
?>