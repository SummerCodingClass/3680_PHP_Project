    <?php
    // for proper response of the sticky form 
        // if (isset($_SESSION["result_pref"])) {
        //     unset($_SESSION["prefName"]);
        //     unset($_SESSION["prefContent"]);
        // }
    ?>

    <!-- date time ref: https://www.w3schools.com/php/php_date.asp -->

    <!-- <div class="wrapper">
    <h2> Preferences </h2> -->
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="PrefForm">
            <!-- Preference Name (max 100 characters):  <br> -->
            <!-- <input type="text" name="itemName" value="< ?=$_SESSION["itemName"]?>" placeholder="item name" maxlength="150" size="100" style="width: 700px"> <br> <br> -->

<!-- $_SESSION["bgColor"];
$_SESSION["fontColor"];
$_SESSION["borderColor"];
$_SESSION["highlightColor"];
$_SESSION["fontSize"];             -->

            Background Color:
            <input type="color" name="bgColor" value="<?=$_SESSION['bgColor'];?>"><br><br>
            Font Color:
            <input type="color" name="fontColor" value="<?=$_SESSION['fontColor'];?>"><br><br>
            Border Color (Table Only):
            <input type="color" name="borderColor" value="<?=$_SESSION['borderColor'];?>"><br><br>
            Highlight Color:
            <input type="color" name="highlightColor" value="<?=$_SESSION['highlightColor'];?>"><br><br>
            Font Size (Table Only):
            <input type="number" name="fontSize" value="<?=$_SESSION['fontSize'];?>"><br><br>

            <input style="background-color: #78AD9E;" type="submit" name="updatePrefRequest" value="Update Preferences"> 
            <input style="background-color: lightpink;" type="submit" name="restoreDefaultPref" value="Restore Default Settings">
    </form>
    
    <!-- </div> -->
    
    <?php
       
        // //splitting into two for proper sticky form purpoess

        // if (isset($_SESSION["result_item"]) || isset($_SESSION["error_item"])) {

        //     if (isset($_SESSION["result_item"])) {
        //         $result = $_SESSION['result_item'];
        //         echo "<div style='color: green'>$result</div>";
        //         unset($_SESSION["result_item"]);
        //         unset($_SESSION["itemName"]);
        //         unset($_SESSION["itemContent"]);
        //     }
        //     if (isset($_SESSION["error_item"])) {
        //         $error = $_SESSION['error_item'];
        //         echo "<div style='color: red'>$error</div>";
        //         unset($_SESSION["error_item"]);
        //     }
        // }

    ?>