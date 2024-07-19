<?php

if (!isset($_COOKIE['visits'])) {
    $_COOKIE['visits'] = 0;
}

$visits = $_COOKIE['visits'] + 1;

//setcookie('visits', $visits, [
//    'expires' => time() + 3600 * 24 * 365,
//    'httponly' => true,
//]);
setcookie(name: 'visits', value: $visits, expires_or_options: time() + 3600 * 24 * 365, httponly: true);

if ($visits > 1) {
    echo "This is visit number $visits.";
} else {
    // First visit
    echo 'Welcome to our website! Click here for a tour!';
}