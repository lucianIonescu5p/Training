<?php
session_start();

require_once 'config.php';

$conn = new PDO("mysql:host=".SERVERNAME.";dbname=".DBNAME."", USERNAME, PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

//translation function
function trans($label) 
{
    $translations = [
        'ID' => '#'
    ];

    return isset($translations[$label]) ? $translations[$label] : $label;
};

//data test function
function sanitize_input($data) {
    
    $data = trim($data);
    $data = strip_tags($data);

    return $data;
  }