<!-- <script>

    function scroll(id) {
        let targetDiv = document.getElementById(id);
        targetDiv.scrollIntoView();
    }
    // let targetDiv = document.getElementById("deleteLogForm");
    //    targetDiv.scrollIntoView();
</script> -->
<!-- // moved to home.php for update button to use it-->

<?php

// troubleshoot: https://stackoverflow.com/questions/29846569/typeerror-value-cant-be-converted-to-a-dictionary

if (isset($_POST["updatePrefRequest"]) || isset($_POST["restoreDefaultPref"])) {

    $id="tempMsg";
    echo "<script> myScroll('$id'); </script>";

    // echo "<script> myScroll('PrefForm'); </script>";

}

if (isset($_POST["addListRequest"])) {
    // echo "<script> myScroll('AddListForm'); </script>";
    echo "<script> myScroll('createNewListWrapper'); </script>";

    // $id="tempMsg";
    // echo "<script> myScroll('$id'); </script>";

}

if (isset($_GET["category"]) || isset($_GET["SearchIt"])) {
    // echo "<script> myScroll('AddListForm'); </script>";
    echo "<script> myScroll('selectListsWrapper'); </script>";

    // $id="tempMsg";
    // echo "<script> myScroll('$id'); </script>";

}

if (isset($_POST["deleteList"]) || isset($_POST["switchToList"])) {
    // echo "<script> myScroll('menu'); </script>";
    
    $id="tempMsg";
    echo "<script> myScroll('$id'); </script>";

}

// if (isset($_POST["addItemRequest"])) {
//     echo "loop hit";

//     // echo "<script> myScroll('addItemsWrapper'); </script>";

//     // $id="addItemsWrapper";
//     // echo "<script> myScroll('$id'); </script>";


// }

// this one was structured diffly so we wont find $_POST["addItemRequest] in home.php... that info was setn to additem.php
// also have to split in two to differentiate btwn the unset variables.

if (isset( $_SESSION["result_item"])) {

    echo "<script> myScroll('addItemsWrapper'); </script>";
    unset($_SESSION["result_item"]);

}
if (isset( $_SESSION["error_item"])) {
    
    echo "<script> myScroll('addItemsWrapper'); </script>";
    unset($_SESSION["error_item"]);

}






if (isset($_POST["markDoneRequest"]) || isset($_POST["markNotDoneRequest"])) {

    // this one can't jump with the row because that id is not going to stay the same
    // e.g. todo1 might become done 3.
    
    $id="tempMsg";
    echo "<script> myScroll('$id'); </script>";


    // $id = $_POST["item_RowID"];
    // $id = "$id";
    // echo "<script> myScroll('$id'); </script>";


    // echo "<button onclick=" . "myScroll('$id')" . "> jump to item </button";
    // unset($_POST["item_RowID"]);
    
}

if (isset($_POST["highlightItemRequest"])) {

    $id = $_POST["item_RowID"];
    $id = "$id";
    echo "<script> myScroll('$id'); </script>";
    
}

if (isset($_POST["deleteItemRequest"])) {

    $id="tempMsg";
    echo "<script> myScroll('$id'); </script>";

    // $id = $_POST["item_RowID"];
    // $id = "$id";
    // echo "<script> myScroll('$id'); </script>";
    
}

if (isset($_POST["deleteToDo"])) {

    
    $id="tempMsg";
    echo "<script> myScroll('$id'); </script>";

    // echo "<script> myScroll('todoDiv'); </script>";

}


if (isset($_POST["deleteDone"])) {

    
    $id="tempMsg";
    echo "<script> myScroll('$id'); </script>";

    // echo "<script> myScroll('doneDiv'); </script>";

}

if (isset($_POST["deleteBoth"])) {

    
    $id="tempMsg";
    echo "<script> myScroll('$id'); </script>";

    // echo "<script> myScroll('doneDiv'); </script>";

}


if (isset($_POST["editItemRequest"])) {

    // $id="tempMsg";
    // echo "<script> myScroll('$id'); </script>";

    echo "<script> myScroll('editItemsWrapper'); </script>";

    // $id = "EditItemForm";

    // echo "<script>";
    // echo "(document.getElementById(" . $id . ")).scrollIntoView();";
    // echo "</script>";
}

if (isset($_POST["editItemNameSubmit"])) {

    // $id="tempMsg";
    // echo "<script> myScroll('$id'); </script>";

    echo "<script> myScroll('tempMsg'); </script>";

    // $id = "EditItemForm";

    // echo "<script>";
    // echo "(document.getElementById(" . $id . ")).scrollIntoView();";
    // echo "</script>";
}



//sort todo

if (isset($_POST["editItemSubmit"])) {

    echo "<script> myScroll('tempMsg'); </script>";
}



if (isset($_POST["sortByAddToDoAsc"])) {
   
    echo "<script> myScroll('toDoTable'); </script>";

}

if (isset($_POST["sortByAddToDoDesc"])) {
   
    echo "<script> myScroll('toDoTable'); </script>";
    
}

if (isset($_POST["sortByGoalToDoAsc"])) {
    
    echo "<script> myScroll('toDoTable'); </script>";

}

if (isset($_POST["sortByGoalToDoDesc"])) {
    
    echo "<script> myScroll('toDoTable'); </script>";

}



//sort done

if (isset($_POST["sortByAddDoneAsc"])) {
    
    echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByAddDoneDesc"])) {
    
    echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByGoalDoneAsc"])) {
    
    echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByGoalDoneDesc"])) {
    
    echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByCompletedDoneAsc"])) {
    
    echo "<script> myScroll('doneTable'); </script>";
}

if (isset($_POST["sortByCompletedDoneDesc"])) {
    
    echo "<script> myScroll('doneTable'); </script>";
}



// unset($_POST);
// https://stackoverflow.com/questions/12953917/clearing-post-array-fully
$_POST = array();


?>