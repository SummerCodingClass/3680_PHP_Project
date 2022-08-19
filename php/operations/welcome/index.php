<?php
session_start();

?>




<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width,initial-scale=1">
    
        <title>Welcome to Pocket List</title>

    </head>

    <body>

        <h1>Welcome to Pocket List</h1>

        <form action="login.php" method="POST">
            <input type="text" name="username" value="<?=$_SESSION["inputUsername"]?>" placeholder="username"> <br> <br>
            <!-- <input type="password" name="password" value="<?=$_SESSION["inputPassword"]?>" placeholder="*****"> <br> <br> -->
            <input type="password" name="password" placeholder="*****"> <br> <br>
            <input type="submit" name="login" value="Log In">
        </form>

        <p>New to this site? <a href="new_user.php">Click here to register for an account.</a></p>
        

        <?php


            if (isset($_SESSION["result"]) || isset($_SESSION["error"]) || isset($_SESSION["error1"])) {
                if (isset($_SESSION["result"])) {
                    $result = $_SESSION['result'];
                    echo "<div style='color: green'>$result</div>";
                    unset($_SESSION["result"]);
                }
                if (isset($_SESSION["error"])) {
                    $error = $_SESSION['error'];
                    echo "<div style='color: red'>$error</div>";
                    unset($_SESSION["error"]);
                }
                if (isset($_SESSION["error1"])) {
                    $error1 = $_SESSION['error1'];
                    echo "<div style='color: red'>$error1</div>";
                    unset($_SESSION["error1"]);
                }
            }
            else {
                echo "<div style='color: blue'> Welcome to Pocket List </div>";
            }


            if (isset($_SESSION["authorized"])) {
                $authorized = $_SESSION["authorized"];

                if ($authorized) {
                    header("Location: home.php");
                }

                
            }

        ?>

    </body>

</html>