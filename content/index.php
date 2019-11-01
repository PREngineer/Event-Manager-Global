<?php

require_once 'autoloader.php';

if( $_GET['display'] === 'Announcements' )
{
    $page = new Announcements();
    $page->Display( $_POST['filter'] );
}
else
{
    $page = new Page();
    $page->Display();
}

?>