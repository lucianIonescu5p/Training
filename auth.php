<?php

if (!$_SESSION['authenticated']) {
    header('Location: login.php?unauthorized');
    die();
};
