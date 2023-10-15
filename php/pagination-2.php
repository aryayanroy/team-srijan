<?php
    try{
        $sql->execute();
        $total_records = $sql->fetch(PDO::FETCH_NUM)[0];
        
        $total_pages = ceil($total_records / 10);
        $start_page = max($active_page - 1, 1);

        $end_page = min($start_page + 2, $total_pages);
        $disabled = ($active_page == 1)? " disabled" : null;
        echo "<ul class='pagination justify-content-center'><li class='page-item".$disabled."'><a href='?page=".max(($active_page - 1), 1)."' class='page-link'><i class='fa-solid fa-chevron-left'></i></a></li>";
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            $active = ($i == $active_page) ? " active" : null;
            echo "<li class='page-item".$active."'><a href='?page=".$i."' class='page-link'>".$i."</a></li>";
        }

        $disabled = ($active_page == $total_pages)? " disabled" : null;
        echo "<li class='page-item".$disabled."'><a href='?page=".min($active_page + 1, $total_pages)."' class='page-link'><i class='fa-solid fa-chevron-right'></i></a></li></ul>";
    }catch(PDOException $e){
        echo "Something went wrong: ".$e;
    }
?>