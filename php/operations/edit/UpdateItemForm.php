
<?php
    // for proper response of the sticky form 
        if (isset($_SESSION["result_itemUpdate"])) {
            unset($_SESSION["itemName"]);
            unset($_SESSION["itemContentUpdate"]);
        }
    ?>

    <!-- date time ref: https://www.w3schools.com/php/php_date.asp -->

    
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="EditItemForm">
            
            You are currently editing item:  <br>
            <h3 style="color: #AB5805">Name - <?=$_POST["update_ItemName"]?></h3><br> <br>
            <input type="hidden" name="itemNameUpdate_old" value="<?=$_POST["update_ItemName"]?>">


            New Item Name (max 100 characters):  <br>
            <input type="text" name="itemNameUpdate_new" value="<?=$_POST["update_ItemName"]?>" maxlength="150" size="100" style="width: 700px"> <br> <br>

            <input type="submit" name="editItemNameSubmit" value="Update Item Name"><br>

            Note: For now, submitting item name will lose changes to the rest of the form, and vice versa.

            <br><br>



            Item Content (max 1000 characters): <br>

            <textarea type="textarea" name="itemContentUpdate" maxlength=1000 style="width: 700px" cols="100" rows="12" form="EditItemForm"><?=$_POST["update_ItemContent"]?></textarea><br> <br>

            Add Time: 
            <input type="date" name="itemAddDateUpdate" value=<?=$_POST["update_ItemAddDate"]?>>
            <input type="time" name="itemAddTimeUpdate" value=<?=$_POST["update_ItemAddTime"]?>> <br> <br>
            Goal Time: 
            <input type="date" name="itemGoalDateUpdate" value=<?=$_POST["update_ItemGoalDate"]?>> 
            <input type="time" name="itemGoalTimeUpdate" value=<?=$_POST["update_ItemGoalTime"]?>> <br> <br>
            
            Highlight? 
            <?php
                if ($_POST["update_ItemHighlight"] == "1") {
                    $checked = true;
                }
                else {
                    $checked = false;
                }
            ?>
            <input type="radio" name="itemHighlightUpdate" value="1" <?php if ($checked) {echo "checked='checked'";}?>> Yes 
            <input type="radio" name="itemHighlightUpdate" value="0" <?php if (!$checked) {echo "checked='checked'";}?>> No <br> <br>
            <input type="submit" name="editItemSubmit" value="Update Item">

    </form>
    
    <!-- </div> -->
    
    <?php
       
        //splitting into two for proper sticky form purpoess

        //for testing. works so now moved inside and shown only if succeeded.
        // $id = $_POST["update_RowID"];
        // $id = "$id";
        // echo "<button onclick=" . "myScroll('$id')" . "> jump to item </button";
        // <button onclick="myFunction()">Click me</button> 

        if (isset($_SESSION["result_itemUpdate"]) || isset($_SESSION["error_itemUpdate"])) {

            if (isset($_SESSION["result_itemUpdate"])) {
                $result = $_SESSION['result_itemUpdate'];
                echo "<div style='color: green' id=\"tempMsg\">$result</div>";
                unset($_SESSION["result_itemUpdate"]);
                unset($_POST["update_ItemName"]);
                unset($_POST["itemContentUpdate"]);

                // $id = $_POST["update_RowID"];
                // echo "<button onclick='scroll($id);'> jump to item </button"


                $id = $_POST["update_RowID"];
                $id = "$id";
                echo "<button onclick=" . "myScroll('$id')" . "> jump to item </button";

            }
            if (isset($_SESSION["error_itemUpdate"])) {
                $error = $_SESSION['error_itemUpdate'];
                echo "<div style='color: red' id=\"tempMsg\">$error</div>";
                unset($_SESSION["error_itemUpdate"]);
            }
        }

    ?>
    </div>