<?php
    $json = "json/pages.json";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $response = array("status" => false, "message" => "No response.");
        $data = json_decode(file_get_contents($json), true);
        if($data[$page] == false){
            $data[$page] = [];
        }
        if($_FILES["image"]["error"]==0){
            $image = time();
            $upload = upload($_FILES["image"]["tmp_name"], $image, "heros");
            if($upload == true){
                $data[$page]["hero"] = $image;
            }else{
                $response["message"] = "Couldn't upload any image. Error code: ".$upload;
            }
        }
        $data[$page]["overview"] = $_POST["overview"];
        if(file_put_contents($json, json_encode($data))) {
            $response["status"] = true;
            $response["message"] = "Page info saved sucessfully.";
        }else{
            $response["message"] = "Something went wrong.";
        }   
    }
?>