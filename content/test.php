<?php

require_once 'autoloader.php';

$db = new Database();

$data = $db->query_DB('SELECT * FROM Users');

print_r($data[0]);

?>