<?php
    include_once "phps/admin/session.php";
    include_once "phps/admin/admin.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if($_POST["new-password"]==$_POST["verify-password"]){
            $password = password_hash($_POST["new-password"],PASSWORD_DEFAULT);
            $sql = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $sql->bindParam(1, $password, PDO::PARAM_STR);
            $sql->bindParam(2, $id, PDO::PARAM_INT);
            try{
                $sql->execute();
                $response = array("status" => true, "message" => "Password changed successfully.");
            }catch(PDOException $e){
                $response = array("status" => false, "message" => "Couldn't change the password.");
            }
        }else{
            $response = array("status" => false, "message" => "Passwords didn't match.");
        }
    }
    include_once "phps/admin/response.php";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php include_once "phps/admin/links.php"; ?>
    <title>Passoword | Team Srijan</title>
</head>
<body class="d-flex flex-column min-vh-100 bg-body-secondary">
    <?php include_once "phps/admin/header.php"; ?>
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
                            <a href="add" class="nav-link">Admins</a>
                        </nav>
                    </aside>
                </div>
                <div class="col-9">
                    <article class="p-3 border bg-white rounded">
                        <h3 class="pb-2 border-bottom">Account</h3>
                        <nav class="nav nav-underline nav-fill mb-3">
                            <a href="personal" class="nav-link">Personal</a>
                            <a href="password" class="nav-link active">Passoword</a>
                            <a href="delete" class="nav-link">Delete</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" class="row g-3">
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="password" id="new-password" name="new-password" class="form-control" placeholder="password" autocomplete="off" required>
                                    <label for="new-password">New password</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="password" id="verify-password" name="verify-password" class="form-control" placeholder="password" autocomplete="off" required>
                                    <label for="verify-password">Verify password</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary w-100">Save</button>
                            </div>
                            <div class="col-9">
                                <?php include_once "phps/admin/response-alert.php"; ?>
                            </div>
                        </form>
                    </article>
                </div>
            </div>
        </div>
    </main>
    <?php include_once "phps/footer.php"; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>