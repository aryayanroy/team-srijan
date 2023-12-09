<?php
    include_once "php/session.php";
    include_once "php/admin.php";

    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["action"])){
        $action = $_POST["action"];
        $response = [false];
        if($action == 0){
            $sql = $conn->prepare("SELECT name, email FROM admins WHERE id = ?");
            $sql->bindParam(1, $id, PDO::PARAM_INT);
            try{
                $sql->execute();
                if($sql->rowCount()==1){
                    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
                    $response[0] = true;
                    $response[1] = $data[0];
                }else{
                    $response[1][] = "Invalid admins found.";
                }
            }catch(PDOException $e){
                $response[1][] = "Couldn't load data.";
                $response[2][] = $e->getMessage();
            }
        }elseif($action == 1){
            $sql = $conn->prepare("UPDATE admins SET name = ? WHERE id = ?");
            $sql->bindParam(1, $_POST["name"], PDO::PARAM_STR);
            $sql->bindParam(2, $id, PDO::PARAM_INT);
            try{
                $sql->execute();
                $response[0] = true;
                $response[1][] = "Profile updated successfully.";
            }catch(PDOException $e){
                $response[1][] = "Couldn't update profile.";
                $response[2][] = $e->getMessage();
            }
        }
        include_once "php/response.php";
    }
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
                            <a href="pages" class="nav-link">General</a>
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
                            <a href="personal" class="nav-link active">Personal</a>
                            <a href="password" class="nav-link">Passoword</a>
                        </nav>
                        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" id="input-form" class="row g-3">
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Name" spellcheck="false" autocomplete="off" value="" required>
                                    <label for="name">Full name</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="email" id="email" class="form-control" placeholder="Email" value="" disabled required>
                                    <label for="email">Email address</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary w-100">Save</button>
                            </div>
                        </form>
                    </article>
                    <div id="asd"></div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once "php/footer.php"; ?>
    <?php include_once "php/loading.php"; ?>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script src="assets/public/js/admin.js"></script>
<script>
    $(document).ready(function(){
        function set_data(){
            load_data([], function(response){
                var data = JSON.parse(response);
                if(data[0]){
                    data = data[1];
                    $("#name").val(data.name);
                    $("#email").val(data.email);
                }else{
                    response_messages(data[1], data[2]);
                }
            });
        }

        set_data();

        $("#input-form").submit(function(e){
            e.preventDefault();
            var form = $(this);
            submit_urlencoded(find_btn(form), form.serializeArray(), 0, function(response){
                var data = JSON.parse(response);
                if(data[0]==true){
                    set_data();
                }
                response_messages(data[1], data[2]);
            })
        })
    })
</script>
</html>