<?php
session_start();


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_GET["logout"])) {
    // $_SESSION["authorized"] = false;
    // unset($_SESSION["result"]);
    // unset($_SESSION["uid"]);

    session_unset();
    session_destroy();

}

if (isset($_SESSION["authorized"])) {
    $authorized = $_SESSION["authorized"];

    if ($authorized) {
        // do nothing. user can stay on this page.
    }

    else {
        header("Location: index.php");
    }
}

else {
    header("Location: index.php");
}



?>



<!-- 
<!DOCTYPE html>
-->

<!-- <html lang="en"> -->
    
<!-- 
    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width,initial-scale=1">
    
        <title>Welcome to Pocket List</title>

    </head>

    <body>
 -->

    <form action=<?=$_SERVER['PHP_SELF']?> method="GET">
        <input type="submit" name="logout" value="Log out">
    </form>
<!-- 
    </body>

</html> -->