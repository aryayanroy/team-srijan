<?php
    include_once "php/session.php";
    include_once "php/admin.php";
    include_once "php/imagekit-config.php";

    $page = "index";
    
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["action"])){
        $action = $_POST["action"];
        $response = array("status" => false, "message" => "No response.");
        if($action == "insert"){
            $sql = $conn->prepare("INSERT INTO pages (page, title, hero, overview)
            VALUES (:page, :title, :hero, :overview)
            ON DUPLICATE KEY UPDATE title = VALUES(title), hero = VALUES(hero), overview = VALUES(overview)");
            $sql->bindParam(1, $_POST["name"], PDO::PARAM_STR);
            $sql->bindParam(2, $_POST["title"], PDO::PARAM_STR);
            $sql->bindParam(3, $_POST["hero"], PDO::PARAM_INT);
            $sql->bindParam(4, $_POST["overview"], PDO::PARAM_STR);
            try{
                $sql->execute();
                $response["status"] = true;
                $response["message"] = "Profile updated successfully";
            }catch(PDOException $e){
                $response["message"] = "Couldn't update profile: ".$e;
            }
        }
        include_once "php/response.php";
    }
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/admin-head.php";
    ?>
    <title>Basic - Home | Team Srijan</title>
</head>
<body class="d-flex flex-column min-vh-100 bg-body-secondary">
    <?php include_once "php/admin-header.php"; ?>
    <main class="flex-grow-1 py-3">
        <div class="container-xxl">
            <div class="row">
                <div class="col-3">
                    <aside class="border bg-white rounded">
                        <nav class="p-3 nav nav-pills flex-column">
                            <a href="personal" class="nav-link">Account</a>
                            <a href="home" class="nav-link active">General</a>
                            <a href="sponsor" class="nav-link">Sponsorship</a>
                            <a href="milestone" class="nav-link">Legacy</a>
                            <a href="admins" class="nav-link">Admins</a>
                        </nav>
                    </aside>
                </div>
                <div class="col-9">
                    <article class="p-3 border bg-white rounded">
                        <h3 class="pb-2 border-bottom">General</h3>
                        <nav class="nav nav-underline nav-fill">
                            <a href="home" class="nav-link active">Home</a>
                            <a href="admin-updates" class="nav-link">Updates</a>
                            <a href="links" class="nav-link">Links</a>
                            <a href="admin-gallery" class="nav-link">Gallery</a>
                        </nav>
                        <nav class="nav nav-underline nav-fill mt-2">
                            <a href="home" class="nav-link active">Basic</a>
                            <a href="banners" class="nav-link">Banners</a>
                            <a href="alert" class="nav-link">Alert</a>
                            <a href="legacy" class="nav-link">Legacy</a>
                        </nav>
                        <?php include_once "php/page-2.php"; ?>
                    </article>
                </div>
            </div>
        </div>
    </main>
    <?php include_once "php/footer.php"; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/public/js/admin.js"></script>
<script>
    $(document).ready(function(){
        function set_data(){
            load_data([], function(response){
                var data = JSON.parse(response);
                if(data["status"]){
                    data = data["message"][0];
                    console.log(data);
                }else{
                    alert(data["message"]);
                }
            });
        }

        set_data();

        $("#input-form").submit(function(e){
            e.preventDefault();
            var form = $(this);
            var data = new FormData(this);
            var btn = find_btn(form);
            submit_multipart(btn, data, 1, function(response){
                var data = JSON.parse(response);
                console.log(response)
                if(data["status"]){
                    set_data();
                }
            })
        })
    })
</script>
</html>