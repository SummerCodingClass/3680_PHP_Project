<?php

    function fetchAllCategories() {

        $username = $_SESSION["username"];

        $db = get_connection();
        $statement = $db->prepare("CALL FetchAllCategories(?)");
        $statement->bind_param('s', $username);

        $allCategories = [];

        if ($statement->execute()) {
            $result = $statement->get_result();
            while ($row = $result->fetch_assoc()) {
                $output = $row["Result"];
                
                if ($output == "true") {

                    $categoryName = $row["category"];
                                        
                    $allCategories []= $categoryName;

                    
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
    
        return $allCategories;
    }        
    


    function fetchListsByCategory($categoryName) {
        if ($categoryName != "default" && $categoryName!= "one" && $categoryName != "two") {
            $categoryName = "default";
        }

        $username = $_SESSION["username"];


        $db = get_connection();
        $statement = $db->prepare("CALL FetchListsByCategory(?, ?)");
        $statement->bind_param('ss', $username, $categoryName);

        $allLists = [];

        if ($statement->execute()) {
            $result = $statement->get_result();
            while ($row = $result->fetch_assoc()) {
                $output = $row["Result"];
                
                if ($output == "true") {

                    $listName = $row["lname"];
                    
                    $allLists []= $listName;

                    
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
    
        return $allLists;
    }



    function fetchAllLists() {
        
        $username = $_SESSION["username"];

        $db = get_connection();
        $statement = $db->prepare("CALL FetchAllLists(?)");
        $statement->bind_param('s', $username);

        $allLists = [];

        if ($statement->execute()) {

            $result = $statement->get_result();

            while ($row = $result->fetch_assoc()) {
                

                $output = $row["Result"];
                
                if ($output == "true") {
                    

                    $listName = $row["lname"];
                    $categoryName = $row["category"];
                    
                    $temp = array(
                        "listName" => $listName, 
                        "categoryName" => $categoryName
                    );
                    
                    $allLists []= $temp;
                    // echo $listName;

                    // $allLists []= $listName;

                    // echo $listName;
                    // var_dump($allLists);
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
    
        // var_dump($allLists);
        return $allLists;
    }


?>