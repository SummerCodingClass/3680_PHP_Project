<?php

// didn't use this file after all. but can keep for future structure revamp
// turned out to be a helpful quick ref throughout the project.

function fetchCurrentTime() {

    $date = date("Y-m-d");
    $time = date("H:i");

    $combined = $date . " ". $time;

    return $combined;
}

function getUsername() {
    if(isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $username = htmlspecialchars($username);
    }
    else {
        $username = NULL;
    }
    return $username;
}



?>