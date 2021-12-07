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

    //Kollar vilket id, raderar usern och skickar tillbaka vilket användar id som blivit raderat och status koden 200 (OK)
    if($rqstMethod === "DELETE") {
        //Hämtar data från databasen users.json
        $data = loadJson("../databas/users.json");

        //Kollar ifall det finns ett id i request, om inte skickar error med meddelande och status kod 404 (Not found)
        if(!isset($requestData["id"])) {
            sendJson(
                ["message" => "Id not found"],
                404
            );
        }

        $id = $requestData["id"];
        $found = false;

        //Loop för att ta bort en user baserat på ett id
        foreach($data as $index => $user) {
            if($user["id"] == $id) {
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

        //Sparar arrayen där vi raderat user baserat på ett id
        saveJson("../databas/users.json", $data);

        //Skickar tillbaka id't som raderades och status koden 200 (OK)
        sendJson(["message" => "User with $id has been removed from user.json"], 200);
    }
?>