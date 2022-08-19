<?php 
session_start();
require_once("../operations/welcome/logout.php");
require_once("../operations/setup/config.php");

require_once("../operations/preferences/FetchPref.php");
require_once("../operations/preferences/UpdatePref.php");
require_once("../operations/preferences/UnwrapPref.php");

?>





<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width,initial-scale=1">
    
        <title>Welcome to Pocket List</title>
        <div style="margin-bottom: 70px; margin-left: 10px; margin-top: 50px; text-align: left;"> <a href="../../erd/index.php"><h2>View ERD Proposal</h2></a> </div>
    </head>

    <body>

    <?php
    //testing
        // $a = "abcd";
        // $b = date($a);
        // var_dump($b);

        $username = $_SESSION["username"];
        $currentPref = $_SESSION["currentPref"];


        if (isset($_POST["updatePrefRequest"])) {

            $username = $_SESSION["username"];
            $prefName = $currentPref;
            
            $bgColor = $_POST["bgColor"];
            $fontColor = $_POST["fontColor"];
            $borderColor = $_POST["borderColor"];
            $highlightColor = $_POST["highlightColor"];
            $fontSize = $_POST["fontSize"];

            $bgColor = htmlspecialchars($bgColor);
            $fontColor = htmlspecialchars($fontColor);
            $borderColor = htmlspecialchars($borderColor);
            $highlightColor = htmlspecialchars($highlightColor);
            $fontSize = htmlspecialchars($fontSize);

            if ($fontSize < 1 || $fontSize > 999) {
                $fontSize = 16;
                $error = "Note: invalid font size. Please enter a number between 1 and 999. The font size has been set to 16 for this request.";
                echo "<div style='color: blue'>$error</div>";
            }

            updatePref($username, $prefName, $bgColor, $fontColor, $borderColor, $highlightColor, $fontSize);


            // unset($_POST["bgColor"]);
            // unset($_POST["fontColor"]);
            // unset($_POST["borderColor"]);
            // unset($_POST["highlightColor"]);
            // unset($_POST["fontSize"]);
            // unset($_POST["updatePrefRequest"]);
        }

        
        if (isset($_POST["restoreDefaultPref"])) {
            
            $username = $_SESSION["username"];
            $prefName = $currentPref;
            
            $bgColor = "#FFFFFF";
            $fontColor = "#000000";
            $borderColor = "#000000";
            $highlightColor = "#FFFF00";
            $fontSize = "16";

            updatePref($username, $prefName, $bgColor, $fontColor, $borderColor, $highlightColor, $fontSize);


            // unset($_POST["bgColor"]);
            // unset($_POST["fontColor"]);
            // unset($_POST["borderColor"]);
            // unset($_POST["highlightColor"]);
            // unset($_POST["fontSize"]);
            // unset($_POST["restoreDefaultPref"]);
        }



        if (isset($_SESSION["result_prefupdate"]) || isset($_SESSION["error_prefupdate"])) {

            // echo 1;

            if (isset($_SESSION["result_prefupdate"])) {
                // echo 2;

                $result = $_SESSION['result_prefupdate'];
                echo "<div style='color: green' id=\"tempMsg\">$result</div>";

                // echo 3;
                unset($_SESSION["result_prefupdate"]);
                // echo 4;
            }
            // echo 5;
            if (isset($_SESSION["error_prefupdate"])) {
                // echo 6;

                $error = $_SESSION['error_prefupdate'];
                echo "<div style='color: red' id=\"tempMsg\">$error</div>";
                unset($_SESSION["error_prefupdate"]);
            }
        }        





        // echo $currentPref;

        $userPref = [];
        $userPref = fetchASetOfPreferences($username, $currentPref);
        // $_SESSION["userPref"] = $userPref;

        // var_dump($userPref);
        
        setPref($userPref);

        
        // echo $_SESSION["bgColor"];
        // echo $_SESSION["fontColor"];
        // echo $_SESSION["borderColor"];
        // echo $_SESSION["highlightColor"];
        // echo $_SESSION["fontSize"];

 


    ?>
    <style>
        body {
            background-color: <?=$_SESSION["bgColor"];?>;
            color: <?=$_SESSION["fontColor"];?>;
            /* font-size: < ?=$_SESSION["fontSize"];?>; */
        }

        form > input[type=date], input[type=time]  {
            font-size: 15px;
            margin: 2px;
            padding: 2px 10px;
        }

        input[type=text] {
            padding: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        textarea {
            padding: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        input[type=submit] {
            font-size: 15px;
            padding: 2px 10px;
        }
    
        .box {
            border: solid 1px darkgrey;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            margin: 2px;
            padding: 2px 10px;
        }

    </style>



    <?php
        require_once("../operations/multi-lists/AddList.php");

        if (isset($_POST["addListRequest"])) {
            $username = $_SESSION["username"];
            $listName = $_POST["addListName"];
            $listCategory = $_POST["addListCategory"];

            // $listName = htmlspecialchars($listName, ENT_QUOTES, 'UTF-8');
            // $listCategory = htmlspecialchars($listCategory, ENT_QUOTES, 'UTF-8');

            // didn't need the quote thing
            $listName = htmlspecialchars($listName);
            $listCategory = htmlspecialchars($listCategory);

            $_SESSION["addListName"] = $listName;
            $_SESSION["addListCategory"] = $listCategory;

            // echo $listCategory;

            addList($username, $listName, $listCategory);
            
            // unset($_POST["addListName"]);
            // unset($_POST["addListCategory"]);
        }
    ?>

    <?php
        // for proper response of the sticky form 
        if (isset($_SESSION["result_list"])) {
            unset($_SESSION["addListName"]);
            unset($_SESSION["addListCategory"]);
        }
    ?>

<?php

// require_once("../operations/scroll/scroll.php");
require_once("../operations/scroll/ScrollFunction.php");

?>
<!-- 
<script>

    function myScroll(id = "todo") {
        let targetDiv = document.getElementById(id);
        targetDiv.scrollIntoView();
    }
    // let targetDiv = document.getElementById("deleteLogForm");
    //    targetDiv.scrollIntoView();
</script> -->


    <div class="wrapper">
    <h2> Preferences </h2>

        <?php 
            require_once("../operations/preferences/PrefForm.php");

        ?>

    </div>





    <div class="wrapper" id="createNewListWrapper">
    <h2> Create a New List </h2>
    <!-- good reminder of using "check" attribute to make radio buttons sticky -->
    <!-- https://larryullman.com/forums/index.php?/topic/1889-making-radio-buttons-sticky/ -->
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="AddListForm">
        Name of the New List (max 100 characters):  <br>
        <input type="text" name="addListName" value="<?=$_SESSION["addListName"]?>" placeholder="list name" maxlength="100" size="100" style="width: 700px"> <br><br>
        The Category for This List is:  <br>
        <!-- Name of the Category for This List (max 100 characters):  <br> -->
        <!-- <input type="text" name="addListCategory" value="< ?=$_SESSION["addListCategory"]?>" placeholder="list category" maxlength="100" size="100" style="width: 700px"> <br><br> -->
        <input type="radio" name="addListCategory" value="default" 
        <?php 
        if (isset($_SESSION["addListCategory"])) {
            $selection = $_SESSION["addListCategory"];
            if ($selection == "default") {
                echo "checked='checked'";
            }
        }
        else {
            echo "checked='checked'";
        }        
        ?>
        > Default <br>

        <input type="radio" name="addListCategory" value="one"
        <?php

            if (isset($_SESSION["addListCategory"])) {
                $selection = $_SESSION["addListCategory"];
                if ($selection == "one") {
                    echo "checked='checked'";
                }
            }
        ?>
        > Category One <br>
        <input type="radio" name="addListCategory" value="two"
        <?php

            if (isset($_SESSION["addListCategory"])) {
                $selection = $_SESSION["addListCategory"];
                if ($selection == "two") {
                    echo "checked='checked'";
                }
            }
        ?>
        > Category Two <br> <br>
        <input type="submit" name="addListRequest" value="Create List">
    </form>

    <?php
        //splitting into two for proper sticky form purpoess
        
        if (isset($_SESSION["result_list"]) || isset($_SESSION["error_list"])) {

            // echo 1;

            if (isset($_SESSION["result_list"])) {
                // echo 2;

                $result = $_SESSION['result_list'];
                echo "<div style='color: green' id=\"tempMsg\">$result</div>";

                // echo 3;
                unset($_SESSION["result_list"]);
                unset($_SESSION["addListName"]);
                unset($_SESSION["addListCategory"]);

                // echo 4;
            }
            // echo 5;
            if (isset($_SESSION["error_list"])) {
                // echo 6;

                $error = $_SESSION['error_list'];
                echo "<div style='color: red' id=\"tempMsg\">$error</div>";
                unset($_SESSION["error_list"]);
            }
        }
    ?>

    
    </div>
    

    <?php
        require_once("../operations/multi-lists/FetchLists.php");
        require_once("../operations/multi-lists/DeleteList.php");
        require_once("../operations/multi-lists/SelectList.php");
        
        if (isset($_POST["deleteList"])) {
            $username = $_SESSION["username"];
            $listName = $_POST["listNameSelected"];

            if ($listName == "default") {
                $msg = "Error. The 'default' list cannot be deleted.";
                echo "<div style='color: red'>$msg</div>";
            }
            else {

                deleteList($username, $listName);
                
                $currentList = $_SESSION["currentList"];
                if ($listName == $currentList) {
                    $_SESSION["currentList"] = "default";
                    
                    $msg = "Current list deleted. Current list has now been switched to the 'default' list";
                    echo "<div style='color: blue'>$msg</div>";

                } 
            }

            // unset($_POST["deleteList"]);
            // unset($_POST["listNameSelected"]);
        }

        if (isset($_POST["switchToList"])) {
            $username = $_SESSION["username"];
            $listName = $_POST["listNameSelected"];

            // $_SESSION["currentList"] = $listName;
            selectList($username, $listName);

            // unset($_POST["switchToList"]);
            // unset($_POST["listNameSelected"]);
        }

    ?>


    <div class="wrapper" id="selectListsWrapper">
    <h2> Select Lists </h2>

    <?php
        require_once("../operations/multi-lists/ListDisplay.php");

    ?>

    </div>






    <?php
    // for proper response of the sticky form 
        if (isset($_SESSION["result_item"])) {
            unset($_SESSION["itemName"]);
            unset($_SESSION["itemContent"]);
        }
    ?>











    <!-- date time ref: https://www.w3schools.com/php/php_date.asp -->

    <div class="wrapper" id="addItemsWrapper">
    <h2> Add Items </h2>
    <form action="../operations/item/AddItem.php" method="POST" id="AddItemForm">
            Item Name (max 100 characters):  <br>
            <input type="text" name="itemName" value="<?=$_SESSION["itemName"]?>" placeholder="item name" maxlength="150" size="100" style="width: 700px"> <br> <br>
            Item Content (max 1000 characters): <br>

            <textarea type="textarea" name="itemContent" placeholder="content body" maxlength=1000 style="width: 700px" cols="100" rows="12" form="AddItemForm"><?=$_SESSION["itemContent"]?></textarea><br> <br>

            <input type="hidden" name="itemAddDate" value=<?=date("Y-m-d");?>>
            <input type="hidden" name="itemAddTime" value=<?=date("H:i");?>> 
            Goal Time: <br>           
            <input type="date" name="itemGoalDate" value=<?=date("Y-m-d");?>> 
            <input type="time" name="itemGoalTime" value=<?=date("H:i");?>> <br> <br>
            <input type="submit" name="addItemRequest" value="Add Item">
    </form>
    
    <!-- </div> -->
    
    <?php
       
        //splitting into two for proper sticky form purpoess

        if (isset($_SESSION["result_item"]) || isset($_SESSION["error_item"])) {

            if (isset($_SESSION["result_item"])) {
                $result = $_SESSION['result_item'];
                echo "<div style='color: green' id=\"tempMsg\">$result</div>";
                
                //had to move this to scrollrequests.php
                // unset($_SESSION["result_item"]);
                unset($_SESSION["itemName"]);
                unset($_SESSION["itemContent"]);
            }
            if (isset($_SESSION["error_item"])) {
                $error = $_SESSION['error_item'];
                echo "<div style='color: red' id=\"tempMsg\">$error</div>";

                //had to move this to scrollrequests.php
                // unset($_SESSION["error_item"]);
            }
        }

    ?>
    </div>

<?php

    require_once("../operations/edit/UpdateItemNameFunction.php");
    require_once("../operations/edit/UpdateItemElseFunction.php");
    require_once("../operations/edit/UpdateRequests.php");

?>


<div class="wrapper" id="editItemsWrapper">
    <h2>Edit Items </h2>
    

    <?php
    // moved to external file because this needs to happen before logs are fetched for display
        // require_once("../operations/edit/UpdateItemFunctions.php");

        // if (isset($_POST["editItemSubmit"])) {
        //     $oldName = $_POST["itemNameUpdate_old"];
        //     $newName = $_POST["itemNameUpdate_new"];
        //     $content = $_POST["itemContentUpdate"];
            
        //     $addDate = $_POST["itemAddDateUpdate"];
        //     $addTime = $_POST["itemAddTimeUpdate"];

        //     $addCombined = $addDate . " ". $addTime;

        //     $goalDate = $_POST["itemGoalDateUpdate"];
        //     $goalTime = $_POST["itemGoalTimeUpdate"];

        //     $goalCombined = $goalDate . " ". $goalTime;

        //     $isHighlighted = $_POST["update_ItemHighlight"];


        //     // $oldName
        //     // $newName
        //     // $content
        //     // $addCombined
        //     // $goalCombined
        //     // $isHighlighted            
        //     // $listName = htmlspecialchars($listName, ENT_QUOTES, 'UTF-8');

        //     $oldName = htmlspecialchars($oldName, ENT_QUOTES, 'UTF-8');
        //     $newName = htmlspecialchars($newName, ENT_QUOTES, 'UTF-8');
        //     $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        //     $addCombined = htmlspecialchars($addCombined, ENT_QUOTES, 'UTF-8');
        //     $goalCombined = htmlspecialchars($goalCombined, ENT_QUOTES, 'UTF-8');
        //     $isHighlighted = htmlspecialchars($isHighlighted, ENT_QUOTES, 'UTF-8');

            
        //     updateItemName($oldName, $newName);
        // }

    ?>

    <?php
    require_once("../operations/edit/UpdateItemForm.php");
    ?>

</div>


<?php

    require_once("../operations/sort/SortRequestToDo.php");
    require_once("../operations/sort/SortRequestDone.php");

    require_once("../operations/sort/SortAdd.php");
    require_once("../operations/sort/SortGoal.php");
    require_once("../operations/sort/SortCompleted.php");    

    require_once("../operations/sort/SetUpToDo.php");
    require_once("../operations/sort/SetUpDone.php");

    require_once("../operations/item/DeleteItem.php");
    require_once("../operations/item/UpdateDone.php");
    require_once("../operations/item/HighlightItem.php");

    require_once("../operations/sort/ClearList.php");


    if (isset($_POST["markDoneRequest"])) {

        $username = $_SESSION["username"];
        $listName = $_POST["item_ListName"];
        $itemName = $_POST["item_ItemName"];

        updateItemDone($username, $listName, $itemName, "1");

        // unset($_POST["markDoneRequest"]);
    }

    if (isset($_POST["markNotDoneRequest"])) {

        $username = $_SESSION["username"];
        $listName = $_POST["item_ListName"];
        $itemName = $_POST["item_ItemName"];

        updateItemDone($username, $listName, $itemName, "0");

        // unset($_POST["markNotDoneRequest"]);
    }

    if (isset($_POST["highlightItemRequest"])) {

        $username = $_SESSION["username"];
        $listName = $_POST["item_ListName"];
        $itemName = $_POST["item_ItemName"];
        
        updateItemHighlight($username, $listName, $itemName);

        // unset($_POST["highlightItemRequest"]);
    }

    if (isset($_POST["deleteItemRequest"])) {

        $username = $_SESSION["username"];
        $listName = $_POST["item_ListName"];
        $itemName = $_POST["item_ItemName"];

        deleteItem($username, $listName, $itemName);

        // unset($_POST["deleteItemRequest"]);
    }

    if (isset($_POST["deleteToDo"])) {

        $username = $_SESSION["username"];
        $listName = $_SESSION["currentList"];

        clearList($username, $listName, "0");

        // unset($_POST["deleteToDo"]);
    }

    if (isset($_POST["deleteDone"])) {

        $username = $_SESSION["username"];
        $listName = $_SESSION["currentList"];

        clearList($username, $listName, "1");

        // unset($_POST["deleteDone"]);
    }


    if (isset($_POST["deleteBoth"])) {

        $username = $_SESSION["username"];
        $listName = $_SESSION["currentList"];

        clearList($username, $listName, "0");
        clearList($username, $listName, "1");

        // unset($_POST["deleteBoth"]);
    }



?>


<style>
    .highlighted {
        /* background-color: #FFFF00; */
        background-color: <?=$_SESSION["highlightColor"];?>;
    }

    th {
        padding: 10px;
        /* text-overflow: "-";
        overflow: scroll;
        white-space: wrap;
        text-wrap: wrap; */
        border-collapse: collapse;
        border: solid 2px <?=$_SESSION["borderColor"];?>;
    }

    td {
        padding: 2px 5px;
        font-size: <?=$_SESSION["fontSize"];?>px;
        
        border-collapse: collapse;
        border: solid 2px <?=$_SESSION["borderColor"];?>;
        /* font-size: 16px; */
        /* text-overflow: "-"; */
        /* overflow: scroll; */
        /* white-space: wrap; */
        /* text-wrap: wrap; */
    }

    /* https://developer.mozilla.org/en-US/docs/Web/CSS/white-space */
    .scrollable {
        height: auto;
        text-overflow: wrap;
        text-wrap: wrap;
        overflow: auto;
        white-space: normal;
        padding: 5px;
    }

    .contentColumn {
        width: 400px;
        max-width: 400px;
        height: auto;
    }

    .nameColumn {
        width: 200px;
        max-width: 100px;
        height: auto;
    }

    .timeColumn {
        width: 100px;
    }

    form {
        margin: 2px;
    }

    input[type=submit] {
        margin: 0px 3px;
    }

    .sort {
        margin: 0px 1px;
    }
    
    .wrapper {
        margin: 50px 10px;
        border-collapse: collapse;
        padding-bottom: 20px;
        border-bottom: solid 2px blue;
    }

    table {
        margin: 50px 10px;
        border-collapse: collapse;
        border: solid 2px <?=$_SESSION["borderColor"];?>;
    }

    #currentListName {
        color: purple;
    }

</style>




<div id = "listWrapper" class="wrapper">

    <h1 id='currentListName'> List Name: <?=$_SESSION["currentList"];?></h2>

    <h2>Your Lists</h2>
    <br>




    <div id="todoDiv">
        <h3>To Do List</h3>

        <?php
            $currentSortByToDo = $_SESSION["sortbytodo"];
            $currentOrderByToDo = $_SESSION["orderbytodo"];

            echo "Currently sorting by: $currentSortByToDo time <br>";
            echo "Currently sorting in the order of: $currentOrderByToDo" ."ing <br>";

            $address = $_SERVER['PHP_SELF'];

            echo
            "
            <form action=\"$address\" method=\"POST\" id=\"clearToDoListButton\">
            <input style='background-color: red; margin:5px;' type=\"submit\" name=\"deleteToDo\" value=\"Clear To Do List\">
            </form>
            ";

            echo "⚠ Please be careful in clicking this. There is no confirmation. The list will be permanently deleted right away.";

            if ($currentSortByToDo == "add") {
                $allToDo = fetchItemsByAddTime("0", $currentOrderByToDo);
            }

            else if ($currentSortByToDo == "goal") {
                $allToDo = fetchItemsByGoalTime("0", $currentOrderByToDo);
            }
            
            else {

                //default add + ascend
                $allToDo = fetchItemsByAddTime("0", "ascend");
            }

            setUpDisplayForToDo($allToDo);

        ?>

    </div>


    <div id="doneDiv">
        <h3>Done List</h3>

        <?php

            $currentSortByDone = $_SESSION["sortbydone"];
            $currentOrderByDone = $_SESSION["orderbydone"];

            echo "Currently sorting by: $currentSortByDone time <br>";
            echo "Currently sorting in the order of: $currentOrderByDone" ."ing <br>";
            
            $address = $_SERVER['PHP_SELF'];

            echo
            "
            <form action=\"$address\" method=\"POST\" id=\"clearDoneListButton\">
            <input style='background-color: red; margin:5px;' type=\"submit\" name=\"deleteDone\" value=\"Clear Done List\">
            </form>
            ";

            echo "⚠ Please be careful in clicking this. There is no confirmation. The list will be permanently deleted right away.";

            if ($currentSortByDone == "add") {
                $allDone = fetchItemsByAddTime("1", $currentOrderByDone);
            }

            else if ($currentSortByDone == "goal") {
                $allDone = fetchItemsByGoalTime("1", $currentOrderByDone);
            }
            
            else if ($currentSortByDone == "completed") {
                $allDone = fetchItemsByCompletedTime("1", $currentOrderByDone);
            }
            
            else {

                //default add + ascend
                $allDone = fetchItemsByAddTime("1", "ascend");
            }

            setUpDisplayForDone($allDone);
        ?>

    </div>

    <?php
        echo
        "
        <form action=\"$address\" method=\"POST\" id=\"clearBothListsButton\">
        <input style='background-color: red; margin:5px;' type=\"submit\" name=\"deleteBoth\" value=\"Clear Both Lists\">
        </form>
        ";

        echo "⚠ Please be careful in clicking this. There is no confirmation. The list will be permanently deleted right away.";
    ?>

</div>



<!-- moved above view items for error msg to display properly -->
<!-- 
<div class="wrapper" id="editItemsWrapper">
    <h2>Edit Items </h2>
    
 
    < ?php
    // moved to external file because this needs to happen before logs are fetched for display
        // require_once("../operations/edit/UpdateItemFunctions.php");

        // if (isset($_POST["editItemSubmit"])) {
        //     $oldName = $_POST["itemNameUpdate_old"];
        //     $newName = $_POST["itemNameUpdate_new"];
        //     $content = $_POST["itemContentUpdate"];
            
        //     $addDate = $_POST["itemAddDateUpdate"];
        //     $addTime = $_POST["itemAddTimeUpdate"];

        //     $addCombined = $addDate . " ". $addTime;

        //     $goalDate = $_POST["itemGoalDateUpdate"];
        //     $goalTime = $_POST["itemGoalTimeUpdate"];

        //     $goalCombined = $goalDate . " ". $goalTime;

        //     $isHighlighted = $_POST["update_ItemHighlight"];


        //     // $oldName
        //     // $newName
        //     // $content
        //     // $addCombined
        //     // $goalCombined
        //     // $isHighlighted            
        //     // $listName = htmlspecialchars($listName, ENT_QUOTES, 'UTF-8');

        //     $oldName = htmlspecialchars($oldName, ENT_QUOTES, 'UTF-8');
        //     $newName = htmlspecialchars($newName, ENT_QUOTES, 'UTF-8');
        //     $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        //     $addCombined = htmlspecialchars($addCombined, ENT_QUOTES, 'UTF-8');
        //     $goalCombined = htmlspecialchars($goalCombined, ENT_QUOTES, 'UTF-8');
        //     $isHighlighted = htmlspecialchars($isHighlighted, ENT_QUOTES, 'UTF-8');

            
        //     updateItemName($oldName, $newName);
        // }

    ?>

    <?php
    require_once("../operations/edit/UpdateItemForm.php");
    ?>

</div> -->

<?php

require_once("../operations/scroll/ScrollRequests.php");

?>

<!-- < ?php 
require_once("../operations/preferences/PrefForm.php");

?> -->


    </body>

</html> 

