<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/user-head.php";
    ?>
    <title>Sponsors | Team Srijan - Birla Institute of Technology, Mesra</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once "php/header.php"; ?>
    <main class="flex-grow-1 bg-body-tertiary">
        <article class="container-xxl">
            <?php
                $page = "sponsors";
                include_once "php/page.php";
                include_once "php/config.php";
                include_once "php/pagination-1.php";
                $sql = $conn->prepare("SELECT tier, name, image, link, description FROM sponsors ORDER BY tier LIMIT ?, 10");
                $sql->bindParam(1, $offset, PDO::PARAM_INT);
                try{
                    $sql->execute();
                    if($sql->rowCount()>0){
                        $tier = 0;
                        $map = ["I", "II", "III", "IV", "V"];
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                            $link = $row["link"];
                            if($tier != $row["tier"]){
                                $i = 1;
                                $tier = $row["tier"];
                                echo "<h3 class='text-center mb-3'><span class='px-2 border border-dark'>Tier ".($map[$tier-1]?? "VI")."</span></h3>";
                            }
                            echo "<section class='row gy-3 pb-3 border-bottom mb-3";
                            print ($i++%2==0)?" flex-row-reverse":null;
                            echo "'>
                                    <div class='col-sm-5 col-md-4 col-lg-3'>
                                        <a href='".$link."'class='ratio ratio-4x3'><img src='".image($row["image"], "sponsors", 306, 229)."' alt='".$row["name"]."' class='object-fit-cover rounded'></a>
                                    </div>
                                    <div class='col-sm-7 col-md-8 col-lg-9'>
                                        <h3><a href='".$link."' class='text-decoration-none'>".$row["name"]."</a></h3>
                                        <p>".$row["description"]."</p>
                                    </div>
                                </section>";
                        }
                    }else{
                        echo "<div class='text-center'>No sponsors for now.</div>";
                    }
                }catch(PDOException $e){
                    echo "<div class='text-center'>Internal error: ".$e."</div>";
                }
                $sql = $conn->prepare("SELECT COUNT(*) FROM sponsors");
                include_once "php/pagination-2.php";
            ?>
        </article>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<?php include_once "php/scripts.php"; ?>
</html>