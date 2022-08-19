<?php
// require_once("FetchLists.php");

// largely from lab 3 / project 2



function printItem($listName) {
    // echo $listName;
    
    echo "<div class='listContainers'>";
    // echo "<span class='listNames'> List Name: $listName </span>";
    
    $form = 
    "<form class='listForms' method=\"POST\"> 
    <input type=\"hidden\" name=\"listNameSelected\" value=\"$listName\">
    <input class='deleteButtons' type=\"submit\" name=\"deleteList\" value=\"Delete This List\">
    <input class='selectButtons' type=\"submit\" name=\"switchToList\" value=\"Switch to This List\">
    </form>";

    echo "<span> $form </span>";
    echo "<span class='listNames'> <b> List Name: </b> $listName </span>";

    echo "<br>";
    echo "</div>";
}



function printItemWithCategory($listName, $categoryName) {
    // echo $listName;
    

    echo "<div class='listContainers'>";
    // echo "<span class='listNames'> List Name: $listName </span>";
    
    $form = 
    "<form class='listForms' method=\"POST\"> 
    <input type=\"hidden\" name=\"listNameSelected\" value=\"$listName\">
    <input class='deleteButtons' type=\"submit\" name=\"deleteList\" value=\"Delete This List\">
    <input class='selectButtons' type=\"submit\" name=\"switchToList\" value=\"Switch to This List\">
    </form>";

    echo "<span> $form </span>";
    echo "<span class='listNames'> <b>List Name:</b> $listName </span> <span class='categoryNames'> <b>Category:</b> $categoryName</span>";

    echo "<br>";
    echo "</div>";
}

function printCategories($category) {
    $formattedName = ucfirst($category);
    echo "<span> <a href=\"$address?category=$category\"> $formattedName </a> </span> <br>";
}





?>





<!-- copied from my project 2 -->


<style>
    #menu{
        display:flex; 
        text-align: center; 
        justify-content: space-between;
        gap: auto;
        margin: 10px;
        font-size: 20px;
    }
    
    #Clear{
        background-color: yellow;
    }

    .listNames {
        font-size: 18px;
        margin: 0px 5px;
        color: blue;
    }

    .categoryNames {
        font-size: 18px;
        margin: 0px 5px;
        color: purple;
    }

    .selectButtons {
        /* font-size: 20px; */
        background-color: lightyellow;
    }

    .deleteButtons {
        /* font-size: 20px; */
        background-color: lightpink;
    }

    .listForms {
        display: inline;   
    }

    .listContainers {
        width: 700px;
    }

</style>

    <div id="menu"> 

    <!-- ======================= -->
    <!-- generate category links -->
    <!-- ======================= -->

        <?php

        $categories = fetchAllCategories();

        //All
        printCategories("all");
        
        //Indiv categories
        foreach ($categories as $category) {
            printCategories($category);
        }

        //Clear category
        $address = $_SERVER['PHP_SELF'];
        echo "<span id='Clear'> <a href=\"$address\"> Clear Category</a> </span> <br>";
        echo "<br>";
        ?>

    </div>

    <br>

    <!-- ========= -->
    <!-- searchbar -->
    <!-- ========= -->

    <form action="<?=$_SERVER['PHP_SELF'] ?>" method="GET">
            <input type="text" name="itemSearched" placeholder="List Name" required>
            <input type="submit" value="Search" name="SearchIt"> 
    </form>
    <br>
    <br>
    

    
    <?php

  

    // <!-- ============== -->
    // <!-- search results -->
    // <!-- ============== -->

    
    if (isset($_GET["SearchIt"])) {
        // sanitize what we got from GET
        $itemSearched = htmlspecialchars($_GET["itemSearched"]);

        // match item searched from GET with item name in arrays
        $boolFound = false;
        $items = fetchAllLists();

        foreach ($items as $item) {
            $listName = $item["listName"];
            $categoryName = $item["categoryName"];
            if (strcasecmp($itemSearched, $listName) == 0) {
                printItemWithCategory($listName, $categoryName);
                $boolFound = true; 
            }
        }
        // if not found:
        if ($boolFound == false) {
            echo "List name not found. <br>";
        }
    }


    // <!-- =================== -->
    // <!-- display by category -->
    // <!-- =================== -->


    
    if (isset($_GET["category"])) {
        $clickedCategory = htmlspecialchars($_GET["category"]);
    

        $items = [];

        //ALL
        if($clickedCategory == "all") {
            
            // echo 1;
            $items = fetchAllLists();
            // echo 2;

            // var_dump($items);

            foreach($items as $item) {
                // var_dump($item);
                // foreach($item as $itemName => $categoryName) {
                    // printItemWithCategory($itemName, $categoryName);
                // }
                $listName = $item["listName"];
                $categoryName = $item["categoryName"];
                printItemWithCategory($listName, $categoryName);
            }
        }

        //Other
        else {
            $items = fetchListsByCategory("$clickedCategory");
    
            foreach($items as $item) {
                printItem($item);
            }
        }
    
    }
?>
