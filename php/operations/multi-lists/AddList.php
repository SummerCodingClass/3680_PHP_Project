<?php



function addList($username, $listName, $categoryName) {
    
    // if (strlen($listName) < 1 || strlen($listName) > 100 || strlen($categoryName) < 0 || strlen($categoryName) > 1000 ) {
    if (strlen($listName) < 1 || strlen($listName) > 100) {
        $nameLength = strlen($listName);
        $_SESSION["error_list"] = "List name must be between 1-100 characters. <br> Your attempt: $nameLength characters. <br><br>";
        return;
    }

        // if (strlen($categoryName) < 0 || strlen($categoryName) > 100) {
        //     // $categoryLength = strlen($categoryName);
        //     // $_SESSION["error_list"] = $_SESSION["error_list"] . "Category name must be between 1-100 characters. <br> Your attempt: $categoryLength characters. <br><br>";        
        //     $_SESSION["error_list"] = $_SESSION["error_list"] . "Please do not modify the category name. <br><br>";        
        // }


        // if ($categoryName != "default" && $categoryName != "one" && $categoryName != "two") {
        //     // $categoryLength = strlen($categoryName);
        //     // $_SESSION["error_list"] = $_SESSION["error_list"] . "Category name must be between 1-100 characters. <br> Your attempt: $categoryLength characters. <br><br>";        
        //     $_SESSION["error_list"] = $_SESSION["error_list"] . "Please do not modify the category name. <br><br>";        
        // }


        // return;
    // }

    if ($categoryName != "default" && $categoryName != "one" && $categoryName != "two") {
        // $categoryLength = strlen($categoryName);
        // $_SESSION["error_list"] = $_SESSION["error_list"] . "Category name must be between 1-100 characters. <br> Your attempt: $categoryLength characters. <br><br>";        
        $_SESSION["error_list"] = $_SESSION["error_list"] . "Please do not modify the category name. <br><br>";  
        return;      
    }

    
    $db = get_connection();

    $statement = $db->prepare("CALL `AddList`(?,?,?)");

    $statement->bind_param('sss', $username, $listName, $categoryName);

    if ($statement->execute()) {
    
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
        
            $output = $row["Result"];
            $error = $row["Error"];

            if($output == "true") {
                $_SESSION["result_list"] = "successfully added a new list named: '$listName' under category: '$categoryName'. Current list has now been switched to the new list.";
                $_SESSION["currentList"] = $listName;
                
            }

            else {
                $_SESSION["error_list"] = $error;
            }
            return;
        }
    }
    else {
        echo "Error getting result: " . mysqli_error($db);
        die();

        return;
    }
    return;
}

?>