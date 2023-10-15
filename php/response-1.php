<?php
    if(isset($response)){
        $response = $_SERVER["PHP_SELF"]."?".http_build_query($response);
        header("Location:".$response);
    }
?>