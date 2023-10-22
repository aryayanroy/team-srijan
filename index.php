<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/user-head.php";
    ?>
    <title>Team Srijan | Our Hearts Don't Beat. They Rev...</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <main class="flex-grow-1 bg-body-tertiary">
        <?php include_once "php/header.php"; ?>
        <article class="container-xxl">
            <section id="hero-carousel" class="carousel slide carousel-container" style="height: 50vh" data-bs-ride="carousel">
                <div class="carousel-inner h-100">
                <?php
                    include_once "php/config.php";
                    include_once "php/imagekit-config.php";
                    $data = json_decode(file_get_contents("json/pages.json"), true);
                    $home = $data["home"];
                    $banners = $home["banners"];
                    $i = 1;
                    foreach($banners as $banner){
                        echo "<div class='carousel-item h-100";
                        if($i==1){
                            echo " active";
                        }
                        echo "'><img src='".image($banner, "banners", 1000, 562)."' alt='Banner ".$i++."' class='w-100 h-100 object-fit-cover'>
                        </div>";
                    }
                ?>
                </div>
                <button type="button" class="carousel-control-prev" data-bs-target="#hero-carousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button type="button" class="carousel-control-next" data-bs-target="#hero-carousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                <div class="carousel-indicators">
                    <?php
                        $i = 0;
                        while($i<count($banners)){
                            echo "<button type='button' data-bs-target='#hero-carousel' data-bs-slide-to='".$i++."'";
                            if($i == 1){
                                echo " class='active'";
                            }
                            echo "></button>";
                        }
                    ?>
                </div>
            </section>
                <?php
                    $alert = $home["alert"];
                    if($alert["message"]!=null){
                        echo "<hr>
                            <section>
                                <div class='alert alert-primary py-2'>".$alert["message"];
                        if($alert["link"]!=null){
                            if($alert["ref"]==null){
                                $ref = "Read more";
                            }else{
                                $ref = $alert["ref"];
                            }
                            echo " <a href='".$alert["link"]."'>".$ref."</a></div>";
                        }
                        echo "</div>
                            </section>";
                    }
                ?>
            <hr>
            <section>
                <h2>Updates</h2>
                <?php
                    $sql = $conn->prepare("SELECT image, title, body, date, link FROM updates ORDER BY id DESC LIMIT 10");
                    try{
                        $sql->execute();
                        if($sql->rowCount()>0){
                            echo "<div class='carousel-container'>
                                    <div id='updates-carousel' class='owl-carousel owl-theme'>";
                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                $link = $row["link"];
                                echo "<div class='item'>
                                        <div class='card'>
                                            <div class='ratio ratio-16x9'><img src='".image($row["image"], "updates", 800, 450)."' alt='".$row["title"]."' class='object-fit-cover'></div>
                                            <div class='card-body'>
                                                <h6 class='card-title text-primary'>".$row["title"]."</h6>
                                                <p class='card-text'>".$row["body"]."</p>
                                                <div class='small'>".(new DateTime($row["date"]))->format("j M Y")."</div>";
                                            if($link!=null){
                                                echo "<div class='mt-3'><a href='".$link."' class='btn btn-dark btn-sm' target='_blank'>Read more</a></div>";
                                            }
                                            echo "</div>
                                        </div>
                                    </div>";
                                ;
                            }
                            echo "</div>
                            </div>";
                        }else{
                            echo "<h6 class='text-center'>No updates for now.</h6><hr>";
                        }
                    }catch(PDOException $e){
                        echo "<div>Couldn't load updates: ".$e."</div>";
                    }
                ?>
                <div class="text-end"><a href="updates" class="link-underline link-underline-opacity-0 link-underline-opacity-100-hover"><span>Explore All Updates</span><i class="fa-solid fa-angles-right ms-1"></i></a></div>
            </section>
            <hr>
            <section class="row gy-3">
                <?php
                    $legacy = $home["legacy"];
                    foreach($legacy as $key => $value){
                        echo "<div class='col-sm-4'>
                            <h1 class='mb-0 text-center'>".$value."</h1>
                            <h5 class='mb-0 text-center'>".$key."</h5>
                        </div>";
                    }
                ?>
            </section>
            <hr>
            <?php
                $home = $data["home"];
            ?>
            <section class="hero-image position-relative" style="background-image: url(<?php echo image($home["hero"], "heros", 1320, 742); ?>)">
                <div class="mb-0 position-absolute top-50 start-50 translate-middle">
                    <h1 class="text-center"><?php echo $home["text"]; ?></h1>
                    <p class="mb-0 text-center"><?php echo $home["overview"]; ?></p>
                </div>
            </section>
            <hr>
            <?php
                unset($data["home"], $data["updates"], $data["sponsors"]);
                $keys = array_keys($data);
                shuffle($keys);
                foreach ($keys as $i => $key) {
                    echo "<section>
                            <div class='row gy-3".($i%2==0?null:" flex-row-reverse")."'>
                                <div class='col-sm-6 col-md-5 col-lg-4'>
                                    <a href='".$key."' class='ratio ratio-4x3'><img src='".image($data[$key]["hero"], "heros", 416, 312)."' alt='".ucfirst($key)."' class='object-fit-cover'></a>
                                </div>
                                <div class='col-sm-6 col-md-7 col-lg-8'>
                                    <h2><a href='".$key."' class='text-decoration-none'>".ucfirst($key)."</a></h2>
                                    <p>".$data[$key]["overview"]."</p>
                                    <a href='".$key."' class='link-underline link-underline-opacity-0 link-underline-opacity-100-hover'><span>Explore more</span><i class='fa-solid fa-angles-right ms-1'></i></a>
                                </div>
                            </div>
                        </section>
                        <hr>";
                }
            ?>
            <section>
                <h2>Sponsors</h2>
                <?php
                    $sql = $conn->prepare("SELECT image, name FROM sponsors ORDER BY tier LIMIT 10");
                    try{
                        $sql->execute();
                        if($sql->rowCount()>0){
                            echo "<div class='carousel-container'>
                                    <div id='sponsors-carousel' class='owl-carousel owl-theme'>";
                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                echo "<div class='item'>
                                        <div class='ratio ratio-1x1'><img src='".image($row["image"], "sponsors", 143, 143)."' alt='".$row["name"]."' class='object-fit-cover'></div>
                                    </div>";
                                ;
                            }
                            echo "</div>
                            </div>";
                        }else{
                            echo "<h6 class='text-center'>No sponsors for now.</h6><hr>";
                        }
                    }catch(PDOException $e){
                        echo "<div>Couldn't load sponsors: ".$e."</div>";
                    }
                ?>
            </section>
            <hr>
        </article>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
    $(document).ready(function(){
        $("#updates-carousel").owlCarousel({
            loop: true,
            autoplay:true,
            margin: 10,
            autoplayTimeout:5000,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items: 1,
                },
                375:{
                    items: 1,
                    stagePadding: 50
                },
                576:{
                    items: 2
                },
                768:{
                    items: 2,
                    stagePadding: 75,
                    mouseDrag: false
                },
                992:{
                    items: 3,
                    mouseDrag: false
                },
                1200:{
                    items: 3,
                    stagePadding: 100,
                    mouseDrag: false
                }
            }
        })
        $("#sponsors-carousel").owlCarousel({
            loop: true,
            autoplay:true,
            margin: 10,
            autoplayTimeout:1000,
            responsive:{
                0:{
                    items: 2,
                    stagePadding: 10
                },
                320:{
                    items: 3,
                    stagePadding: 20
                },
                375:{
                    items: 4,
                    stagePadding: 25
                },
                425:{
                    items: 5,
                    stagePadding: 30
                },
                768:{
                    items: 7,
                    stagePadding: 40,
                    mouseDrag: false
                },
                1024:{
                    items: 8,
                    stagePadding: 50,
                    mouseDrag: false
                },
            }
        })
    })
</script>
</html>