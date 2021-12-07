<?php
    //Laddar in nödvändiga funktioner
    require_once "utilities.php";

    //Kollar så att content-type är rätt
    if($contentType !== "application/json") {
        sendJson(
            ["message" => "The API only accepts JSON"],
            400
        );
    }

    //Kollar så att det är rätt metod innan programmet körs
    if($rqstMethod !== "PATCH") {
        sendJson(
            ["message" => "Method not allowed"],
            405
        );
    }

    //Info som skickats till servern
    $dataPHP = file_get_contents("php://input");
    //Gör JSON till en associativ array
    $requestData = json_decode($dataPHP, true);

    //Kollar så att request metoden är PATCH
    if($rqstMethod === "PATCH") {
        //Kollar så att requesten innehåller ett id
        if(!isset($requestData["id"])) {
            sendJson(
                ["message" => "Id not found"],
                404
            );
        }

        //Kontrollerar så att minst en av nycklarna har skickats med
        if(!isset($requestData["first_pet_name"]) && !isset($requestData["race"]) && !isset($requestData["avatar"]) && !isset($requestData["gender"])) {
            sendJson(
                ["message" => "Missing one of these: 'first_pet_name', 'race', 'avatar' or 'gender'"],
                400
            );
        }

        //Hämtar data från databasen pets.json
        $data = loadJson("../databas/pets.json");

        $id = $requestData["id"];
        $found = false;
        $foundUser = null;

        //Loopar igenom arrayen med pets och kollar så att pet id och request id matchar
        foreach($data as $index => $pet) {
            if($pet["id"] == $id) {
                $found = true;
                
                if(isset($requestData["first_pet_name"])) {
                    $pet["first_pet_name"] = $requestData["first_pet_name"];
                }

                if(isset($requestData["race"])) {
                    $pet["race"] = $requestData["race"];
                }

                if(isset($requestData["avatar"])) {
                    $pet["avatar"] = $requestData["avatar"];
                }

                if(isset($requestData["gender"])) {
                    $pet["gender"] = $requestData["gender"];
                }

                //Uppdaterar arrayen i databasen pet.json
                $data[$index] = $pet;
                $foundPet = $pet;

                break;
            }
        }

        //Om id't inte hittats skickas en error tillbaka som berättar att id't inte finns
        if($found === false) {
            sendJson(
                ["message" => "This pet $id does not exist"],
                404
            );
        }

        //Sparar arrayen där vi ändrat pet baserat på ett id
        saveJson("../databas/pets.json", $data);

        //Skickar tillbaka id't som raderades och status koden 200 (OK)
        sendJson(["message" => "Pet with $id has been changed in pets.json", $foundPet], 200);
    }
?>