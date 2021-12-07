<?php
//Error reports på = -1, av = 0
error_reporting(-1);

//Variable med server request
$rqstMethod = $_SERVER["REQUEST_METHOD"];
$contentType = "application/json";

//1 Send json function
function sendJson($data, $statusCode = 200) {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        $json = json_encode($data);
        echo $json;
        exit();
}

//2 Load json function
function loadJson($fileName) {
    $dataLoadJson = json_decode(file_get_contents($fileName), true);
    return $dataLoadJson;
}

//3 Save json function
function saveJson($fileName, $data) {
    $dataSaveJson = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($fileName, $dataSaveJson);
    return true;
}

//4 Inspect data (var_dump)
function inspect($inspectVariable) {
    echo "<pre>";
    echo var_dump($inspectVariable);
    echo "</pre>";
}
?>