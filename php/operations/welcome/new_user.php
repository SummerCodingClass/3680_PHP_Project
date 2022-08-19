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

        <form action="register.php" method="POST">
            <input type="text" name="username" value="<?=$_SESSION["inputUsername"]?>" placeholder="username"> <br> <br>
            <!-- <input type="password" name="password" value="<?=$_SESSION["inputPassword"]?>" placeholder="*****"> <br> <br> -->
            <input type="password" name="password" placeholder="*****"> <br> <br>
            <input type="submit" name="register" value="Register">
        </form>

        <p>Already have an account? <a href="index.php">Click here to login instead.</a></p>

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
                if (isset($_SESSION["defaultListCreated"])) {
                    // $status = $_SESSION["defaultListCreated"];
                    // echo $status; 

                    if ($_SESSION["defaultListCreated"] == true) {
                        echo "<div style='color: green'> Successfully created default list for user. </div>";
                        unset($_SESSION["defaultListCreated"]);
                    }
                    else {
                        // echo "<div style='color: red'> Failed to create default list. Please contact the site administrator for help. </div>";
                        echo "<div style='color: red'> Failed to create default list. </div>";
                        unset($_SESSION["defaultListCreated"]);
                    }
                }
                if (isset($_SESSION["defaultPrefCreated"])) {
                    
                    if ($_SESSION["defaultPrefCreated"] == true) {
                        echo "<div style='color: green'> Successfully created default preferences for user. </div>";
                        unset($_SESSION["defaultPrefCreated"]);
                    }
                    else {
                        // echo "<div style='color: red'> Failed to create default list. Please contact the site administrator for help. </div>";
                        echo "<div style='color: red'> Failed to create default preferences. </div>";
                        unset($_SESSION["defaultPrefCreated"]);
                    }
                }

            }
            else {
                echo "<div style='color: blue'> Welcome to Pocket List </div>";
            }

        ?>

    </body>

</html>