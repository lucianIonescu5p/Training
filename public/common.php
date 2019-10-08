<?php
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
    // Check connection

    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }