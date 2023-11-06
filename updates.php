<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/user-head.php";
    ?>
    <title>Stay Updated | Team Srijan - Birla Institute of Technology, Mesra</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once "php/header.php"; ?>
    <main class="flex-grow-1 bg-body-tertiary">
        <article class="container-xxl">
            <?php
                $page = "updates";
                include_once "php/page.php";
                include_once "php/config.php";
                include_once "php/pagination-1.php";
                $sql = $conn->prepare("SELECT image, title, body, date, link FROM updates ORDER BY date DESC LIMIT ?, 10");
                $sql->bindParam(1, $offset, PDO::PARAM_INT);
                try{
                    $sql->execute();
                    if($sql->rowCount()>0){
                        $i = 1;
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                            $link = $row["link"];
                            echo "<section class='row gy-3";
                            print ($i++%2==0)?" flex-row-reverse":null;
                            echo "'><div class='col-sm-6 col-md-5 col-lg-4'>";
                            print ($link!=null)?"<a href='".$link."'":"<div";
                            echo " class='ratio ratio-16x9'><img src='".image($row["image"], "updates", 480, 270)."' alt='".$row["title"]."' class='object-fit-cover rounded'>";
                            print ($link!=null)?"</a>":"</div>";
                            echo "</div>
                                <div class='col-sm-6 col-md-7 col-lg-8'>
                                    <h3>".$row["title"]."</h3>
                                    <span>".(new DateTime($row["date"]))->format("j M Y")."</span>
                                    <p>".$row["body"]."</p>";
                            print ($link!=null)?"<a href='".$link."' class='btn btn-dark'><span>Read more</span><i class='fa-solid fa-chevron-right ms-2'></i></a>":null;
                            echo "</div></section><hr>";
                        }
                    }else{
                        echo "<h6 class='text-center'>No updates for now.</h6><hr>";
                    }
                }catch(PDOException $e){
                    echo "<h6 class='text-center'>Engine is broken</h6><hr>";
                }

                $sql = $conn->prepare("SELECT COUNT(*) FROM updates");
                include_once "php/pagination-2.php";
            ?>
        </article>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<?php include_once "php/scripts.php"; ?>
</html>