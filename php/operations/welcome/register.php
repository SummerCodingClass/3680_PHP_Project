<?php
session_start();
require_once("../setup/config.php");
require_once("../../operations/preferences/AddPref.php");

function register($username, $password) {
    $db = get_connection(); 

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $statement = $db->prepare("Call RegisterUser (?, ?)");

    $statement->bind_param('ss', $username, $hashed);
    
    if (!$statement->execute()) {
        die(mysqli_error($db) . "<br>");
    }

    $result = $statement->get_result();

    while ($row = $result->fetch_assoc()) {
        // var_dump($row);

        $output = $row["Result"];
        $error = $row["Error"];
    
        if ($output == "true") {
            $_SESSION["result"] = "Registration Successful.";
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


function createDefaultList($username) {

    //for default list:
    $listName = "default";
    $categoryName = "default";
    
    $db = get_connection(); 

    $statement = $db->prepare("Call AddList (?, ?, ?)");
    $statement->bind_param('sss', $username, $listName, $categoryName);

    if (!$statement->execute()) {
        die(mysqli_error($db) . "<br>");
    }

    $result = $statement->get_result();

    while ($row = $result->fetch_assoc()) {
        var_dump($row);

        // can't use result!!! have to use output because result was already used above
        // as a database handle. i fell for this twice lol.
        $output = $row["Result"];
        $error = $row["Error"];
        
    
        if ($output == "true") {
            // $_SESSION["result_list"] = "success";
            $_SESSION["defaultListCreated"] = true;
            
            $lid = $row["lid"];
        } 
        else {
            // $_SESSION["error_list"] = $error;
            $_SESSION["defaultListCreated"] = false;
        }
    }
}





if (isset($_POST["register"])) {

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
            header("Location: new_user.php");
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

                register($username, $password);

                echo 'a <br>';

                createDefaultList($username);
                createDefaultPref($username);

                echo 'b';
                

                // if ($defaultListCreated) {
                //     echo 4;

                //     $_SESSION["defaultListCreated"] = true;
                //     // $_SESSION["defaultListCreated"] = "successfully created default list";
                // }
                // else {
                //     echo 5;
                //     $_SESSION["defaultListCreated"] = false;
                //     // $_SESSION["defaultListCreated"] = "failed to create default list. please contact the site administrator for help";
                // }

                echo 3.5;
                echo "<br>";
                
                unset($_POST["username"]);
                unset($_POST["password"]);
            }
             
            // header("Location: new_user.php");
        }
    }
    
    else {
        echo 6;
        echo "<br>";

        $_SESSION["error"] = "unauthorized form submission";
        unset($_SESSION["error"]);
    }
 
    echo 7;
    header("Location: new_user.php");
}

?>