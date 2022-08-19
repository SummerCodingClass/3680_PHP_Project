<?php

function updateItemHighlight($username, $listName, $itemName) {
    $db = get_connection();

    $statement = $db->prepare("CALL `UpdateHighlight`(?, ?, ?)");

    $statement->bind_param('sss', $username, $listName, $itemName);

    if ($statement->execute()) {
    
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
        
            $output = $row["Result"];
            $error = $row["Error"];

            if($output == "true") {
                echo "<span style='color: green' id=\"tempMsg\"> successfully updated item named: '$itemName' </span>";
            }

            else {
                echo "span style='color: red' id=\"tempMsg\"> $error </span>";
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