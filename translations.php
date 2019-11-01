<?php

/**
 * @param $label
 * @return mixed
 */
function trans($label) 
{
    $translations = [
        'ID' => '#'
    ];

    return isset($translations[$label]) ? $translations[$label] : $label;
};