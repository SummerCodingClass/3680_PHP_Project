<?php


function fetchCurrentTime() {

    $date = date("Y-m-d");
    $time = date("H:i");

    $combined = $date . " ". $time;

    return $combined;
}

function updateItemDone($username, $listName, $itemName, $isDone) {
    $db = get_connection();

    $completedTime = fetchCurrentTime(); // does not matter if isDone is 0. stored procedure will ignore it.

    $statement = $db->prepare("CALL `UpdateDone`(?, ?, ?, ?, ?)");

    $statement->bind_param('sssss', $username, $listName, $itemName, $isDone, $completedTime);

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