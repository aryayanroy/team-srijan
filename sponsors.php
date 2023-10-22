
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
                include_once "php/imagekit-config.php";
                $data = json_decode(file_get_contents("json/pages.json"), true);
                $sponsors = $data["sponsors"];
            ?>
            <section class="hero-image position-relative" style="background-image: url(<?php echo image($sponsors["hero"], "heros", 1320, 742); ?>)">
                <h1 class="mb-0 position-absolute top-50 start-50 translate-middle">Sponsors</h1>
            </section>
            <p></p>
            <hr>
            <section>
                <h2 class="text-center">Title Sponsors</h2>
            </section>
            <hr>
            <section>
                <h2 class="text-center">Title Sponsors</h2>
            </section>
            <hr>
        </article>
    </main>
    <footer class="navbar bg-body-secondary">
        <div class="container-xl flex-column flex-sm-row">
            <nav class="nav">
                <a href="#" class="nav-link link-body-emphasis"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="nav-link link-body-emphasis"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="nav-link link-body-emphasis"><i class="fa-brands fa-linkedin"></i></a>
            </nav>
            <small class="text-body-emphasis">© 2023 Team Srijan</small>
        </div>
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>