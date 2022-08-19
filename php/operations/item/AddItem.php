<?php
session_start();

$auth = $_SESSION["authorized"];
if (!$auth) {
    header("Location: ../welcome/index.php");
}

else {
    require_once("../setup/config.php");



    function combiningDateTime($date, $time) {
        $combined = $date . " " . $time;
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

    function getListName() {
        if(isset($_SESSION["currentList"])) {
            $listName = $_SESSION["currentList"];
            $listName = htmlspecialchars($listName);
        }
        else {
            $listName = "default";
        }
        return $listName;
    }

    // function getHighlightColor() {
    //     if(isset($_SESSION["highlightColor"])) {
    //         $highlightColor = $_SESSION["highlightColor"];
    //         $highlightColor = htmlspecialchars($highlightColor);
    //     }
    //     else {
    //         $highlightColor = "#FFFF00";
    //     }
    //     return $highlightColor;
    // }



    function addItem($itemName, $itemContent, $addDate, $addTime, $goalDate, $goalTime, $username, $listName, $isHighlighted) {
        
        if (strlen($itemName) < 1 || strlen($itemName) > 100 || strlen($itemContent) < 0 || strlen($itemContent) > 1000 ) {
            if (strlen($itemName) < 1 || strlen($itemName) > 100) {
            $nameLength = strlen($itemName);
            $_SESSION["error_item"] = "Item NAME must be between 1-100 characters. <br> Your attempt: $nameLength characters. <br><br>";
            }

            if (strlen($itemContent) < 0 || strlen($itemContent) > 1000) {
                $contentLength = strlen($itemContent);
                $_SESSION["error_item"] = $_SESSION["error_item"] . "Item CONTENT must be between 1-100 characters. <br> Your attempt: $contentLength characters. <br><br>";        
            }

            return;
        }

        // if (strlen($itemName) < 1 || strlen($itemName) > 100) {
        //     $_SESSION["error_item"] = "item name must be between 1-100 characters <br>";
        //     return;
        // }


        echo "before: $goalDate, $goalTime <br>"; 
        
        // set it up
        $username = getUsername();
        $listName = getListName();
        // $highlightColor = getHighlightColor();
        $isHighlighted = "0";
        $addCombined = combiningDateTime($addDate, $addTime);
        $goalCombined = combiningDateTime($goalDate, $goalTime);

        echo "add: $addCombined <br>";
        echo "goal: $goalCombined <br>";


        // checking if string received is a date:
        // https://stackoverflow.com/questions/11029769/function-to-check-if-a-string-is-a-date

        if (DateTime::createFromFormat('Y-m-d H:i', $addCombined) == false) {
            $_SESSION["error_item"] = $_SESSION["error_item"] . "Invalid add date/time. Please do not modify the form." . "<br><br>";        
            return;
        }

        if (DateTime::createFromFormat('Y-m-d H:i', $goalCombined) == false) {
            $_SESSION["error_item"] = $_SESSION["error_item"] . "Invalid goal date/time. Please do not modify the form." . "<br><br>";        
            return;
        }

        if ($isHighlighted != "0" && $isHighlighted != "1") {
            $_SESSION["error_item"] = $_SESSION["error_item"] . "Invalid highlight value. Please do not modify the form." . "<br><br>";        
            return;
        }

        // call the proceudre

        $db = get_connection(); 

        $statement = $db->prepare("Call AddItem (?, ?, ?, ?, ?, ?, ?)");
        $statement->bind_param('sssssss', $username, $listName, $itemName, $itemContent, $addCombined, $goalCombined, $isHighlighted);
        if (!$statement->execute()) {
            die(mysqli_error($db) . "<br>");
        }

        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            var_dump($row);

            $found = $row["Result"];
            $error = $row["Error"];
        
            if ($found == "true") {
                $_SESSION["result_item"] = "Added Successfully";              
            }
            else {
                $_SESSION["error_item"] = $error;
            }
        }
    }


    if (isset($_POST["addItemRequest"])) {
        $itemName = $_POST["itemName"];
        $itemContent = $_POST["itemContent"];
        $itemAddDate = $_POST["itemAddDate"];
        $itemAddTime = $_POST["itemAddTime"];
        $itemGoalDate = $_POST["itemGoalDate"];
        $itemGoalTime = $_POST["itemGoalTime"];

        $_SESSION["itemName"] = $itemName;
        $_SESSION["itemContent"] = $itemContent;
        
        $itemName = htmlspecialchars($itemName);
        $itemContent = htmlspecialchars($itemContent);
        $itemAddDate = htmlspecialchars($itemAddDate);
        $itemAddTime = htmlspecialchars($itemAddTime);
        $itemGoalDate = htmlspecialchars($itemGoalDate);
        $itemGoalTime = htmlspecialchars($itemGoalTime);

        // echo $itemGoalDate . " " . $itemGoalTime . "<br>";
        
        echo "<a href='../../views/home.php'> Return Home </a><br><br>";
        addItem($itemName, $itemContent, $itemAddDate, $itemAddTime, $itemGoalDate, $itemGoalTime, "", "", "");

        // gonna have to display errors

    }

    // echo "<a href='../../views/home.php> return home </a>";

    header("Location: ../../views/home.php");

}

?>
