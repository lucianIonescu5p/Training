<?php
session_start();

require_once 'config.php';

$conn = new PDO("mysql:host=".SERVERNAME.";dbname=".DBNAME."", USERNAME, PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function trans($label) 
{
    $translations = [
        'ID' => '#'
    ];

    return isset($translations[$label]) ? $translations[$label] : $label;
};

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }