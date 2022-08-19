<?php
    // $username = $_SESSION["username"] = $username;
    // $currentList = $_SESSION["currentList"] = "default";
    // $sortBy = $_SESSION["currentSortBy"] = "add";
    // $order = $_SESSION["currentOrder"] = "ascend";


    function fetchItemsByAddTime($isDone, $order) {
        $username = $_SESSION["username"];
        $listName = $_SESSION["currentList"];
        // $sortBy = $_SESSION["currentSortBy"];
        // $order = $_SESSION["currentOrder"];
        
        $db = get_connection();
        $statement = $db->prepare("CALL DisplayItemsByAddTime(?, ?, ?, ?)");
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

                    // echo "temp: ";
                    // var_dump($temp);
                    // echo "<br>";

                    
                    $allItems []= $temp;

                    // echo "allItems: ";
                    // var_dump($allItems);
                    // echo "<br>";
                    
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