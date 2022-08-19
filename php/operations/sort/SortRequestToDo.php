<?php

if (isset($_POST["sortByAddToDoAsc"])) {
    $_SESSION["sortbytodo"] = "add";
    $_SESSION["orderbytodo"] = "ascend";
    // echo "<script> myScroll('toDoTable'); </script>";

}

if (isset($_POST["sortByAddToDoDesc"])) {
    $_SESSION["sortbytodo"] = "add";
    $_SESSION["orderbytodo"] = "descend";
    // echo "<script> myScroll('toDoTable'); </script>";
    
}

if (isset($_POST["sortByGoalToDoAsc"])) {
    $_SESSION["sortbytodo"] = "goal";
    $_SESSION["orderbytodo"] = "ascend";
    // echo "<script> myScroll('toDoTable'); </script>";

}

if (isset($_POST["sortByGoalToDoDesc"])) {
    $_SESSION["sortbytodo"] = "goal";
    $_SESSION["orderbytodo"] = "descend";
    // echo "<script> myScroll('toDoTable'); </script>";

}
?>