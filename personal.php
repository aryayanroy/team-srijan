<?php
    include_once "php/session.php";
    include_once "php/config.php";
    
    $sql = $conn->prepare("SELECT name, email FROM admins WHERE id = ?");
    $sql->bindParam(1, $id, PDO::PARAM_INT);
    try{
        $sql->execute();
        if($sql->rowCount()==1){
            $admin = $sql->fetch(PDO::FETCH_NUM);
            $name = $admin[0];
        }else{
            header("Location: logout");
        }
    }catch(PDOException $e){
        header("Location: logout");
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");
        $sql = $conn->prepare("UPDATE admins SET name = ? WHERE id = ?");
        $sql->bindParam(1, $_POST["name"], PDO::PARAM_STR);
        $sql->bindParam(2, $id, PDO::PARAM_INT);
        try{
            $sql->execute();
            $response["status"] = true;
            $response["message"] = "Profile updated successfully.";
        }catch(PDOException $e){
            $response["message"] = "Coudn't update profile: ".$e;
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
    <title>Personal | Team Srijan</title>
</head>
<body class="d-flex flex-column min-vh-100 bg-body-secondary">
    <?php include_once "php/admin-header.php"; ?>
    <main class="flex-grow-1 py-3">
        <div class="container-xxl">
            <div class="row">
                <div class="col-3">
                    <aside class="border bg-white rounded">
                        <nav class="p-3 nav nav-pills flex-column">
                            <a href="personal" class="nav-link active">Account</a>
                            <a href="admin-updates" class="nav-link">General</a>
                            <a href="admin-sponsors" class="nav-link">Sponsorship</a>
                            <a href="admin-milestones" class="nav-link">Legacy</a>
                            <a href="admins" class="nav-link">Admins</a>
                        </nav>
                    </aside>
                </div>
                <div class="col-9">
                    <article class="p-3 border bg-white rounded">
                        <h3 class="pb-2 border-bottom">Account</h3>
                        <nav class="nav nav-underline nav-fill mb-3">
                            <a href="personal" class="nav-link active">Personal</a>
                            <a href="password" class="nav-link">Passoword</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" class="row g-3">
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Name" spellcheck="false" autocomplete="off" value="<?php echo $admin[0];?>" required>
                                    <label for="name">Full name</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="email" id="email" class="form-control" placeholder="Email" value="<?php echo $admin[1];?>" disabled required>
                                    <label for="email">Email address</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary w-100">Save</button>
                            </div>
                            <div class="col-9">
                                <?php include_once "php/response-2.php"; ?>
                            </div>
                        </form>
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