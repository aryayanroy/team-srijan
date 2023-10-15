<footer class="navbar">
    <div class="container-xl flex">
        <nav class="nav">
            <?php
                $links = json_decode(file_get_contents("json/links.json"), true);
                if($links!=null){
                    foreach ($links as $icon => $link){
                        if($link != null){
                            echo "<a href='".$link."' class='nav-link link-body-emphasis' target='_blank'><i class='fa-brands fa-".$icon."'></i></a>";
                        }
                    }
                }
            ?>
        </nav>
        <small class="text-body-emphasis">© <?php echo date("Y"); ?> Team Srijan</small>
    </div>
</footer>