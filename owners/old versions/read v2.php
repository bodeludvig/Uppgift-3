<?php
    require_once "utilities.php";

    if($contentType !== "application/json") {
        sendJson(
            ["message" => "The API only accepts JSON"],
            400
        );
    }

    if($rqstMethod !== "GET") {
        sendJson(
            ["message" => "Method not allowed"],
            405
        );
    }

    //Info som skickats till servern
    $dataPHP = file_get_contents("php://input");
    //Gör JSON till en associativ array
    $requestData = json_decode($dataPHP, true);

    if (!isset($_GET["id"])) {
        
        if($rqstMethod === "GET") {
            $data = loadJson("../databas/users.json");
            
            sendJson($data, 200);
        }

    } elseif(isset($_GET["id"])) {
    
        if($rqstMethod === "GET") {
            $data = loadJson("../databas/users.json");
            
            foreach($data as $user) {
                $id = $user["id"];
                if($_GET["id"] == $id) {
                    sendJson($user, 200);
                }
            }
        }
    }
?>