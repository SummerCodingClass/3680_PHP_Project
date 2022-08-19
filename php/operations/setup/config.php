<?php


// server address, username, password, database name


function get_connection() {
    static $connection;
    
    if (!isset($connection)) {
        $connection = mysqli_connect('localhost_or_server_address', 'mysql_username', 'mysql_password', 'database_name') 
            or die(mysqli_connect_error());
    }
    if ($connection === false) {
        echo "Unable to connect to database<br>";
        echo mysqli_connect_error();
    }
  
    return $connection;
}

// source: /home/fac/nick/public_html/useraccounts/config.php

?>