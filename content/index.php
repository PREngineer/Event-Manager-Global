<?php

require_once 'autoloader.php';

if( $_GET['display'] === 'Announcements' )
{
    echo $_GET['display'];

    $page = new Announcements();
    $page->Display();
}
else
{
    $page = new Page();
    $page->Display();
}

?>