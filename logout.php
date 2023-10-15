<?php
    session_start();
    session_destroy();
    if($_SESSION["id"]){
        echo "Successfully logged out";
        header("refresh:2; URL=/teamsrijan/login");
    }else{
        header("Location: login");
    }
?>