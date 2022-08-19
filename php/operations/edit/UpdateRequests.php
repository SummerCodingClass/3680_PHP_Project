<?php
        // moved to home
        // require_once("../operations/edit/UpdateItemFunctions.php");

        if (isset($_POST["editItemNameSubmit"])) {
            $oldName = $_POST["itemNameUpdate_old"];
            $newName = $_POST["itemNameUpdate_new"];
            $content = $_POST["itemContentUpdate"];
            
            $addDate = $_POST["itemAddDateUpdate"];
            $addTime = $_POST["itemAddTimeUpdate"];

            $addCombined = $addDate . " ". $addTime;

            $goalDate = $_POST["itemGoalDateUpdate"];
            $goalTime = $_POST["itemGoalTimeUpdate"];

            $goalCombined = $goalDate . " ". $goalTime;

            $isHighlighted = $_POST["update_ItemHighlight"];


            // $oldName
            // $newName
            // $content
            // $addCombined
            // $goalCombined
            // $isHighlighted            
            // $listName = htmlspecialchars($listName, ENT_QUOTES, 'UTF-8');

            // $oldName = htmlspecialchars($oldName, ENT_QUOTES, 'UTF-8');
            // $newName = htmlspecialchars($newName, ENT_QUOTES, 'UTF-8');
            // $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
            // $addCombined = htmlspecialchars($addCombined, ENT_QUOTES, 'UTF-8');
            // $goalCombined = htmlspecialchars($goalCombined, ENT_QUOTES, 'UTF-8');
            // $isHighlighted = htmlspecialchars($isHighlighted, ENT_QUOTES, 'UTF-8');


            $oldName = htmlspecialchars($oldName);
            $newName = htmlspecialchars($newName);
            $content = htmlspecialchars($content);
            $addCombined = htmlspecialchars($addCombined);
            $goalCombined = htmlspecialchars($goalCombined);
            $isHighlighted = htmlspecialchars($isHighlighted);

            
            updateItemName($oldName, $newName);
        }



        
        if (isset($_POST["editItemSubmit"])) {
            $itemName = $_POST["itemNameUpdate_old"];
            // $newName = $_POST["itemNameUpdate_new"];
            $content = $_POST["itemContentUpdate"];
            
            $addDate = $_POST["itemAddDateUpdate"];
            $addTime = $_POST["itemAddTimeUpdate"];

            $addCombined = $addDate . " ". $addTime;

            $goalDate = $_POST["itemGoalDateUpdate"];
            $goalTime = $_POST["itemGoalTimeUpdate"];

            $goalCombined = $goalDate . " ". $goalTime;

            $isHighlighted = $_POST["itemHighlightUpdate"];


            // $oldName
            // $newName
            // $content
            // $addCombined
            // $goalCombined
            // $isHighlighted            
            // $listName = htmlspecialchars($listName, ENT_QUOTES, 'UTF-8');

            // $itemName = htmlspecialchars($itemName, ENT_QUOTES, 'UTF-8');
            // $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
            // $addCombined = htmlspecialchars($addCombined, ENT_QUOTES, 'UTF-8');
            // $goalCombined = htmlspecialchars($goalCombined, ENT_QUOTES, 'UTF-8');
            // $isHighlighted = htmlspecialchars($isHighlighted, ENT_QUOTES, 'UTF-8');


            // maybe i dont need the quote thing
            $itemName = htmlspecialchars($itemName);
            $content = htmlspecialchars($content);
            $addCombined = htmlspecialchars($addCombined);
            $goalCombined = htmlspecialchars($goalCombined);
            $isHighlighted = htmlspecialchars($isHighlighted);

            
            updateItemElse($itemName, $content, $addCombined, $goalCombined, $isHighlighted);
        }


    ?>