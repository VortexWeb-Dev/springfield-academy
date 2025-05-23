<?php

function getDatabaseConnection()
{
    //Database connection details
    $servername = 'localhost'; // Change to your actual DB host
    $username = 'root'; // Your DB username
    $password = ''; // Your DB password
    $dbname = 'academy'; // Your DB name
    
    //$servername = "localhost";
    //$username = "root";
    //$password = "";
    //$dbname = "academy";
    
    $port = 3306; // Default MySQL port

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Usage example
$conn = getDatabaseConnection();
