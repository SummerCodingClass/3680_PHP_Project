<?php

    function fetchItemsByGoalTime($isDone, $order) {
        $username = $_SESSION["username"];
        $listName = $_SESSION["currentList"];
        
        $db = get_connection();
        $statement = $db->prepare("CALL DisplayItemsByGoalTime(?, ?, ?, ?)");
        $statement->bind_param('ssss', $username, $listName, $isDone, $order);

        $allItems = [];

        if ($statement->execute()) {
            $result = $statement->get_result();
            while ($row = $result->fetch_assoc()) {
                $output = $row["Result"];
                
                if ($output == "true") {

                    $itemName = $row["iname"];
                    $itemContent = $row["icontent"];
                    $addTime = $row["add"];
                    $goalTime = $row["goal"];
                    $completedTime = $row["completed"];
                    $isHighlighted = $row["ishighlighted"];

                    $temp = array(
                        "itemName" => $itemName, 
                        "itemContent" => $itemContent,
                        "addTime" => $addTime,
                        "goalTime" => $goalTime,
                        "completedTime" => $completedTime,
                        "isHighlighted" => $isHighlighted
                    );

                    $allItems []= $temp;                    
                }
                else {
                    $_SESSION["error"] = $row["Error"];
                }

            }        
        }
        else {
            echo "Error getting result: " . mysqli_error($db);
            die();
        }
    
        return $allItems;
    }

?>