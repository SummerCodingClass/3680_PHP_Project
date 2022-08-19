<?php
// lightpurple: #FAD7F7

    function setUpDisplayForToDo($allItems) {

        $address = $_SERVER['PHP_SELF'];
        $username = $_SESSION["username"];
        $listName = $_SESSION["currentList"];

        // https://stackoverflow.com/questions/4722727/htmlspecialchars-ent-quotes-not-working
        $listName = htmlspecialchars($listName, ENT_QUOTES, 'UTF-8');

        echo "<table border='1' id='toDoTable'> ";
        echo "<tr>";

        echo "<th> </th>";
        echo "<th> Row# </th>";
        echo "<th class='nameColumn'> Item Name </th>";
        echo "<th class='contentColumn'> Item Content </th>";
        echo "<th class='timeColumn'>";

        echo "Add Time";

        echo 
        "
        <form action=\"$address\" method=\"POST\">
        <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByAddToDoAsc\" value=\"⬆\">
        <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByAddToDoDesc\" value=\"⬇\">
        </form>
        ";


        echo "</th>";

        echo "<th class='timeColumn'>";

        echo "Goal Time";

        echo 
        "
        <form action=\"$address\" method=\"POST\">
        <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByGoalToDoAsc\" value=\"⬆\">
        <input class='sort' style='background-color: #FAD7F7;' type=\"submit\" name=\"sortByGoalToDoDesc\" value=\"⬇\">
        </form>
        ";


        echo "</th>";

        echo "<th> </th>";

        echo "</tr>";

        $counter = 0;

        foreach ($allItems as $item) {

            $counter++;

            // $itemName = htmlspecialchars($item["itemName"]);
            $itemName = htmlspecialchars($item["itemName"], ENT_QUOTES, 'UTF-8');
            $itemContent = htmlspecialchars($item["itemContent"], ENT_QUOTES, 'UTF-8');
            $addTime = htmlspecialchars($item["addTime"]);
            $goalTime = htmlspecialchars($item["goalTime"]);
            


            if ($item["isHighlighted"] == "1") {
                $highlight = true;
            } 
            else {
                $highlight = false;
            }
            
            if ($highlight == true) {
                echo "<tr class='highlighted'> <div id=todo$counter> </div>";
                // echo "<tr class='highlighted'> <div id=todo$counter> </div>";
            }
            else {
                echo "<tr> <div id=todo$counter> </div>";
                // echo "<tr> <div id=todo$counter> </div>";
            }
            
            // lightgreen = #C1FFDC

            // $highlightTrack = $item["isHighlighted"];


            
            echo "<td>";
            
            echo 
            "
            <form action=\"$address\" method=\"POST\">
            <input type=\"hidden\" name=\"item_ListName\" value='$listName'>
            <input type=\"hidden\" name=\"item_ItemName\" value='$itemName'>
            <input type=\"hidden\" name=\"item_RowID\" value='todo$counter'>
            <input style='background-color: lightpink;' type=\"submit\" name=\"deleteItemRequest\" value=\"Delete\">
            <input style='background-color: lightyellow;' type=\"submit\" name=\"highlightItemRequest\" value=\"Highlight\">
            <input style='width: 150px; background-color: #C1FFDC;' type=\"submit\" name=\"markDoneRequest\" value=\"Mark As Done\">
            </form>
            
            ";
            
            echo "</td>";


            echo "<td>" . $counter . "</td>";
            echo "<td class='nameColumn'> <div class='scrollable'>" . $itemName . "</div> </td>";
            echo "<td class='contentColumn'> <div class='scrollable'>" . $itemContent . "</div> </td>";
            echo "<td class='timeColumn'>" . $addTime . "</td>";
            echo "<td class='timeColumn'>" . $goalTime . "</td>";
            

            
   

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
            <input type=\"hidden\" name=\"update_RowID\" value='todo$counter'>
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