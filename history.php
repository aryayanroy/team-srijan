<?php
    include_once "php/session.php";
    include_once "php/admin.php";
    include_once "php/imagekit-config.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $image = time();
        $upload = upload($_FILES["image"]["tmp_name"], $image, "history");
        if($upload==true){
            $sql = $conn->prepare("INSERT INTO history (image, year, overview) VALUES (?, ?, ?)");
            $sql->bindParam(1, $image, PDO::PARAM_INT);
            $sql->bindParam(2, $_POST["year"], PDO::PARAM_INT);
            $sql->bindParam(3, $_POST["overview"], PDO::PARAM_STR);
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
        $sql = $conn->prepare("DELETE FROM history WHERE id = ?");
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
    <title>History | Team Srijan</title>
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
                            <a href="admin-updates" class="nav-link">General</a>
                            <a href="admin-sponsors" class="nav-link">Sponsorship</a>
                            <a href="admin-milestones" class="nav-link active">Legacy</a>
                            <a href="add" class="nav-link">Admins</a>
                        </nav>
                    </aside>
                </div>
                <div class="col-9">
                    <article class="p-3 border bg-white rounded">
                        <h3 class="pb-2 border-bottom">Legacy</h3>
                        <nav class="nav nav-underline nav-fill">
                            <a href="admin-updates" class="nav-link">Milestones</a>
                            <a href="admin-garage" class="nav-link">Garage</a>
                            <a href="admin-competitions" class="nav-link">Competitions</a>
                            <a href="admin-overview" class="nav-link active">Overview</a>
                            <a href="admin-crews" class="nav-link">Crews</a>
                        </nav>
                        <nav class="nav nav-underline nav-fill mt-2">
                            <a href="admin-overview" class="nav-link">Overview</a>
                            <a href="history" class="nav-link active">History</a>
                        </nav>
                        <div class="row my-3">
                            <div class="col-2"><button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#add-new"><i class="fa-solid fa-plus me-2"></i><span>Add new</span></button></div>
                            <div class="col-10">
                                <?php include_once "php/response-2.php"; ?>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tr class="text-center">
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Year</th>
                                <th>Overview</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                include_once "php/pagination-1.php";
                                $sql = $conn->prepare("SELECT * FROM history ORDER BY year DESC LIMIT ?, 10");
                                $sql->bindParam(1, $offset, PDO::PARAM_INT);
                                try{
                                    $sql->execute();
                                    if($sql->rowCount()>0){
                                        $i = 1;
                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                            echo "<tr>
                                                <td class='text-center'>".$i++."</td>
                                                <td class='text-center'><img src='".image($row["image"], "history", 96, 54)."' alt='".$row["year"]."'></td>
                                                <td class='text-end'>".$row["year"]."</td>
                                                <td>".$row["overview"]."</td>
                                                <td class='text-center'><button type='button' class='btn btn-link link-danger delete-btn' value='".$row["id"]."'><i class='fa-solid fa-trash'></i></button></td>
                                            </tr>";
                                        }
                                    }else{
                                        echo "<tr><td colspan='5' class='text-center'>No data for now.</td><tr>";
                                    }
                                }catch(PDOException $e){
                                    echo "<tr><td colspan='5' class='text-center'>Internal error: ".$e."</td><tr>";
                                }
                            ?>
                        </table>
                        <?php
                            $sql = $conn->prepare("SELECT COUNT(*) FROM history");
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
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating">
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                        <label for="image">Image images (16x9)</label>
                    </div>
                    <div class="form-floating my-3">
                        <select id="year" name="year" class="form-select">
                        <?php
                            for($y=2007; $y<=date("Y"); $y++){
                                echo "<option value=".$y.">".$y."</option>";
                            }
                        ?>
                        </select>
                        <label for="year">Year</label>
                    </div>
                    <div class="form-floating">
                        <textarea id="overview" name="overview" class="form-control" placeholder="Overview" style="height: 100px; resize:none" maxlength="1000" autocomplete="off" required></textarea>
                        <label for="overview">Overview</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary flex-grow-1">Save</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/public/js/admin.js"></script>
</html>