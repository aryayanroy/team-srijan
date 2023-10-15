<?php
    include_once "php/session.php";
    include_once "php/admin.php";
    include_once "php/imagekit-config.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $image = time();
        $upload = $imageKit->uploadFile([
            "file" => fopen($_FILES["image"]["tmp_name"], "r"),
            "fileName" => $image,
            "useUniqueFileName" => false,
            "folder" => "history"
        ]);
        $status = $upload->responseMetadata["statusCode"];
        $response = array("status" => false, "message" => "No response.");
        if($upload->error == null && $status == 200){
            $sql = $conn->prepare("INSERT INTO crews (image, year, name, team, email, link) VALUES (?, ?, ?, ?, ?, ?)");
            $sql->bindParam(1, $image, PDO::PARAM_INT);
            $sql->bindParam(2, $_POST["year"], PDO::PARAM_INT);
            $sql->bindParam(3, $_POST["name"], PDO::PARAM_STR);
            $sql->bindParam(4, $_POST["team"], PDO::PARAM_INT);
            $sql->bindParam(5, $_POST["email"], PDO::PARAM_STR);
            $sql->bindParam(6, $_POST["link"], PDO::PARAM_STR);
            try{
                $sql->execute();
                $response["status"] = true;
                $response["message"] = "Successfully added.";
            }catch(PDOException $e){
                $response["message"] = "Couldn't record data: ".$e;
            }
        }else{
            $response["message"] = "Couldn't upload image. Error code: ".$status;
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
    <title>Crews | Team Srijan</title>
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
                            <a href="admins" class="nav-link">Admins</a>
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
                            <a href="admin-overview" class="nav-link">Overview</a>
                            <a href="admin-crews" class="nav-link active">Crews</a>
                        </nav>
                        <nav class="nav nav-underline nav-fill mt-2">
                            <a href="crew" class="nav-link">Basic</a>
                            <a href="admin-crews" class="nav-link active">Crews</a>
                            <a href="teams" class="nav-link">Teams</a>
                        </nav>
                        <div class="row my-3">
                            <div class="col-2"><button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#add-new"><i class="fa-solid fa-plus me-2"></i><span>Add new</span></button></div>
                            <div class="col-10">
                                <?php include_once "php/response-1.php"; ?>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tr class="text-center">
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Year</th>
                                <th>Team</th>
                                <th>Email</th>
                                <th>Social Media</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                $sql = $conn->prepare("SELECT * FROM crews ORDER BY name");
                                try{
                                    $sql->execute();
                                    if($sql->rowCount()>0){
                                        $i = 1;
                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                            $team = $row["team"];
                                            $image = $imageKit->url(
                                                [
                                                    "path" => "history/".$row["image"],
                                                    "transformation" => [
                                                        [
                                                            "format" => "webp",
                                                            "width" => "54",
                                                            "height" => "54"
                                                        ]
                                                ]
                                            ]);
                                            echo "<tr>
                                                <td class='text-center'>".$i++."</td>
                                                <td><img src='".$image."' alt='".$row["name"]."'></td>
                                                <td>".$row["name"]."</td>
                                                <td>K-".$row["year"]."</td>
                                                <td></td>
                                                <td><a href='mailto:".$row["email"]."' target='_blank'>".$row["email"]."</a></td>
                                                <td><a href='".$row["link"]."' target='_blank'>".$row["link"]."</a></td>
                                                <td class='text-center'><button type='button' class='btn btn-danger btn-sm data-delete' value='".$row["id"]."'><i class='fa-solid fa-trash'></i></button></td>
                                            </tr>";
                                        }
                                    }else{
                                        echo "<tr><td colspan='5' class='text-center'>No crew member.</td><tr>";
                                    }
                                }catch(PDOException $e){
                                    echo "<tr><td colspan='5' class='text-center'>Internal error: ".$e."</td><tr>";
                                }
                            ?>
                        </table>
                    </article>
                </div>
            </div>
        </div>
    </main>
    <?php include_once "php/footer.php"; ?>
    <!-- Off Canvas -->
    <div id="add-new" class="modal fade">
        <div class="modal-dialog">
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="form-floating">
                                <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                                <label for="image">Image images (1x1)</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <select id="year" name="year" class="form-select">
                                <?php
                                    for($y=7; $y<=date("y"); $y++){
                                        echo "<option value=".$y.">K-".$y."</option>";
                                    }
                                ?>
                                </select>
                                <label for="year">Year</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off" spellcheck="false" required>
                                <label for="name">Full name</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <select id="team" name="team" class="form-select">
                                    <?php
                                        foreach($teams as $key => $value){
                                            echo "<option value=".$key.">".$value."</option>";
                                        }
                                    ?>
                                </select>
                                <label for="team">Team</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email" autocomplete="off" spellcheck="false" required>
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <input type="text" id="link" name="link" class="form-control" placeholder="Link" autocomplete="off" spellcheck="false">
                                <label for="link">Social Media</label>
                            </div>
                        </div>
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
<script src="assets/public/js/delete.js"></script>
</html>