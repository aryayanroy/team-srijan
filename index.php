<?php
    include_once "phps/config.php";
    include_once "phps/imagekit-config.php";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php include_once "phps/links.php"; ?>
    <title>Team Srijan | Our Hearts Don't Beat. They Rev...</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <header class="sticky-top">
        <nav class="navbar navbar-expand-md bg-dark navbar-dark">
            <div class="container-xl">
                <a href="" class="navbar-brand"><img src="assets/public/branding/team-srijan-logo-white.webp" alt="Team Srijan" height=32></a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#header-collapse"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <div id="header-collapse" class="collapse navbar-collapse flex-grow-0">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="garage" class="nav-link">Garage</a></li>
                        <li class="nav-item"><a href="competitions" class="nav-link">Competitions</a></li>
                        <li class="nav-item"><a href="updates" class="nav-link">Updates</a></li>
                        <li class="nav-item dropdown">
                            <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Partners</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a href="sponsors" class="dropdown-item">Sponsors</a></li>
                                <li><a href="crowdfunding" class="dropdown-item">Crowdfunding</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="milestones" class="nav-link">Milestones</a></li>
                        <li class="nav-item dropdown">
                            <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">About</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a href="overview" class="dropdown-item">Overview</a></li>
                                <li><a href="crews" class="dropdown-item">Crews</a></li>
                                <li><a href="gallery" class="dropdown-item">Gallery</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-grow-1 bg-body-tertiary">
        <article class="container-xxl">
            <section id="hero-carousel" class="carousel slide carousel-container" style="height: 50vh" data-bs-ride="carousel">
                <div class="carousel-inner h-100">
                <?php
                    $banners = json_decode(file_get_contents("jsons/banners.json"), true);
                    $i = 1;
                    foreach($banners as $banner){
                        $image = $imageKit->url(
                            [
                                "path" => "banners/".$banner,
                                "transformation" => [
                                    [
                                        "format" => "webp",
                                        "width" => "1000",
                                        "height" => "562"
                                    ]
                            ]
                        ]);
                        echo "<div class='carousel-item h-100";
                        if($i==1){
                            echo " active";
                        }
                        echo "'><img src='".$image."' alt='Banner ".$i++."' class='w-100 h-100 object-fit-cover'>
                        </div>";
                    }
                ?>
                </div>
                <button type="button" class="carousel-control-prev" data-bs-target="#hero-carousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button type="button" class="carousel-control-next" data-bs-target="#hero-carousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="2"></button>
                </div>
            </section>
                <?php
                    $alert = json_decode(file_get_contents("jsons/alert.json"), true);
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
                    $sql = $conn->prepare("SELECT image, title, body, date, link FROM updates ORDER BY date DESC LIMIT 10");
                    try{
                        $sql->execute();
                        if($sql->rowCount()>0){
                            echo "<div class='carousel-container'>
                                <div class='owl-carousel owl-theme'>";
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
                <div class="col-sm-4">
                    <h1 class="mb-0 text-center">35+</h1>
                    <h5 class="mb-0 text-center">Awards</h5>
                </div>
                <div class="col-sm-4">
                    <h1 class="mb-0 text-center">13</h1>
                    <h5 class="mb-0 text-center">Competitions</h5>
                </div>
                <div class="col-sm-4">
                    <h1 class="mb-0 text-center">8</h1>
                    <h5 class="mb-0 text-center">Generations</h5>
                </div>
            </section>
            <hr>
            <section>
                <div class="row gy-3">
                    <div class="col-sm-6 col-md-5 col-lg-4">
                        <a href="#" class="ratio ratio-4x3"><img src="uploads/images/clement-delacre-M5s9Ffs1KqU-unsplash.jpg" alt="" class="object-fit-cover"></a>
                    </div>
                    <div class="col-sm-6 col-md-7 col-lg-8">
                        <h2><a href="#" class="text-decoration-none">Who Are We?</a></h2>
                        <p>Take a look at the cars and trikes we’ve designed and fabricated since 2010- the year it all started. We took these cars to various national competitions and have won accolades. Till now, all of our cars were internal combustion engine cars. The world is changing and is taking a shift towards a more sustainable and greener future. We at IITG Racing embrace this change and are planning to shift our manufacturing to wholly electric in the next couple of years.</p>
                        <a href="updates" class="link-underline link-underline-opacity-0 link-underline-opacity-100-hover"><span>Explore Garage</span><i class="fa-solid fa-angles-right ms-1"></i></a>
                    </div>
                </div>
            </section>
            <hr>
            <section>
                <div class="row gy-3 flex-row-reverse">
                    <div class="col-sm-6 col-md-5 col-lg-4">
                        <a href="#" class="ratio ratio-4x3"><img src="uploads/images/nicolas-peyrol-tU8bnQV80Jc-unsplash.jpg" alt="" class="object-fit-cover"></a>
                    </div>
                    <div class="col-sm-6 col-md-7 col-lg-8">
                        <h2><a href="#" class="text-decoration-none">Our Garage</a></h2>
                        <p>Take a look at the cars and trikes we’ve designed and fabricated since 2010- the year it all started. We took these cars to various national competitions and have won accolades. Till now, all of our cars were internal combustion engine cars. The world is changing and is taking a shift towards a more sustainable and greener future. We at IITG Racing embrace this change and are planning to shift our manufacturing to wholly electric in the next couple of years.</p>
                        <a href="updates" class="link-underline link-underline-opacity-0 link-underline-opacity-100-hover"><span>Explore Garage</span><i class="fa-solid fa-angles-right ms-1"></i></a>
                    </div>
                </div>
            </section>
            <hr>
            <section>
                <div class="row gy-3">
                    <div class="col-sm-6 col-md-5 col-lg-4">
                        <a href="#" class="ratio ratio-4x3"><img src="uploads/images/philip-myrtorp-sBl8QD-CGGQ-unsplash.jpg" alt="" class="object-fit-cover"></a>
                    </div>
                    <div class="col-sm-6 col-md-7 col-lg-8">
                        <h2><a href="#" class="text-decoration-none">Crowdfunding</a></h2>
                        <p>Take a look at the cars and trikes we’ve designed and fabricated since 2010- the year it all started. We took these cars to various national competitions and have won accolades. Till now, all of our cars were internal combustion engine cars. The world is changing and is taking a shift towards a more sustainable and greener future. We at IITG Racing embrace this change and are planning to shift our manufacturing to wholly electric in the next couple of years.</p>
                        <a href="updates" class="link-underline link-underline-opacity-0 link-underline-opacity-100-hover"><span>Explore Garage</span><i class="fa-solid fa-angles-right ms-1"></i></a>
                    </div>
                </div>
            </section>
            <hr>
            <section>
                <h2>Sponsors</h2>
                <a href="#"><img src="uploads/images/sponsors.png" alt="" class="w-100"></a>
            </section>
            <hr>
        </article>
    </main>
    <?php include_once "phps/footer.php"; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 10,
            autoplay:true,
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
    })
</script>
</html>