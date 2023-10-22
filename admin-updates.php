<?php
    include_once "php/session.php";
    include_once "php/admin.php";
    include_once "php/imagekit-config.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");
        $image = time();
        $upload = upload($_FILES["image"]["tmp_name"], $image, "updates");
        if($upload == true){
            $sql = $conn->prepare("INSERT INTO updates (image, title, body, link) VALUES (?, ?, ?, ?)");
            $sql->bindParam(1, $image, PDO::PARAM_INT);
            $sql->bindParam(2, $_POST["title"], PDO::PARAM_STR);
            $sql->bindParam(3, $_POST["body"], PDO::PARAM_STR);
            $sql->bindParam(4, $_POST["link"], PDO::PARAM_STR);
            try{
                $sql->execute();
                $response["status"] = true;
                $response["message"] = "Successfully posted.";
            }catch(PDOException $e){
                $response["message"] = "Couldn't record data: ".$e;
            }
        }else{
            $response["message"] = "Couldn't upload image. Error code: ".$upload;
        }
    }

    if(isset($_GET["delete"])){
        $sql = $conn->prepare("DELETE FROM updates WHERE id = ?");
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
    <?php include_once "php/head.php"; ?>
    <?php include_once "php/admin-head.php"; ?>
    <title>Updates | Team Srijan</title>
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
                            <a href="home" class="nav-link">Home</a>
                            <a href="admin-updates" class="nav-link active">Updates</a>
                            <a href="links" class="nav-link">Links</a>
                            <a href="admin-gallery" class="nav-link">Gallery</a>
                        </nav>
                        <nav class="nav nav-underline nav-fill mt-2">
                            <a href="update" class="nav-link">Basic</a>
                            <a href="admin-updates" class="nav-link active">Updates</a>
                        </nav>
                        <div class="row my-3">
                            <div class="col-2"><button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#add-new"><i class="fa-solid fa-plus me-2"></i><span>Add new</span></button></div>
                            <div class="col-10">
                                <?php include_once "php/response-2.php";; ?>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tr class="text-center">
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Body</th>
                                <th>Date</th>
                                <th>Link</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                include_once "php/pagination-1.php";
                                $sql = $conn->prepare("SELECT * FROM updates ORDER BY id DESC LIMIT ?, 10");
                                $sql->bindParam(1, $offset, PDO::PARAM_INT);
                                try{
                                    $sql->execute();
                                    if($sql->rowCount()>0){
                                        $i = $offset;
                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                            $link = ($row["link"]!=null) ? "<a href='".$row["link"]."' target='_blank'><i class='fa-solid fa-arrow-up-right-from-square'></i></a>": "NA";
                                            echo "<tr>
                                                <td class='text-center'>".++$i."</td>
                                                <td class='text-center'><img src='".image($row["image"], "updates", 96, 54)."' alt='".$row["title"]."' width='96'></td>
                                                <td>".$row["title"]."</td>
                                                <td>".$row["body"]."</td>
                                                <td class='text-end'>".(new DateTime($row["date"]))->format("j M y")."</td>
                                                <td class='text-center'>".$link."</td>
                                                <td class='text-center'><button type='button' class='btn btn-link link-danger delete-btn' value='".$row["id"]."'><i class='fa-solid fa-trash'></i></button></td>
                                            </tr>";
                                        }
                                    }else{
                                        echo "<tr><td colspan='7' class='text-center'>No updates for now.</td><tr>";
                                    }
                                }catch(PDOException $e){
                                    echo "<tr><td colspan='7' class='text-center'>Internal error".$e."</td><tr>";
                                }
                            ?>
                        </table>
                        <?php
                            $sql = $conn->prepare("SELECT COUNT(*) FROM updates");
                            include_once "php/pagination-2.php";
                        ?>
                    </article>
                </div>
            </div>
        </div>
    </main>
    <?php include_once "php/footer.php"; ?>
    <!-- Off Canvas -->
    <div id="add-new" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                            <label for="image">Image upload (16x9)</label>
                        </div>
                        <div class="form-floating my-3">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Title" autocomplete="off" required>
                            <label for="title">Title</label>
                        </div>
                        <div class="form-floating">
                            <textarea id="body" name="body" class="form-control" placeholder="Body" style="height: 100px; resize:none" maxlength="1000" autocomplete="off" required></textarea>
                            <label for="body">Body</label>
                        </div>
                        <div class="form-floating mt-3">
                            <input type="text" id="link" name="link" class="form-control" placeholder="Link" autocomplete="off" spellcheck="false">
                            <label for="link">Link (optional)</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary flex-grow-1">Post update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/public/js/admin.js"></script>
</html>