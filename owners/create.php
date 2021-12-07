<?php
    //Laddar in nödvändiga funktioner
    require_once "utilities.php";
    
    //Info som skickats till servern
    $dataPHP = file_get_contents("php://input");
    //Gör JSON till en associativ array
    $requestData = json_decode($dataPHP, true);

    //Kollar så att content-type är rätt
    if($contentType !== "application/json") {
        sendJson(
            ["message" => "The API only accepts JSON"],
            400
        );
    }

    //Kollar så att det är rätt metod innan programmet körs
    if($rqstMethod !== "POST") {
        sendJson(
            ["message" => "Method not allowed"],
            405
        );
    }

    //Kollar så att requst metoden är POST
    if($rqstMethod === "POST") {
        //Om dessa fälten inte är ifyllda skickas en error tillbaka
        if(!isset($requestData["first_name"], $requestData["last_name"], $requestData["email"], $requestData["gender"])) {
            sendJson(
                ["message" => "To create a user you must have: 'first_name', 'last_name', 'email' and 'gender'"],
                400
            );
        }

        //Laddar in users
        $data = loadJson("../databas/users.json");

        //Nya usern
        $newUser = [
            "first_name" => $requestData["first_name"],
            "last_name" => $requestData["last_name"],
            "email" => $requestData["email"],
            "gender" => $requestData["gender"]
        ];

        //Lägger till ett id som ökar med 1 över det högsta id't i databasen users.json
        $highestID = 0;
        foreach($data as $user) {
            if($user["id"] > $highestID) {
                $highestID = $user["id"];
            }
        }
        $newUser["id"] = $highestID + 1;

        //Lägger till den nya usern
        array_push($data, $newUser);

        //Spara den nya arrayen i databasen users.json
        saveJson("../databas/users.json", $data);

        //Skickar tillbaka den skapade usern och status koden 201 (Created)
        sendJson($newUser, 201);
    }
?>