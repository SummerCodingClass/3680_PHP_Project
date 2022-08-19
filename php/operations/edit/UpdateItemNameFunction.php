<?php


    function updateItemName($oldItemName, $newItemName) {
        $username = $_SESSION["username"];
        $listName = $_SESSION["currentList"];

        if ($oldItemName == $newItemName) {
            echo "<span style='color: blue' id=\"tempMsg\"> No name change requested. <br></span>";
            return;
        }

        else if (strlen($newItemName) < 1 || strlen($newItemName) > 100) {
            echo "<span style='color: red' id=\"tempMsg\"> Invalid name length. Name has to be between 1 - 100 characters.  <br> </span>";
            return;
        }

        else {

            $db = get_connection();

            $statement = $db->prepare("CALL `UpdateItemName`(?,?,?,?)");
        
            $statement->bind_param('ssss', $username, $listName, $oldItemName, $newItemName);
        
            if ($statement->execute()) {
            
                $result = $statement->get_result();
        
                while ($row = $result->fetch_assoc()) {
                
                    $output = $row["Result"];
                    $error = $row["Error"];
        
                    if($output == "true") {
                        echo "<span style='color: green' id=\"tempMsg\"> Successfully updated item named: '$oldItemName'. It is now called: '$newItemName'. <br></span>";
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
    }



?>