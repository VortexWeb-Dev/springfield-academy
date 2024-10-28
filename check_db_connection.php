<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to establish database connection
function getDatabaseConnection()
{
    // Database connection details
    $servername = 'localhost';
    $username = 'u884492537_app_user';
    $password = '^sU4n@3u#kS';
    $dbname = 'u884492537_training';
    $port = 3306;

    // Create a new mysqli connection object with a timeout
    $conn = new mysqli();
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5); // Set a 5 second timeout

    // Try to connect to the database
    $conn->real_connect($servername, $username, $password, $dbname, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Usage example
$conn = getDatabaseConnection();

if ($conn) {
    echo "Connection to the database was successful!<br>";

    // Execute a sample query to test the connection
    $query = "SHOW TABLES";
    $result = $conn->query($query);

    if ($result) {
        echo "Tables in the database:<br>";
        while ($row = $result->fetch_array()) {
            echo $row[0] . "<br>";
        }
    } else {
        echo "Error executing query: " . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    echo "Failed to connect to the database.";
}

?>
