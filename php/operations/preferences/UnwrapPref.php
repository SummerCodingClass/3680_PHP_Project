<?php

    function setPref($prefItems) {
        // foreach($prefItems as $item) {
        //     $bgColor = $item["bgColor"];
        //     $fontColor = $item["fontColor"];
        //     $borderColor = $item["borderColor"];
        //     $highlightColor = $item["highlightColor"];
        //     $fontSize = $item["fontSize"];
        // }

        // var_dump($prefItems);
        
        // $bgColor = $prefItems["bgColor"];
        // $fontColor = $prefItems["fontColor"];
        // $borderColor = $prefItems["borderColor"];
        // $highlightColor = $prefItems["highlightColor"];
        // $fontSize = $prefItems["fontSize"];

        // echo $prefItems["bgColor"];

        $_SESSION["bgColor"] = $prefItems["bgColor"];
        $_SESSION["fontColor"] = $prefItems["fontColor"];
        $_SESSION["borderColor"] = $prefItems["borderColor"];
        $_SESSION["highlightColor"] = $prefItems["highlightColor"];
        $_SESSION["fontSize"] = $prefItems["fontSize"];

        

    }

?>