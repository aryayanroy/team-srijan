<?php
    $active_page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? max(1, $_GET["page"]) : 1;
    $offset = ($active_page - 1) * 10;
?>