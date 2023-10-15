<?php
    include_once "php/session.php";
    include_once "php/admin.php";

    $links_json = "json/links.json";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => null);
        if(file_put_contents($links_json, json_encode($_POST))) {
            $response["status"] = true;
            $response["message"] = "Links updated successfully.";
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
        include_once "php/links.php";
        include_once "php/admin-links.php";
    ?>
    <title>Links | Team Srijan</title>
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
                        <nav class="nav nav-underline nav-fill mb-3">
                            <a href="home" class="nav-link">Home</a>
                            <a href="admin-updates" class="nav-link">Updates</a>
                            <a href="links" class="nav-link active">Links</a>
                            <a href="admin-gallery" class="nav-link">Gallery</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                            <table class="table table-bordered table-striped">
                                <tr class="text-center">
                                    <th>Site</th>
                                    <th>Link</th>
                                </tr>
                                <?php
                                    $links = json_decode(file_get_contents($links_json), true);
                                ?>
                                <tr>
                                    <td><label for="facebook">Facebook</label></td>
                                    <td><input type="text" id="facebook" name="facebook" class="form-control" placeholder="https://www.facebook.com/" autocomplete="off" spellcheck="false" value="<?php echo $links["facebook"]; ?>"></td>
                                </tr>
                                <tr>
                                    <td><label for="twitter">Twitter (X)</label></td>
                                    <td><input type="text" id="twitter" name="twitter" class="form-control" placeholder="https://twitter.com/" autocomplete="off" spellcheck="false" value="<?php echo $links["twitter"]; ?>"></td>
                                </tr>
                                <tr>
                                    <td><label for="instagram">Instagram</label></td>
                                    <td><input type="text" id="instagram" name="instagram" class="form-control" placeholder="https://instagram.com/" autocomplete="off" spellcheck="false" value="<?php echo $links["instagram"]; ?>"></td>
                                </tr>
                                <tr>
                                    <td><label for="youtube">YouTube</label></td>
                                    <td><input type="text" id="youtube" name="youtube" class="form-control" placeholder="https://www.youtube.com/" autocomplete="off" spellcheck="false" value="<?php echo $links["youtube"]; ?>"></td>
                                </tr>
                                <tr>
                                    <td><label for="linkedin">LinkedIn</label></td>
                                    <td><input type="text" id="linkedin" name="linkedin" class="form-control" placeholder="https://www.linkedin.com/in/" autocomplete="off" spellcheck="false" value="<?php echo $links["linkedin"]; ?>"></td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col-2"><button type="submit" class="btn btn-primary w-100">Save links</button></div>
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
</html>