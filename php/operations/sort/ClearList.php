<?php



function clearList($username, $listName, $isDone) {
    $db = get_connection();

    $statement = $db->prepare("CALL `DeleteAllListItems`(?,?,?)");

    $statement->bind_param('sss', $username, $listName, $isDone);

    if ($statement->execute()) {
    
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
        
            $output = $row["Result"];
            $error = $row["Error"];

            if($output == "true") {
                echo "<span style='color: green' id=\"tempMsg\"> successfully deleted the list </span> <br>";
            }

            else {
                echo "<span style='color: red' id=\"tempMsg\"> Error: $error </span> <br>";
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