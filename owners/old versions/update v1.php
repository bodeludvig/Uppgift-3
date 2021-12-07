<?php
    require_once "utilities.php";

    //Info som skickats till servern
    $dataPHP = file_get_contents("php://input");
    //Gör JSON till en associativ array
    $requestData = json_decode($dataPHP, true);

    if(!isset($_GET["id"])) {
        sendJson(
            ["message" => "Id not found"],
            404
        );
    }

    if($contentType !== "application/json") {
        sendJson(
            ["message" => "The API only accepts JSON"],
            400
        );
    }

    if($rqstMethod !== "PATCH") {
        sendJson(
            ["message" => "Method not allowed"],
            405
        );
    }

    if($rqstMethod === "PATCH") {
        
    }
?>