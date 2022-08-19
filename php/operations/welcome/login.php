<?php
session_start();
require_once("../setup/config.php");
// require_once("../../operations/preferences/FetchPref.php");

function login($username, $password) {
    $db = get_connection(); 

    // $hashed = password_hash($password, PASSWORD_DEFAULT);

    echo 'a';

    $statement = $db->prepare("Call LogIn (?)");

    echo 'b';

    $statement->bind_param('s', $username);

    echo 'c';
    
    if (!$statement->execute()) {

        echo 'd';

        die(mysqli_error($db) . "<br>");

        echo 'e';
    }

    echo 'f';

    $result = $statement->get_result();

    echo 'g';

    while ($row = $result->fetch_assoc()) {
        // var_dump($row);

        $found = $row["Result"];
        $error = $row["Error"];
        $uid = $row["user_id"];
        $upassword = $row["user_password"];
    
        if ($found == "true") {
            $successfulLogIn = password_verify($password, $upassword);
            if ($successfulLogIn) {

                $_SESSION["result"] = "Login Successful";
                $_SESSION["authorized"] = true;
                $_SESSION["uid"] = $uid;
                $_SESSION["username"] = $username;
                $_SESSION["currentList"] = "default";
                // $_SESSION["currentSortBy"] = "add";
                // $_SESSION["currentOrder"] = "ascend";
                $_SESSION["sortbytodo"] = "add";
                $_SESSION["orderbytodo"] = "ascend";

                $_SESSION["sortbydone"] = "add";
                $_SESSION["orderbydone"] = "ascend";
                // echo "test result: success";
                // echo $_SESSION["result"] + "<br>";
                // echo $_SESSION["authorized"] + "<br>";
                // echo $_SESSION["uid"] + "<br>";


                $_SESSION["currentPref"] = "default";

                
                // echo 1;

                // $userPref = [];

                // echo 2;
                // oh coz can't run another query while handling this query in login
                // $userPref = fetchASetOfPreferences($username, "default");

                // echo 3;

                // $_SESSION["userPref"] = $userPref;

                // echo 4;

            }
            else {
                $_SESSION["error"] = "no matching combination of username & password found";    
            }
        } 
        else {
            $_SESSION["error"] = $error;
        }
    }
}


function isBlank($textfield) {
    if ((strlen($textfield) == 0) || ($textfield == "") || $textfield == null) {
        return true;
    } 
    return false;
}

function nameInvalid($username) {
    if (strlen($username) < 4 || strlen($username) > 100) {
        return true;
    }
    return false;
}

function passInvalid($password) {
    if (strlen($password) < 6 || strlen($password) > 100) {
        return true;
    }
    return false;
}





if (isset($_POST["login"])) {

    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $_SESSION["inputUsername"] = $username;
        $_SESSION["inputPassword"] = $password;

        echo 0;
        echo "<br>";

        $nameBlank = isBlank($username);
        $passBlank = isBlank($password);
       
        if ($nameBlank && $passBlank) {
            echo 1;
            echo "<br>";

            $_SESSION["error"] = "both username and password are required";
            echo $_SESSION["error"];
            header("Location: index.php");
        }
       
        else {
            echo 2;
            echo "<br>";

            $nameInvalid = nameInvalid($username);
            $passInvalid = passInvalid($password);

            if ($nameInvalid && $passInvalid) {
                echo "neither valid";
                echo "<br>";

                $_SESSION["error"] = "username must be between 4-100 characters long <br>";
                unset($_POST["username"]);

                $_SESSION["error1"] = "password must be between 6-100 characters long <br>";
                unset($_POST["password"]);
            }

            else if ($nameInvalid) {
                echo "name invalid";
                echo "<br>";

                $_SESSION["error"] = "username must be between 4-100 characters long <br>";
                unset($_POST["username"]);

                $_SESSION["error1"] = "password ok<br>";
                unset($_POST["password"]);
            }

            else if ($passInvalid) {
                echo "pass invalid";
                echo "<br>";

                $_SESSION["error"] = "username ok<br>";
                unset($_POST["username"]);

                $_SESSION["error1"] = "password must be between 6-100 characters long <br>";
                unset($_POST["password"]);
            }
                
            else {
                echo 3;
                echo "<br>";

                login($username, $password);

                echo 3.5;
                echo "<br>";
                
                unset($_POST["username"]);
                unset($_POST["password"]);
            }
             
            // header("Location: index.php");
        }
    }
    
    else {
        echo 4;
        echo "<br>";

        $_SESSION["error"] = "unauthorized form submission";
        unset($_SESSION["error"]);
    }

 
    header("Location: index.php");
}

?>