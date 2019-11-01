<?php

require_once 'autoloader.php';

$db = new Database();

$data = $db->query_DB("SELECT DISTINCT O.Symbol
FROM Announcements A, Orgs O
WHERE A.Org_ID = O.ID
AND A.Expires >= '" . date('Y-m-d') . "'
");

$filt = array();

foreach($data as $one)
{
    $filt[] = $one['Symbol'];
}

$filter = new AnnouncementsFilter( $data );

echo $filter->Display();


?>