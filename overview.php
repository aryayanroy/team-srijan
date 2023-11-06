<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/user-head.php";
    ?>
    <title>Who Are We? | Team Srijan - Birla Institute of Technology, Mesra</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once "php/header.php"; ?>
    <main class="flex-grow-1 bg-body-tertiary">
        <article class="container-xxl">
            <?php
                $page = "overview";
                include_once "php/page.php";
                include_once "php/config.php";
                include_once "php/pagination-1.php";
                $sql = $conn->prepare("SELECT image, year, overview FROM history ORDER BY year DESC LIMIT ?, 10");
                $sql->bindParam(1, $offset, PDO::PARAM_INT);
                try{
                    $sql->execute();
                    if($sql->rowCount()>0){
                        $i = 1;
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                            echo "<section class='row gy-3";
                            print ($i++%2==0)?" flex-row-reverse":null;
                            echo "'>
                                    <div class='col-sm-6 col-md-5 col-lg-4'>
                                        <div class='ratio ratio-4x3'><img src='".image($row["image"], "history", 416, 312)."' alt='Race' class='object-fit-cover rounded'></div>
                                    </div>
                                    <div class='col-sm-6 col-md-7 col-lg-8'>
                                        <h3>2006</h3>
                                        <p>With the planning and budgeting done, the cost of building the vehicle will be a whopping Rs 27lakhs, out of which the college is contributing 6L and the students have managed to get sponsorship commitment of another 6L. As BITOSA Global, we want to support Team Srijan in this initiative and have committed to raise at least 6L for them to match what the college has contributed. This will give them the required runway so that they can go ahead and raise the remaining 9L through other sponsorships.</p>
                                    </div>
                                </section>
                                <hr>";
                        }
                    }else{
                        echo "<h6 class='text-center'>No updates for now.</h6><hr>";
                    }
                }catch(PDOException $e){
                    echo "<h6 class='text-center'>Engine is broken</h6><hr>";
                }

                $sql = $conn->prepare("SELECT COUNT(*) FROM history");
                include_once "php/pagination-2.php";
            ?>
        </article>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<?php include_once "php/scripts.php"; ?>
</html>