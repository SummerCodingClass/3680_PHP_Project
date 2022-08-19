<?php

    function fetchASetOfPreferences($username, $prefName) {
        // $username = $_SESSION["username"];
        // $prefName = $_SESSION["currentPref"];
        // $prefName = "default";
        
        // echo 'a';

        $db = get_connection();

        // echo 'b';
        
        // var_dump($db);
        // echo $username;
        // echo $prefName;

        $statement = $db->prepare("CALL FetchAPreference(?, ?)");

        // var_dump($statement);
        // echo 'c';
        
        $statement->bind_param('ss', $username, $prefName);

        // echo 'd';

        $allItems = [];

        // echo 'e';

        if ($statement->execute()) {
            // echo 'f';

            $result = $statement->get_result();

            // echo 'g';

            while ($row = $result->fetch_assoc()) {

                // echo 'h';
                // var_dump($row);

                $output = $row["Result"];
                // echo $output;
                // echo 'i';
                
                if ($output == "true") {

                    // echo 'j';

                    // var_dump($row);
                    
                    // $bgColor = $row["bgcolor"];
                    // $fontColor = $row["fontcolor"];
                    // $borderColor = $row["bordercolor"];
                    // $highlightColor = $row["highlightcolor"];
                    // $fontSize = $row["fontsize"];

                    // $temp = array(
                    //     "bgColor" => $bgColor,
                    //     "fontColor" => $fontColor,
                    //     "borderColor" => $borderColor,
                    //     "highlightColor" => $highlightColor,
                    //     "fontSize" => $fontSize
                    // );

                    // $allItems []= $temp;       
                    
                                
                    $bgColor = $row["bgcolor"];
                    $fontColor = $row["fontcolor"];
                    $borderColor = $row["bordercolor"];
                    $highlightColor = $row["highlightcolor"];
                    $fontSize = $row["fontsize"];

                    $allItems = array(
                        "bgColor" => $bgColor,
                        "fontColor" => $fontColor,
                        "borderColor" => $borderColor,
                        "highlightColor" => $highlightColor,
                        "fontSize" => $fontSize
                    );

                    // $allItems []= $temp;       
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