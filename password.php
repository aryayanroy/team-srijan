<?php
    include_once "php/session.php";
    include_once "php/admin.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");

        $password = password_hash($_POST["new-password"],PASSWORD_DEFAULT);
        $sql = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $sql->bindParam(1, $password, PDO::PARAM_STR);
        $sql->bindParam(2, $id, PDO::PARAM_INT);
        try{
            $sql->execute();
            $response["status"] = true;
            $response["message"] = "Password changed successfully.";
        }catch(PDOException $e){
            $response["message"] = "Couldn't change the password: ".$e;
        }
    }

    include_once "php/response.php";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?php
        include_once "php/head.php";
        include_once "php/admin-head.php";
    ?>
    <title>Passoword | Team Srijan</title>
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
                            <a href="home" class="nav-link">General</a>
                            <a href="sponsor" class="nav-link">Sponsorship</a>
                            <a href="milestone" class="nav-link">Legacy</a>
                            <a href="admins" class="nav-link">Admins</a>
                        </nav>
                    </aside>
                </div>
                <div class="col-9">
                    <article class="p-3 border bg-white rounded">
                        <h3 class="pb-2 border-bottom">Account</h3>
                        <nav class="nav nav-underline nav-fill mb-3">
                            <a href="personal" class="nav-link">Personal</a>
                            <a href="password" class="nav-link active">Passoword</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" id="input-form" class="row g-3">
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
<script>
    $(document).ready(function(){
        var form = $("#input-form");
        form.submit(function(e){
            e.preventDefault();
            var password = $("#new-password").val();
            if(password == $("#verify-password").val()){
                var button = $(this).find("button[type=submit]");
                submit_urlencoded(button, $(this).serializeArray(), "update", function(response){
                    var data = JSON.parse(response);
                    alert(data["message"]);
                    if(data["status"]){
                        form.trigger("reset")
                    }
                })
            }else{
                alert("The passwords didn't matched!")
            }
        })
    })
</script>
</html>