<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/user-head.php";
        include_once "php/config.php";
        if(isset($_GET["year"]) && is_numeric($_GET["year"])){
            $active_year = $_GET["year"];
        }else{
            $sql = $conn->prepare("SELECT DISTINCT MAX(year) FROM crews");
            try{
                $sql->execute();
                $active_year = $sql->fetch(PDO::FETCH_NUM)[0];
            }catch(PDOException $e) {
                $active_year = date("Y");
            }
        }
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <title>Crew of <?php echo $active_year; ?> | Team Srijan - Birla Institute of Technology, Mesra</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once "php/header.php"; ?>
    <main class="flex-grow-1 bg-body-tertiary">
        <article class="container-xxl">
            <?php
                $page = "crews";
                include_once "php/page.php";
            ?>
            <div class="overflow-x-auto">
                <ul class="nav nav-pills flex-nowrap flex-md-wrap">
                    <?php
                        $sql = $conn->prepare("SELECT DISTINCT year FROM crews ORDER BY year DESC");
                        try{
                            $sql->execute();
                            if($sql->rowCount()>0){
                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                    $year = $row["year"];
                                    echo "<li class='nav-item'>
                                        <a href='?year=$year' class='nav-link";
                                    print ($year == $active_year)? " active" : null;
                                    echo "'>$year</a>
                                    </li>";
                                }
                            }
                        }catch(PDOException $e){
                            echo "Internal error: ".$e."";
                        }
                    ?>
                </ul>
            </div>
            <?php
                $sql = $conn->prepare("SELECT c.name, c.image, c.email, c.link, t.id AS team_id, t.name AS team_name FROM crews c JOIN teams t ON c.team = t.id WHERE c.year = ? ORDER BY t.id");
                $sql->bindParam(1, $active_year, PDO::PARAM_INT);

                try {
                    $sql->execute();
                    if($sql->rowCount()>0){
                        $prev_team = null;
                        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                            if ($prev_team != $row["team_id"]) {
                                if ($prev_team !== null) {
                                    echo "</div></div></section>";
                                }
                                echo "<hr><section>";
                                echo "<h2 class='text-center'>" . $row["team_name"] . "</h2>";
                                echo "<div class='carousel-container'>
                                        <div class='owl-carousel owl-theme'>";
                            }
    
                            $name = $row["name"];
                            $link = $row["link"];
                            if($link!=null){
                                $url = parse_url($link);
                                $host = $url["host"];
                                $host_parts = explode(".", $host);
                                if (count($host_parts) >= 3 && $host_parts[0] === "www") {
                                    $domain = $host_parts[1];
                                } else {
                                    $domain = $host_parts[0];
                                }
                            }
                            echo "<div class='item'>
                                    <div class='ratio ratio-1x1 rounded-circle overflow-hidden mb-2'><img src='" . image($row["image"], "history", 175, 175) . "' alt='$name' class='object-fit-cover'></div>
                                    <h5 class='text-center'>$name</h5>
                                    <div class='d-flex justify-content-around'>
                                        <a href='mailto:".$row["email"]."' class='link-body-emphasis'><i class='fa-solid fa-envelope'></i></a>";
                            print ($link!=null)?"<a href='".$link."' class='link-body-emphasis'><i class='fa-brands fa-$domain'></i></a>" : null;
                            echo "</div>
                                </div>";
                            $prev_team = $row["team_id"];
                        }
                        if ($prev_team !== null) {
                            echo "</div></div></section>";
                        }
                    }else{
                        echo "<h4 class='text-center'>No crews</h4>";
                    }
                } catch (PDOException $e) {
                    echo "Internal error: " . $e;
                }
            ?>
            <hr>
        </article>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<?php include_once "php/scripts.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            margin: 25,
            stagePadding: 75,
            responsive:{
                0:{
                    items: 1,
                    loop:true
                },
                375:{
                    items: 2,
                    loop:true
                },
                576:{
                    items: 3,
                    loop:true
                },
                768:{
                    items: 4,
                    mouseDrag: false
                },
                992:{
                    items: 5,
                    mouseDrag: false
                },
                1200:{
                    items: 6,
                    mouseDrag: false
                }
            }
        })
    })
</script>
</html>