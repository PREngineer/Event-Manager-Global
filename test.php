<?php

$dsn = 'mysql:host=localhost;port=3306;dbname=MYORG;charset=utf8';

try
{
    $conn =  new PDO($dsn, 'root', 'root');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
}
catch(PDOException $error)
{
    print_r($error->getMessage());
}

?>