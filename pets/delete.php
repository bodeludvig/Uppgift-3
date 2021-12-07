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
    if($rqstMethod !== "DELETE") {
        sendJson(
            ["message" => "Method not allowed"],
            405
        );
    }

    //Info som skickats till servern
    $dataPHP = file_get_contents("php://input");
    //Gör JSON till en associativ array
    $requestData = json_decode($dataPHP, true);

    //Kollar vilket id, raderar pet'et och skickar tillbaka vilket pet id som blivit raderat och status koden 200 (OK)
    if($rqstMethod === "DELETE") {
        //Hämtar data från databasen pets.json
        $data = loadJson("../databas/pets.json");

        //Kollar ifall det finns ett id i request, om inte skickar error med meddelande och status kod 404 (Not found)
        if(!isset($requestData["id"])) {
            sendJson(
                ["message" => "Id not found"],
                404
            );
        }

        $id = $requestData["id"];
        $found = false;

        //Loop för att ta bort ett pet baserat på ett id
        foreach($data as $index => $pet) {
            if($pet["id"] == $id) {
                $found = true;
                array_splice($data, $index, 1);
                break;
            }
        }

        //Om id't inte hittats skickas en error tillbaka som berättar att id't inte finns
        if($found === false) {
            sendJson(
                ["message" => "The user $id does not exist"],
                404
            );
        }

        //Sparar arrayen där vi raderat pet baserat på ett id
        saveJson("../databas/pets.json", $data);

        //Skickar tillbaka id't som raderades och status koden 200 (OK)
        sendJson(["message" => "Pet with $id has been removed from pets.json"], 200);
    }
?>