<?php
    include_once "php/session.php";
    include_once "php/admin.php";
    include_once "php/imagekit-config.php";

    $page = "home";
    $json = "json/pages.json";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");
        $image = time();
        $upload = upload($_FILES["image"]["tmp_name"], $image, "banners");
        if($upload == true){
            $data = json_decode(file_get_contents($json), true);
            if($data["home"]["banners"] == false){
                $data["home"]["banners"] = [];
            }
            array_push($data["home"]["banners"], $image);
            if(file_put_contents($json, json_encode($data))) {
                $response["status"] = true;
                $response["message"] = "Image uploaded successfully.";
            }else{
                $response["message"] = "Something went wrong.";
            }
        }else{
            $response["message"] = "Couldn't upload image. Error code: ".$upload;
        }
    }
    
    if(isset($_GET["delete"])){
        $response = array("status" => false, "message" => "No response.");
        $data = json_decode(file_get_contents($json));
        $banners = $data->home->banners;
        $key = array_search($_GET["delete"], $banners);
        unset($banners[$key]);
        $data->home->banners = array_values($banners);
        if(file_put_contents($json, json_encode($data))) {
            $response["status"] = true;
            $response["message"] = "Image removed successfully.";
        }else{
            $response["message"] = "Something went wrong.";
        }
    }

    include_once "php/response-1.php";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/links.php";
        include_once "php/admin-links.php";
    ?>
    <title>Banners - Home | Team Srijan</title>
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
                            <a href="admin-home" class="nav-link active">General</a>
                            <a href="admin-sponsors" class="nav-link">Sponsorship</a>
                            <a href="admin-milestones" class="nav-link">Legacy</a>
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
                            <a href="home" class="nav-link">Basic</a>
                            <a href="banners" class="nav-link active">Banners</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" class="row mt-3">
                            <div class="col-4">
                                <div class="form-floating">
                                    <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                                    <label for="image">Image upload (16x9)</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary w-100">Upload</button>
                            </div>
                            <div class="col-6">
                                <?php include_once "php/response-2.php"; ?>
                            </div>
                        </form>
                        <div class="row g-3 mt-1">
                            <?php
                                $images = json_decode(file_get_contents($json))->home->banners;
                                foreach($images as $image){
                                    echo "<div class='col-6'>
                                            <div class='position-relative'>
                                                <div class='ratio ratio-16x9'>
                                                    <img src='".image($image, "banners", 335, 188)."' alt='Banner' class='img-thumbnail object-fit-cover'>
                                                </div>
                                                <button type='button' class='btn btn-sm btn-danger position-absolute top-0 start-100 translate-middle delete-btn' value='".$image."'><i class='fa-solid fa-xmark fa-lg'></i></button>
                                            </div>
                                        </div>"; 
                                    
                                }
                            ?>
                        </div>
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
</html>