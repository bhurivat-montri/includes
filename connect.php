<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    date_default_timezone_set("Asia/Bangkok");
    include('DB.php');

    $host = "your_database_host";
    $username = "your_database_username";
    $password = "your_database_password";
    $database = "your_database_name";
    $db = new DB($host, $username, $password, $database);
    
    include('functions.php');
?>
