<?php
    include_once "php/session.php";
    include_once "php/admin.php";

    $json = "json/pages.json";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");
        $data = json_decode(file_get_contents($json), true);
        $data["home"]["legacy"] = [
            $_POST["param-1"] => $_POST["value-1"],
            $_POST["param-2"] => $_POST["value-2"],
            $_POST["param-3"] => $_POST["value-3"]
        ];
        if(file_put_contents($json, json_encode($data))) {
            $response["status"] = true;
            $response["message"] = "Legacy updated successfully.";
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
        include_once "php/head.php";
        include_once "php/admin-head.php";
    ?>
    <title>Legacy - Home | Team Srijan</title>
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
                            <a href="banners" class="nav-link">Banners</a>
                            <a href="alert" class="nav-link">Alert</a>
                            <a href="legacy" class="nav-link active">Legacy</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" class="mt-3">
                            <table class="table table-bordered table-striped">
                                <tr class="text-center">
                                    <th>Parameter</th>
                                    <th>Value</th>
                                </tr>
                                <?php
                                    $legacy = json_decode(file_get_contents($json), true)["home"]["legacy"];
                                    foreach($legacy as $key => $value){
                                        $param[] = $key;
                                        $val[] = $value;
                                    }
                                ?>
                                <tr>
                                    <td><input type="text" name="param-1" class="form-control" autocomplete="off" value="<?php echo $param[0]; ?>" required></td>
                                    <td><input type="text" name="value-1" class="form-control" autocomplete="off" value="<?php echo $val[0]; ?>" required></td>
                                </tr>
                                <tr>
                                    <td><input type="text" name="param-2" class="form-control" autocomplete="off" value="<?php echo $param[1]; ?>" required></td>
                                    <td><input type="text" name="value-2" class="form-control" autocomplete="off" value="<?php echo $val[1]; ?>" required></td>
                                </tr>
                                <tr>
                                    <td><input type="text" name="param-3" class="form-control" autocomplete="off" value="<?php echo $param[2]; ?>" required></td>
                                    <td><input type="text" name="value-3" class="form-control" autocomplete="off" value="<?php echo $val[2]; ?>" required></td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary w-100">Save</button>
                                </div>
                                <div class="col-10">
                                    <?php include_once "php/response-2.php"; ?>
                                </div>
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
</html