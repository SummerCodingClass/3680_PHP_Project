<?php

// lightpurple: #FAD7F7

function setUpDisplayForDone($allItems) {

    $address = $_SERVER['PHP_SELF'];
    $username = $_SESSION["username"];
    $listName = $_SESSION["currentList"];

    $listName = htmlspecialchars($listName, ENT_QUOTES, 'UTF-8');

    echo "<table border='1' id='doneTable'>";
    echo "<tr>";

    echo "<th> </th>";
    echo "<th> Row# </th>";
    echo "<th class='nameColumn'> Item Name </th>";
    echo "<th class='contentColumn'> Item Content </th>";
    // echo "<th> Add Time </th>";
    // echo "<th> Goal Time </th>";
    // echo "<th> Completed Time </th>";

    echo "<th class='timeColumn'>";
    
    echo "Add Time";

    echo 
    "
    <form action=\"$address\" method=\"POST\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByAddDoneAsc\" value=\"⬆\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByAddDoneDesc\" value=\"⬇\">
    </form>
    ";
    
    
    echo "</th>";
    
    echo "<th class='timeColumn'>";
    
    echo "Goal Time";

    echo 
    "
    <form action=\"$address\" method=\"POST\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByGoalDoneAsc\" value=\"⬆\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByGoalDoneDesc\" value=\"⬇\">
    </form>
    ";
    
    
    echo "</th>";

    echo "<th class='timeColumn'>";
    
    echo "Completed Time";

    echo 
    "
    <form action=\"$address\" method=\"POST\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByCompletedDoneAsc\" value=\"⬆\">
    <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByCompletedDoneDesc\" value=\"⬇\">
    </form>
    ";
    
    
    echo "</th>";
    
    echo "<th> </th>";

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
            echo "<tr class='highlighted'> <div id=done$counter> </div>";
        }
        else {
            echo "<tr> <div id=done$counter> </div>";
        }

        
        echo "<td>";

        echo 
        "
        <form action=\"$address\" method=\"POST\">
        <input type=\"hidden\" name=\"item_ListName\" value='$listName'>
        <input type=\"hidden\" name=\"item_ItemName\" value='$itemName'>
        <input type=\"hidden\" name=\"item_RowID\" value='done$counter'>
        <input style='background-color: lightpink;' type=\"submit\" name=\"deleteItemRequest\" value=\"Delete\">
        <input style='background-color: lightyellow;' type=\"submit\" name=\"highlightItemRequest\" value=\"Highlight\">
        <input style='width: 150; background-color: lightblue;' type=\"submit\" name=\"markNotDoneRequest\" value=\"Mark As Not Done\">
        </form>
        
        ";

        echo "</td>";
        
        echo "<td>" . $counter . "</td>";
        echo "<td class='nameColumn'> <div class='scrollable'>" . $itemName . "</div> </td>";
        echo "<td class='contentColumn'> <div class='scrollable'>" . $itemContent . " </div> </td>";
        echo "<td class='timeColumn'>" . $addTime . "</td>";
        echo "<td class='timeColumn'>" . $goalTime . "</td>";
        echo "<td class='timeColumn'>" . $completedTime . "</td>";



        $breakDown = explode(" ", $addTime);
        $explodedAddDate = $breakDown["0"];
        $explodedAddTime = $breakDown["1"];

        $breakDown = explode(" ", $goalTime);
        $explodedGoalDate = $breakDown["0"];
        $explodedGoalTime = $breakDown["1"];
        $highlightTrack = $item["isHighlighted"];


        echo "<td>";

        echo 
        "
        <form action=\"$address\" method=\"POST\">
        <input type=\"hidden\" name=\"update_ListName\" value='$listName'>
        <input type=\"hidden\" name=\"update_ItemName\" value='$itemName'>
        <input type=\"hidden\" name=\"update_RowID\" value='done$counter'>
        <input type=\"hidden\" name=\"update_ItemContent\" value='$itemContent'>
        <input type=\"hidden\" name=\"update_ItemAddDate\" value='$explodedAddDate'>
        <input type=\"hidden\" name=\"update_ItemAddTime\" value='$explodedAddTime'>
        <input type=\"hidden\" name=\"update_ItemGoalDate\" value='$explodedGoalDate'>
        <input type=\"hidden\" name=\"update_ItemGoalTime\" value='$explodedGoalTime'>
        <input type=\"hidden\" name=\"update_ItemHighlight\" value='$highlightTrack'>

        <input style='width: 50; background-color: #DAD8EF;' type=\"submit\" name=\"editItemRequest\" value=\"Edit\">
        </form>
        
        ";

        echo "</td>";
        
        
        
        
        echo "</tr>";
    }

    echo "</table>";
}

?>