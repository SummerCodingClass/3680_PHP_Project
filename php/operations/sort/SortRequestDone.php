<?php

if (isset($_POST["sortByAddDoneAsc"])) {
    $_SESSION["sortbydone"] = "add";
    $_SESSION["orderbydone"] = "ascend";
    // echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByAddDoneDesc"])) {
    $_SESSION["sortbydone"] = "add";
    $_SESSION["orderbydone"] = "descend";
    // echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByGoalDoneAsc"])) {
    $_SESSION["sortbydone"] = "goal";
    $_SESSION["orderbydone"] = "ascend";
    // echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByGoalDoneDesc"])) {
    $_SESSION["sortbydone"] = "goal";
    $_SESSION["orderbydone"] = "descend";
    // echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByCompletedDoneAsc"])) {
    $_SESSION["sortbydone"] = "completed";
    $_SESSION["orderbydone"] = "ascend";
    // echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByCompletedDoneDesc"])) {
    $_SESSION["sortbydone"] = "completed";
    $_SESSION["orderbydone"] = "descend";
    // echo "<script> myScroll('doneTable'); </script>";
}


?>