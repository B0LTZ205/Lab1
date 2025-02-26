<?php 

$host = "localhost";
$dbName = "itemborrowingdb";
$userName = "root";
$password = "";

try 
{
    $conn = new PDO("mysql:host={$host}; dbname={$dbName}",$userName,$password);
}

catch (Exception $e)
{
    echo "ERROR: ",$e->getMessage();
}
?>