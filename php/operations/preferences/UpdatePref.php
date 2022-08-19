<?php



function updatePref($username, $prefName, $bgColor, $fontColor, $borderColor, $highlightColor, $fontSize) {
    
    // $pname = "default";
    // $bgColor = "#FFFFFF";
    // $fontColor = "#000000";
    // $borderColor = "#000000";
    // $highlightColor = "#FFFF00";
    // $fontSize = "14";

    $pname = $prefName;

    // echo $username;
    // echo $pname;
    // echo $bgColor;


    $db = get_connection();
    
    $statement = $db->prepare("CALL `UpdatePreference`(?, ?, ?, ?, ?, ?, ?)");
    $statement->bind_param('sssssss', $username, $pname, $bgColor, $fontColor, $borderColor, $fontSize, $highlightColor);

    if ($statement->execute()) {
        $result = $statement->get_result();
        while ($row = $result->fetch_assoc()) {
        
            $output = $row["Result"];
            $error = $row["Error"];

            // echo $output;

            if($output == "true") {
                // $_SESSION["defaultPrefCreated"] = true;
                $_SESSION["result_prefupdate"] = "successfully updated the preferences.";
                $_SESSION["currentPref"] = $pname;
            }

            else {
                $_SESSION["error_prefupdate"] = $error;
                $_SESSION["currentPref"] = "default";
            }
            // else {
            //     $_SESSION["defaultPrefCreated"] = false;
            // }
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