<?php
    include_once "php/session.php";
    include_once "php/admin.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");
        $sql = $conn->prepare("INSERT INTO teams (name) VALUES (?)");
        $sql->bindParam(1, $_POST["name"], PDO::PARAM_STR);
        try{
            $sql->execute();
            $response["status"] = true;
            $response["message"] = "Successfully added.";
        }catch(PDOException $e){
            $response["message"] = "Couldn't record data: ".$e;
        }
    }

    if(isset($_GET["delete"])){
        $sql = $conn->prepare("DELETE FROM teams WHERE id = ?");
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
        include_once "php/head.php";
        include_once "php/admin-head.php";
    ?>
    <title>Teams - Crews | Team Srijan</title>
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
                            <a href="admin-crews" class="nav-link">Crews</a>
                            <a href="teams" class="nav-link active">Teams</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" class="row g-3 mt-1">
                            <div class="col-4">
                                <div class="form-floating">
                                    <input type="text" id="team" name="name" class="form-control" placeholder="Team" autocomplete="off" required>
                                    <label for="team">Team</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary w-100">Save</button>
                            </div>
                            <div class="col-6">
                                <?php include_once "php/response-2.php"; ?>
                            </div>
                        </form>
                        <div class="alert alert-warning my-2">Terminating an team will result in the automatic removal of all associated cew members linked to it.</div>
                        <table class="table table-bordered table-striped">
                            <tr class="text-center">
                                <th>Sl</th>
                                <th>Teams</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                include_once "php/pagination-1.php";
                                $sql = $conn->prepare("SELECT * FROM teams ORDER BY name LIMIT ?, 10");
                                $sql->bindParam(1, $offset, PDO::PARAM_INT);
                                try{
                                    $sql->execute();
                                    if($sql->rowCount()>0){
                                        $i = $offset;
                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                            echo "<tr>
                                                <td class='text-center'>".++$i."</td>
                                                <td>".$row["name"]."</td>
                                                <td class='text-center'><button type='button' class='btn btn-link link-danger delete-btn' value='".$row["id"]."'><i class='fa-solid fa-trash'></i></button></td>
                                            </tr>";
                                        }
                                    }else{
                                        echo "<tr><td colspan='3' class='text-center'>No crew member.</td><tr>";
                                    }
                                }catch(PDOException $e){
                                    echo "<tr><td colspan='3' class='text-center'>Internal error: ".$e."</td><tr>";
                                }
                            ?>
                        </table>
                        <?php
                            $sql = $conn->prepare("SELECT COUNT(*) FROM teams");
                            include_once "php/pagination-2.php";
                        ?>
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