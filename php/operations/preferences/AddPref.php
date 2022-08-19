<?php



function createDefaultPref($username) {
    
    $pname = "default";
    $bgColor = "#FFFFFF";
    $fontColor = "#000000";
    $borderColor = "#000000";
    $highlightColor = "#FFFF00";
    $fontSize = "16";



    $db = get_connection();
    $statement = $db->prepare("CALL `AddPreference`(?, ?, ?, ?, ?, ?, ?)");
    $statement->bind_param('sssssss', $username, $pname, $bgColor, $fontColor, $borderColor, $fontSize, $highlightColor);

    if ($statement->execute()) {
        $result = $statement->get_result();
        while ($row = $result->fetch_assoc()) {
        
            $output = $row["Result"];
            $error = $row["Error"];

            if($output == "true") {
                $_SESSION["defaultPrefCreated"] = true;
                // $_SESSION["result_prefadd"] = "successfully added a default set of preferences for user: '$username'";
                // $_SESSION["currentPref"] = $pname;
            }

            // else {
            //     $_SESSION["error_prefadd"] = $error;
            // }
            else {
                $_SESSION["defaultPrefCreated"] = false;
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