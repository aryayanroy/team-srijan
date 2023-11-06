<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/user-head.php";
    ?>
    <title>Our Garage | Team Srijan - Birla Institute of Technology, Mesra</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once "php/header.php"; ?>
    <main class="flex-grow-1 bg-body-tertiary">
        <article class="container-xxl">
            <?php
                $page = "garage";
                include_once "php/page.php";
                include_once "php/config.php";
                include_once "php/pagination-1.php";
                $sql = $conn->prepare("SELECT images, name, overview, specs, achievements FROM garage ORDER BY id DESC LIMIT ?, 10");
                $sql->bindParam(1, $offset, PDO::PARAM_INT);
                try{
                    $sql->execute();
                    if($sql->rowCount()>0){
                        $i = 0;
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                            $name = $row["name"];
                            echo "<section class='row gy-3";
                            print ((++$i)%2==0)? " flex-row-reverse" : null;
                            echo "'>
                            <div class='col-sm-6 col-md-5 col-lg-4'>
                                <div id='car-".$i."-carousel' class='carousel slide' data-bs-ride='carousel'>
                                    <div class='carousel-inner rounded overflow-hidden'>";
                            $images = json_decode($row["images"]);
                            $j = 1;
                            foreach($images as $image){
                                echo "<div class='carousel-item ratio ratio-4x3";
                                print ($j == 1)? " active" : null;
                                echo "'><img src='".image($image, "garage", 416, 312)."' alt='".$name."-".$j++."' class='w-100 object-fit-cover'></div>";
                            }
                            echo "</div>
                                    <button type='button' class='carousel-control-prev' data-bs-target='#car-".$i."-carousel' data-bs-slide='prev'><span class='carousel-control-prev-icon'></span></button>
                                    <button type='button' class='carousel-control-next' data-bs-target='#car-".$i."-carousel' data-bs-slide='next'><span class='carousel-control-next-icon'></span></button>
                                </div>
                            </div>
                            <div class='col-sm-6 col-md-7 col-lg-8'>
                                <h3>".$row["name"]."</h3>
                                <div id='car-".$i."-collapse' class='accordion accordion-flush'>
                                    <div class='accordion-item'>
                                        <button type='button' class='accordion-button collapsed' data-bs-toggle='collapse' data-bs-target='#car-".$i."-overview'>Overview</button>
                                        <div id='car-".$i."-overview' class='accordion-collapse collapse' data-bs-parent='#car-".$i."-collapse'>
                                            <div class='accordion-body'>".$row["overview"]."</div>
                                        </div>
                                    </div>
                                    <div class='accordion-item'>
                                        <button type='button' class='accordion-button collapsed' data-bs-toggle='collapse' data-bs-target='#car-".$i."-specs'>Specifications</button>
                                        <div id='car-".$i."-specs' class='accordion-collapse collapse' data-bs-parent='#car-".$i."-collapse'>
                                            <div class='accordion-body'>
                                                <table class='table table-sm table-striped mb-0'>";
                                            $specs = json_decode($row["specs"], true);
                                            foreach($specs as $spec){
                                               foreach($spec as $param => $value){
                                                    echo "<tr><td>".$param."</td><td>".$value."</td></tr>";
                                               }
                                            }
                                            echo "</td></tr></table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='accordion-item'>
                                        <button type='button' class='accordion-button collapsed' data-bs-toggle='collapse' data-bs-target='#car-".$i."-achivements'>Achievements</button>
                                        <div id='car-".$i."-achivements' class='accordion-collapse collapse' data-bs-parent='#car-".$i."-collapse'>
                                            <div class='accordion-body'>
                                                <ul class='mb-0'>";
                                            $achievements = array_filter(explode(".", $row["achievements"]), "strlen");
                                            foreach ($achievements as $achievement) {
                                                echo "<li>".$achievement.".</li>";
                                            }
                                            echo "</ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <hr>";
                        }
                    }else{
                        echo "<div class='text-center'>No updates for now.</div>";
                    }
                }catch(PDOException $e){
                    echo "<div class='text-center'>Internal error: ".$e."</div>";
                }
                
                $sql = $conn->prepare("SELECT COUNT(*) FROM garage");
                include_once "php/pagination-2.php";
            ?>
            <hr>
        </article>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<?php include_once "php/scripts.php"; ?>
</html>