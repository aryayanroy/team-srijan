<?php
    include_once "php/config.php";
    $sql = $conn->prepare("SELECT name FROM admins WHERE id = ?");
    $sql->bindParam(1, $id, PDO::PARAM_INT);
    try{
        $sql->execute();
        if($sql->rowCount()==1){
            $name = $sql->fetch(PDO::FETCH_NUM)[0];
        }else{
            header("Location: logout");
        }
    }catch(PDOException $e){
        header("Location: logout");
    }
?>