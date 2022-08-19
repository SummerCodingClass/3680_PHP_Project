<?php



function deleteList($username, $listName) {
    $db = get_connection();

    $statement = $db->prepare("CALL `DeleteList`(?,?)");

    $statement->bind_param('ss', $username, $listName);

    if ($statement->execute()) {
    
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
        
            $output = $row["Result"];
            $error = $row["Error"];

            if($output == "true") {
                echo "<span style='color: green' id=\"tempMsg\"> successfully deleted list named: '$listName' </span>";
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