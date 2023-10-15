<?php
    include_once "php/session.php";
    include_once "php/admin.php";
    include_once "php/imagekit-config.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $image = time();
        $upload = upload($_FILES["image"]["tmp_name"], $image, "gallery");
        if($upload == true){
            $sql = $conn->prepare("INSERT INTO gallery (image) VALUES (?)");
            $sql->bindParam(1, $image, PDO::PARAM_INT);
            try{
                $sql->execute();
                $response["status"] = true;
                $response["message"] = "Successfully added.";
            }catch(PDOException $e){
                $response["message"] = "Couldn't record data: ".$e;
            }
        }else{
            $response["message"] = "Couldn't upload image. Error code: ".$upload;
        }
    }
    
    if(isset($_GET["delete"])){
        $sql = $conn->prepare("DELETE FROM gallery WHERE id = ?");
        $sql->bindParam(1, $_GET["delete"], PDO::PARAM_INT);
        $response = array("status" => false, "message" => "No response");
        try{
            $sql->execute();
            $response["status"] = true;
            $response["message"] = "Successfully deleted.";
        }catch(PDOException $e){
            $response["message"] = "Couldn't delete: ".$e;
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
    <title>Gallery | Team Srijan</title>
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
                            <a href="admin-updates" class="nav-link active">General</a>
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
                            <a href="home" class="nav-link">Home</a>
                            <a href="admin-updates" class="nav-link">Updates</a>
                            <a href="links" class="nav-link">Links</a>
                            <a href="admin-gallery" class="nav-link active">Gallery</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" class="row g-3 mt-1">
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
                                $sql = $conn->prepare("SELECT * FROM gallery");
                                try{
                                    $sql->execute();
                                    if($sql->rowCount()>0){
                                        $i = 1;
                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                            echo "<div class='col-4'>
                                                <div class='position-relative'>
                                                    <div class='ratio ratio-16x9'>
                                                        <img src='".image($row["image"],"gallery",303,170)."' alt='Image ".$i++."' class='img-thumbnail object-fit-cover'>
                                                    </div>
                                                    <button type='button' class='btn btn-sm btn-danger position-absolute top-0 start-100 translate-middle delete-btn' value='".$row["id"]."'><i class='fa-solid fa-xmark fa-lg'></i></button>
                                                </div>
                                            </div>";
                                        }
                                    }else{
                                        echo "<div class='col-12 text-center'>No images found</div>";
                                    }
                                }catch(PDOException $e){
                                    echo "<div class='col-12'>Internal error: ".$e."</div>";
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