<?php

// lightpurple: #FAD7F7

function setUpDisplayForDone($allItems) {

    $address = $_SERVER['PHP_SELF'];
    $username = $_SESSION["username"];
    $listName = $_SESSION["currentList"];

    $listName = htmlspecialchars($listName, ENT_QUOTES, 'UTF-8');

    echo "<table border='1'>";
    echo "<tr>";

    echo "<th> </th>";
    echo "<th> Row# </th>";
    echo "<th class='nameColumn'> Item Name </th>";
    echo "<th class='contentColumn'> Item Content </th>";
    // echo "<th> Add Time </th>";
    // echo "<th> Goal Time </th>";
    // echo "<th> Completed Time </th>";

    echo "<th>";
    
    echo "Add Time";

    echo 
    "
    <form action=\"$address\" method=\"POST\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByAddDoneAsc\" value=\"⬆\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByAddDoneDesc\" value=\"⬇\">
    </form>
    ";
    
    
    echo "</th>";
    
    echo "<th>";
    
    echo "Goal Time";

    echo 
    "
    <form action=\"$address\" method=\"POST\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByGoalDoneAsc\" value=\"⬆\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByGoalDoneDesc\" value=\"⬇\">
    </form>
    ";
    
    
    echo "</th>";

    echo "<th>";
    
    echo "Completed Time";

    echo 
    "
    <form action=\"$address\" method=\"POST\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByCompletedDoneAsc\" value=\"⬆\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByCompletedDoneDesc\" value=\"⬇\">
    </form>
    ";
    
    
    echo "</th>";
    
    echo "</tr>";

    $counter = 0;
    
    foreach ($allItems as $item) {

        $counter++;

        $itemName = htmlspecialchars($item["itemName"], ENT_QUOTES, 'UTF-8');
        // echo $itemName;
        $itemContent = htmlspecialchars($item["itemContent"], ENT_QUOTES, 'UTF-8');
        $addTime = htmlspecialchars($item["addTime"]);
        $goalTime = htmlspecialchars($item["goalTime"]);
        $completedTime = htmlspecialchars($item["completedTime"]);
        


        if ($item["isHighlighted"] == "1") {
            $highlight = true;
        } 
        else {
            $highlight = false;
        }
        
        if ($highlight == true) {
            echo "<tr class='highlighted' id=$counter>";
        }
        else {
            echo "<tr>";
        }
        
        echo "<td>";

        echo 
        "
        <form action=\"$address\" method=\"POST\">
        <input type=\"hidden\" name=\"item_ListName\" value='$listName'>
        <input type=\"hidden\" name=\"item_ItemName\" value='$itemName'>
        <input style='background-color: lightpink;' type=\"submit\" name=\"deleteItemRequest\" value=\"Delete\">
        <input style='background-color: lightyellow;' type=\"submit\" name=\"highlightItemRequest\" value=\"Highlight\">
        <input style='width: 150; background-color: lightblue;' type=\"submit\" name=\"markNotDoneRequest\" value=\"Mark As Not Done\">
        </form>
        
        ";
        
        echo "</td>";
        
        echo "<td>" . $counter . "</td>";
        echo "<td class='nameColumn'> <div class='scrollable'>" . $itemName . "</div> </td>";
        echo "<td class='contentColumn'> <div class='scrollable'>" . $itemContent . " </div> </td>";
        echo "<td>" . $addTime . "</td>";
        echo "<td>" . $goalTime . "</td>";
        echo "<td>" . $completedTime . "</td>";
        
        
        
        
        echo "</tr>";
    }

    echo "</table>";
}

?>