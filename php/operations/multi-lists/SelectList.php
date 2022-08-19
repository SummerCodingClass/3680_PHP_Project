<?php

    function selectList($username, $desiredList) {
        $boolFound = false;
        $items = fetchAllLists();

        foreach ($items as $item) {
            $listName = $item["listName"];

            if (strcasecmp($desiredList, $listName) == 0) {
                $boolFound = true; 
                $_SESSION["currentList"] = $desiredList;
                echo "<span style='color: green' id=\"tempMsg\"> successfully switched to list named: '$desiredList' </span>";
                return;
            }
        }
        // if not found:
        if ($boolFound == false) {
            $error = "List name not found.";
            echo "<span style='color: red' id=\"tempMsg\"> $error </span>";
            return;
        }

    }


?>