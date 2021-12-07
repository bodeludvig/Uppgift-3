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
        if(!isset($requestData["first_name"]) && !isset($requestData["last_name"]) && !isset($requestData["email"]) && !isset($requestData["gender"])) {
            sendJson(
                ["message" => "Missing one of these: 'first_name', 'last_name', 'email' or 'gender'"],
                400
            );
        }

        //Hämtar data från databasen users.json
        $data = loadJson("../databas/users.json");

        $id = $requestData["id"];
        $found = false;
        $foundUser = null;

        //Loopar igenom arrayen med users och kollar så att user id och request id matchar
        foreach($data as $index => $user) {
            if($user["id"] == $id) {
                $found = true;
                
                if(isset($requestData["first_name"])) {
                    $user["first_name"] = $requestData["first_name"];
                }

                if(isset($requestData["last_name"])) {
                    $user["last_name"] = $requestData["last_name"];
                }

                if(isset($requestData["email"])) {
                    $user["email"] = $requestData["email"];
                }

                if(isset($requestData["gender"])) {
                    $user["gender"] = $requestData["gender"];
                }

                //Uppdaterar arrayen i databasen users.json
                $data[$index] = $user;
                $foundUser = $user;

                break;
            }
        }

        //Om id't inte hittats skickas en error tillbaka som berättar att id't inte finns
        if($found === false) {
            sendJson(
                ["message" => "This user $id does not exist"],
                404
            );
        }

        //Sparar arrayen där vi ändrat user baserat på ett id
        saveJson("../databas/users.json", $data);

        //Skickar tillbaka id't som raderades och status koden 200 (OK)
        sendJson(["message" => "User with $id has been changed in user.json", $foundUser], 200);
    }
?>