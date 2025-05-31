<?php
    // Connect to the database
    $serverName = "";
    $dBUsername = "";
    $dBPassword = ""; 
    $dBName = "";

    // Create connection
    $connection_string = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

    
    // Check connection
    if (!$connection_string) {
        die("Failed to connect to Database: " . mysqli_connect_error());
    }
?> 
