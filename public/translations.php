<?php

// translation function
function trans($label) 
{
    $translations = [
        'ID' => '#'
    ];

    return isset($translations[$label]) ? $translations[$label] : $label;
};