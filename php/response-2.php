<?php
    if(isset($_GET["status"]) && isset($_GET["message"])){
        echo "<div class='alert ";
        print ($_GET["status"])? "alert-success" : "alert-danger";
        echo " mb-0'>".$_GET["message"]."</div>";
    }
?>