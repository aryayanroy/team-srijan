<?php
    session_start();
    if(isset($_SESSION["id"])){
        header("Location: personal");
        die();
    }
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");
        include_once "php/config.php";
        $sql = $conn->prepare("SELECT id, password FROM admins WHERE email = ?");
        $sql->bindParam(1, $_POST["email"], PDO::PARAM_STR);
        try{
            $sql->execute();
            $count = $sql->rowCount();
            if($count == 1){
                $admin = $sql->fetch(PDO::FETCH_ASSOC);
                if(password_verify($_POST["password"], $admin["password"])){
                    $_SESSION["id"] = $admin["id"];
                    header("Location: personal");
                }else{
                    $response["message"] = "Invalid password.";
                }
            }else if($count == 0){
                $response["message"] = "No account was found.";
            }else{
                $response["message"] = "Multiple accounts found. Please contact the administrator.";
            }
        }catch(PDOException $e){
            $response["message"] = "Internal server error.";
        }
    }

    include_once "php/response-1.php";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php include_once "php/head.php"; ?>
    <?php include_once "php/admin-head.php"; ?>
    <title>Login | Team Srijan</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <header class="sticky-top">
        <nav class="navbar bg-dark">
            <div class="container-xl">
                <a href="/" class="navbar-brand"><img src="assets/public/branding/team-srijan-logo-white.webp" alt="Team Srijan" height=32></a>
            </div>
        </nav>
    </header>
    <main class="flex-grow-1 bg-body-tertiary">
        <article class="container-xxl py-3">
            <section class="my-5">
                <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" class="mx-auto" style="max-width: 300px">
                    <div class="text-center"><img src="assets/public/branding/team-srijan-black-logo.webp" alt="Team Srijan" class="w-25"></div>
                    <div class="form-floating my-3">
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" spellcheck="false" autocomplete="off" required>
                        <label for="email">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="my-3"><button type="submit" class="btn btn-primary w-100">Login</button></div>
                    <?php include_once "php/response-2.php"; ?>
                </form>
            </section>
        </article>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>