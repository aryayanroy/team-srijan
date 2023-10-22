<?php
    include_once "php/session.php";
    include_once "php/admin.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response");
        if($_POST["password"] == $_POST["confirm-password"]){
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
            $sql = $conn->prepare("INSERT INTO admins (name, email, password, parent) VALUES (?, ?, ?, ?)");
            $sql->bindParam(1, $_POST["name"], PDO::PARAM_STR);
            $sql->bindParam(2, $_POST["email"], PDO::PARAM_STR);
            $sql->bindParam(3, $password, PDO::PARAM_STR);
            $sql->bindParam(4, $id, PDO::PARAM_INT);
            try{
                $sql->execute();
                $response["status"] = true;
                $response["message"] = "Successfully added.";
            }catch(PDOException $e){
                $response["message"] = "Couldn't add admin: ".$e;
            }
        }else{
            $response["message"] = "Password's didn't matched";
        }
    }

    if(isset($_GET["delete"])){
        $sql = $conn->prepare("DELETE FROM admins WHERE id = ? AND parent = ?");
        $sql->bindParam(1, $_GET["delete"], PDO::PARAM_INT);
        $sql->bindParam(2, $id, PDO::PARAM_INT);
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
        include_once "php/head.php";
        include_once "php/admin-head.php";
    ?>
    <title>Add Admins | Team Srijan</title>
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
                            <a href="admin-milestones" class="nav-link">Legacy</a>
                            <a href="admins" class="nav-link active">Admins</a>
                        </nav>
                    </aside>
                </div>
                <div class="col-9">
                    <article class="p-3 border bg-white rounded">
                        <h3 class="pb-2 border-bottom">Admins</h3>
                        <nav class="nav nav-underline nav-fill">
                            <a href="add" class="nav-link active">Add Admins</a>
                            <a href="reset" class="nav-link">Reset</a>
                        </nav>
                        <div class="row my-3">
                            <div class="col-2"><button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#add-new"><i class="fa-solid fa-plus me-2"></i><span>Add new</span></button></div>
                            <div class="col-10">
                                <?php include_once "php/response-2.php"; ?>
                            </div>
                        </div>
                        <div class="alert alert-warning">Terminating an account will result in the automatic removal of all associated sub-accounts linked to it.</div>
                        <table class="table table-bordered table-striped">
                            <tr class="text-center">
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                include_once "php/pagination-1.php";
                                $sql = $conn->prepare("SELECT id, name, email FROM admins WHERE parent = ? ORDER BY name LIMIT ?, 10");
                                $sql->bindParam(1, $id, PDO::PARAM_INT);
                                $sql->bindParam(2, $offset, PDO::PARAM_INT);
                                try{
                                    $sql->execute();
                                    if($sql->rowCount()>0){
                                        $i = $offset;
                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                            echo "<tr>
                                                <td class='text-center'>".++$i."</td>
                                                <td>".$row["name"]."</td>
                                                <td>".$row["email"]."</td>
                                                <td class='text-center'><button type='button' class='btn btn-link link-danger delete-btn' value='".$row["id"]."'><i class='fa-solid fa-trash'></i></button></td>
                                            </tr>";
                                        }
                                    }else{
                                        echo "<tr><td colspan='4' class='text-center'>No updates for now.</td><tr>";
                                    }
                                }catch(PDOException $e){
                                    echo "<tr><td colspan='4' class='text-center'>Internal error: ".$e."</td><tr>";
                                }
                            ?>
                        </table>
                        <?php
                            $sql = $conn->prepare("SELECT COUNT(*) FROM admins WHERE parent = ?");
                            $sql->bindParam(1, $id, PDO::PARAM_INT);
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off" spellcheck="false" required>
                                    <label for="name">Full name</label>
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
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required>
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="password" id="confirm-password" name="confirm-password" class="form-control" placeholder="Password" autocomplete="off" required>
                                    <label for="confirm-password">Confirm password</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary flex-grow-1">Add</button>
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