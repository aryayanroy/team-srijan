<?php
    include_once "phps/config.php";
    include_once "phps/imagekit-config.php";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php include_once "phps/links.php"; ?>
    <title>Stay Updated | Team Srijan - Birla Institute of Technology, Mesra</title>
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
                        <li class="nav-item"><a href="updates" class="nav-link active">Updates</a></li>
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
            <?php
                $hero = json_decode(file_get_contents("jsons/heros.json"), true)["updates"];
            ?>
            <section class="hero-image position-relative" style="background-image: url(<?php echo image($hero, "heros", 1320, 660); ?>)">
                <h1 class="mb-0 position-absolute top-50 start-50 translate-middle">Stay Updated</h1>
            </section>
            <hr>
            <?php
                $sql = $conn->prepare("SELECT image, title, body, date, link FROM updates ORDER BY date DESC");
                try{
                    $sql->execute();
                    if($sql->rowCount()>0){
                        $i = 1;
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                            $image = $imageKit->url(
                                [
                                    "path" => "updates/".$row["image"],
                                    "transformation" => [
                                        [
                                            "format" => "webp",
                                            "width" => "480",
                                            "height" => "270"
                                        ]
                                ]
                            ]);
                            $link = $row["link"];
                            echo "<section class='row gy-3";
                            print ($i++%2==0)?" flex-row-reverse":null;
                            echo "'><div class='col-sm-6 col-md-5 col-lg-4'>";
                            print ($link!=null)?"<a href='".$link."'":"<div";
                            echo " class='ratio ratio-16x9'><img src='".$image."' alt='".$row["title"]."' class='w-100 object-fit-cover'>";
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
            ?>
        </article>
    </main>
    <?php include_once "phps/footer.php"; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>