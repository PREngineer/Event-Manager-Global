<?php

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
// Handle MyRSVPs
else if( $_GET['display'] === 'MyRSVPs' )
{
    $page = new MyRSVPs();
    $page->Display( $_POST );
}
// Not instantiated properly
else
{
    $page = new Page();
    $page->Display();
}


/****************
    Global Admin Pages
****************/

/****************
    Org Admin Pages
****************/

/****************
    POC Pages
****************/

/****************
    Approver Pages
****************/

echo '<br><br>';
// print_r($_POST);
?>