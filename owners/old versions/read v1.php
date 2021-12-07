<?php
    require_once "utilities.php";

    if($contentType !== "application/json") {
        sendJson(
            ["message" => "Bad request"],
            400
        );
    }

    if($rqstMethod !== "GET") {
        http_response_code(405);
        $json = json_encode(["message" => "Method not allowed"]);
        echo $json;
        exit();
    }

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