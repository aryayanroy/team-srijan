<?php
    include_once "php/session.php";
    include_once "php/admin.php";
    include_once "php/imagekit-config.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");
        $csv = explode("\n", trim(file_get_contents($_FILES["specs"]["tmp_name"])));
        foreach($csv as $data){
            $data = explode(",", $data);
            if(count($data)==2){
                $specs[] = [trim($data[0]) => trim($data[1])];
            }
        }
        if(isset($specs)){
            $specs = json_encode($specs);
            $name = time();
            $files = $_FILES["images"]["tmp_name"];
            $count = 0;
            for($i = 0; $i < count($files); $i++){
                $image = $name.$i;
                $upload = upload($files[$i], $image, "garage");
                if($upload == true){
                    $images[] = $image;
                    $count++;
                }
            }
            if(isset($images)){
                $images = json_encode($images);
                $sql = $conn->prepare("INSERT INTO garage (images, name, overview, specs, achievements) VALUES (?, ?, ?, ?, ?)");
                $sql->bindParam(1, $images, PDO::PARAM_STR);
                $sql->bindParam(2, $_POST["name"], PDO::PARAM_STR);
                $sql->bindParam(3, $_POST["overview"], PDO::PARAM_STR);
                $sql->bindParam(4, $specs, PDO::PARAM_STR);
                $sql->bindParam(5, $_POST["achievements"], PDO::PARAM_STR);
                try{
                    $sql->execute();
                    $response["status"] = true;
                    $response["message"] = "Successfully added. Images uploaded: ".$count;
                }catch(PDOException $e){
                    $response["message"] = "Couldn't record data: ".$e;
                }   
            }else{
                $response["message"] = "Couldn't upload any image. Error code: ".$upload;
            }
        }else{
            $response["message"] = "Couldn't read the CSV file.";
        }
    }

    if(isset($_GET["delete"])){
        $response = array("status" => false, "message" => "No response");
        $sql = $conn->prepare("DELETE FROM garage WHERE id = ?");
        $sql->bindParam(1, $_GET["delete"], PDO::PARAM_INT);
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
        include_once "php/head.php";
        include_once "php/admin-head.php";
    ?>
    <title>Garage | Team Srijan</title>
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
                            <a href="home" class="nav-link">General</a>
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
                            <a href="admin-milestones" class="nav-link">Milestones</a>
                            <a href="admin-garage" class="nav-link active">Garage</a>
                            <a href="admin-competitions" class="nav-link">Competitions</a>
                            <a href="admin-overview" class="nav-link">Overview</a>
                            <a href="admin-crews" class="nav-link">Crews</a>
                        </nav>
                        <nav class="nav nav-underline nav-fill mt-2">
                            <a href="garage-basic" class="nav-link">Basic</a>
                            <a href="admin-garage" class="nav-link active">Garage</a>
                        </nav>
                        <div class="row my-3">
                            <div class="col-2"><button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#add-new"><i class="fa-solid fa-plus me-2"></i><span>Add new</span></button></div>
                            <div class="col-10">
                                <?php include_once "php/response-2.php"; ?>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tr class="text-center">
                                    <th>Sl</th>
                                    <th style="min-width: 96px">Image</th>
                                    <th>Car</th>
                                    <th>Overview</th>
                                    <th>Specifications</th>
                                    <th>Achievements</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                                    include_once "php/pagination-1.php";
                                    $sql = $conn->prepare("SELECT * FROM garage ORDER BY id DESC LIMIT ?, 10");
                                    $sql->bindParam(1, $offset, PDO::PARAM_INT);
                                    try{
                                        $sql->execute();
                                        if($sql->rowCount()>0){
                                            $i = $offset;
                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                                echo "<tr>
                                                    <td class='text-center'>".++$i."</td>
                                                    <td>";

                                                $images = json_decode($row["images"], true);
                                                foreach ($images as $image) {
                                                    echo "<div class='ratio ratio-16x9 mt-2'><img src='".image($image, "garage", 96, 54)."' alt='".$row["name"]."'  class='img-thumbnail object-fit-cover'></div>";
                                                }
                                                echo "</td>
                                                    <td>".$row["name"]."</td>
                                                    <td>".$row["overview"]."</td>
                                                    <td>
                                                    <table class='table table-striped table-sm'>";
                                                
                                                foreach ($specs as $data){
                                                    foreach($data as $param => $value){
                                                        echo "<tr>
                                                            <th>".$param."<th>
                                                            <td>".$value."<td>
                                                        </tr>";
                                                    }
                                                }
                                                $specs = json_decode($row["specs"], true);
                                                echo "</table>
                                                    </td>
                                                    <td>
                                                        <ul class='mb-0'>";
                                                $achievements = array_filter(explode(".", $row["achievements"]), "strlen");
                                                foreach ($achievements as $data) {
                                                    echo "<li>".$data.".</li>";
                                                }
                                                echo "</ul>
                                                    </td>
                                                    <td class='text-center'><button type='button' class='btn btn-link link-danger delete-btn' value='".$row["id"]."'><i class='fa-solid fa-trash'></i></button></td>
                                                </tr>";
                                            }
                                        }else{
                                            echo "<tr><td colspan='7' class='text-center'>No updates for now.</td><tr>";
                                        }
                                    }catch(PDOException $e){
                                        echo "<tr><td colspan='7' class='text-center'>Internal error: ".$e."</td><tr>";
                                    }
                                ?>
                            </table>
                            <?php
                                $sql = $conn->prepare("SELECT COUNT(*) FROM garage");
                                include_once "php/pagination-2.php";
                            ?>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </main>
    <?php include_once "php/footer.php"; ?>
    <!-- Off Canvas -->
    <div id="add-new" class="modal fade">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating">
                        <input type="file" id="images" name="images[]" class="form-control" accept="image/*" required multiple>
                        <label for="images">Image images (1x1)</label>
                    </div>
                    <span class="form-text">You can upload multiple images at a time.</span>
                    <div class="form-floating my-3">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off" spellcheck="false" required>
                        <label for="name">Name</label>
                    </div>
                    <div class="form-floating">
                        <textarea id="overview" name="overview" class="form-control" placeholder="Overview" style="height: 100px; resize:none" maxlength="1000" autocomplete="off" required></textarea>
                        <label for="overview">Overview</label>
                    </div>
                    <div class="form-floating mt-3">
                        <input type="file" id="specs" name="specs" class="form-control" accept=".csv" required>
                        <label for="specs">Specifications (.csv File)</label>
                    </div>
                    <span class="form-text">Create a CSV file in a spreadsheet software with two columns: left for parameters and right for values. Avoid including column headings.</span>
                    <div class="form-floating mt-3">
                        <textarea id="achievements" name="achievements" class="form-control" placeholder="Achievements" style="height: 100px; resize:none" maxlength="1000" autocomplete="off" required></textarea>
                        <label for="achievements">Achievements</label>
                    </div>
                    <span class="form-text">Achievements will be displayed as lists. Each sentence will be considered as a list item.</span>
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