<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/user-head.php";
    ?>
    <title>Gallery | Team Srijan - Birla Institute of Technology, Mesra</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once "php/header.php"; ?>
    <main class="flex-grow-1 bg-body-tertiary">
        <article class="container-xxl">
            <?php
                $page = "gallery";
                include_once "php/page.php";
                include_once "php/config.php";
                include_once "php/pagination-1.php";
                $sql = $conn->prepare("SELECT image FROM gallery ORDER BY id DESC LIMIT ?, 12");
                $sql->bindParam(1, $offset, PDO::PARAM_INT);
                try{
                    $sql->execute();
                    if($sql->rowCount()>0){
                        $i = 1;
                        echo "<div class='row g-3'>";
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                            echo "<div class='col-sm-6 col-md-4'>
                                <div class='ratio ratio-16x9'><img src='".image($row["image"], "gallery", 312, 175)."' alt='Image ".$i++."' class='img-thumbnail rounded'></div>
                            </div>";
                        }
                        echo "</div>";
                    }else{
                        echo "<h6 class='text-center'>No images</h6><hr>";
                    }
                }catch(PDOException $e){
                    echo "<h6 class='text-center'>Engine is broken: $e</h6><hr>";
                }
                echo "<hr>";
                $sql = $conn->prepare("SELECT COUNT(*) FROM gallery");
                include_once "php/pagination-2.php";
            ?>
        </article>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<?php include_once "php/scripts.php"; ?>
</html>