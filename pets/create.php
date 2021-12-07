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
        if(!isset($requestData["first_pet_name"], $requestData["race"], $requestData["avatar"], $requestData["gender"])) {
            sendJson(
                ["message" => "To create a pet you must have: 'first_pet_name', 'race', 'avatar' and 'gender'"],
                400
            );
        }

        //Laddar in pets
        $data = loadJson("../databas/pets.json");

        //Nytt pet
        $newPet = [
            "first_pet_name" => $requestData["first_pet_name"],
            "race" => $requestData["race"],
            "avatar" => $requestData["avatar"],
            "gender" => $requestData["gender"]
        ];

        //Lägger till ett id som ökar med 1 över det högsta id't i databasen pets.json
        $highestID = 0;
        foreach($data as $pet) {
            if($pet["id"] > $highestID) {
                $highestID = $pet["id"];
            }
        }
        $newPet["id"] = $highestID + 1;

        //Lägger till ett nytt pet
        array_push($data, $newPet);

        //Spara den nya arrayen i databasen pets.json
        saveJson("../databas/pets.json", $data);

        //Skickar tillbaka det skapade pet'et och status koden 201 (Created)
        sendJson($newPet, 201);
    }
?>