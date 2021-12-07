<?php
    //Laddar in nödvändiga funktioner
    require_once "utilities.php";

    //Kollar så att det är rätt metod innan programmet körs
    if($rqstMethod !== "GET") {
        sendJson(
            ["message" => "Method not allowed"],
            405
        );
    }

    //Hämtar users från databasen users.json
    $data = loadJson("../databas/users.json");
    //Info som skickats till servern
    $dataPHP = file_get_contents("php://input");
    //Gör JSON till en associativ array
    $requestData = json_decode($dataPHP, true);

    if($rqstMethod === "GET") {
        //Sätter en limit på hur många users man hämtar
        if(isset($_GET["limit"])) {
            $limit = $_GET["limit"];
            $sliceUsers = array_slice($data, 0, $limit);

            //Skriver ut så många users man valt i sin limit
            sendJson($sliceUsers);
        }

        //Med id hämta en eller flera specifika användare
        if(isset($_GET["ids"])) {
            $id = explode(",", $_GET["ids"]);
            $usersById = [];

            //Loop för att ta ut user baserat på ett id
            foreach($data as $user) {
                if(in_array($user["id"], $id)) {
                    $usersById[] = $user;
                }
            }
            //Skriver ut users baserat på id
            sendJson($usersById);
        }
        //Skriver ut alla users
        sendJson($data);
    }

    //Kollar så att content-type är rätt
    if($contentType !== "application/json") {
        sendJson(
            ["message" => "The API only accepts JSON"],
            400
        );
    }
?>