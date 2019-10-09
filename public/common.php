<?php
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