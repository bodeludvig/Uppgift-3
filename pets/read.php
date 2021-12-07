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

    //Hämtar pets från databasen pets.json
    $data = loadJson("../databas/pets.json");
    //Info som skickats till servern
    $dataPHP = file_get_contents("php://input");
    //Gör JSON till en associativ array
    $requestData = json_decode($dataPHP, true);

    if($rqstMethod === "GET") {
        //Sätter en limit på hur många pets man hämtar
        if(isset($_GET["limit"])) {
            $limit = $_GET["limit"];
            $slicePets = array_slice($data, 0, $limit);

            //Skriver ut så många pets man valt i sin limit
            sendJson($slicePets);
        }

        
        //Med id hämta en eller flera specifika pets
        if(isset($_GET["ids"])) {
            $id = explode(",", $_GET["ids"]);
            $petsById = [];

            //Loop för att ta ut pet baserat på ett id
            foreach($data as $pet) {
                if(in_array($pet["id"], $id)) {
                    $petsById[] = $pet;
                }
            }
            //Skriver ut pets baserat på id
            sendJson($petsById);
        }

        //Include delen
        //Funkar ej om det inte är en key med "owner" på alla pets...
        
        //Hämtar userdata
        $userData = loadJson("../databas/users.json");

        //Variabler för att spara user id utanför foreach
        $userIds = 0;
        $usersById = [];

        if(isset($_GET["ids"])) {
            $userIds = explode(",", $_GET["ids"]);
            $usersById = [];

            //Loop för att ta ut pet baserat på ett id
            foreach($userData as $user) {
                if(in_array($user["id"], $userIds)) {
                    $usersById[] = $user;
                }
            }
            /* //Skriver ut pets baserat på id
            sendJson($usersById); */
        }
        /* echo $userIds; */
        //Sätter include till false så om man inte satt get parametern till true ska den inte hämta något
        $include = false;

        if(isset($_GET["include"])) {
            $include = $_GET["include"];
            $includeOwner = [];
            if($include == true) {
                foreach($data as $pet) { 
                    $owner = $pet["owner"];
                    if($owner === $userIds){
                        if(in_array($owner, $pet)) {
                            $includeOwner[] = $pet;
                        }
                    }
                }
            }
            sendJson($includeOwner);
        }
        //Skriver ut alla pets
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