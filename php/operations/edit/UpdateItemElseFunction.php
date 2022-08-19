<?php


    function updateItemElse($itemName, $content, $add, $goal, $isHighlighted) {
        $username = $_SESSION["username"];
        $listName = $_SESSION["currentList"];


        if (strlen($content) < 1 || strlen($content) > 1000) {
            $length = strlen($content);
            echo "<span style='color: red' id=\"tempMsg\"> Invalid content length. Name has to be between 1 - 1000 characters. You are currently at $length characters. <br> </span>";
            return;
        }

        if ($isHighlighted != "0" && $isHighlighted != "1") {
            // $isHighlighted = "0";
            // echo $isHighlighted;
            echo "<span style='color: red' id=\"tempMsg\"> Invalid highlight value. Please do not modify the form <br> </span>";
            return;
        }

        // checking if string received is a date:
            // https://stackoverflow.com/questions/11029769/function-to-check-if-a-string-is-a-date

        // check if valid date: (not used)
            // https://www.geeksforgeeks.org/php-checkdate-function/

        if (DateTime::createFromFormat('Y-m-d H:i:s', $add) !== false) {
            // it's a date
        }
        else {
            // echo $add;
            echo "<span style='color: red' id=\"tempMsg\"> Invalid add date. Please do not modify the form <br> </span>";
            return;
            // $add = "null";
        }

        if (DateTime::createFromFormat('Y-m-d H:i:s', $goal) !== false) {
            // it's a date
        }
        else {
            // $goal = "null";
            echo "<span style='color: red' id=\"tempMsg\"> Invalid goal date. Please do not modify the form <br> </span>";
            return;
        }
        

        

        $db = get_connection();

        $statement = $db->prepare("CALL `UpdateItem`(?,?,?,?,?,?,?)");
    
        $statement->bind_param('sssssss', $username, $listName, $itemName, $content, $add, $goal, $isHighlighted);
    
        if ($statement->execute()) {
        
            $result = $statement->get_result();
    
            while ($row = $result->fetch_assoc()) {
            
                $output = $row["Result"];
                $error = $row["Error"];
    
                if($output == "true") {
                    echo "<span style='color: green' id=\"tempMsg\"> Successfully updated item named: '$itemName'. <br></span>";
                }
    
                else {
                    echo "<span style='color: red' id=\"tempMsg\"> $error <br> </span>";
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