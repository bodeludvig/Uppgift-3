<?php
    require_once "utilities.php";
    $users = loadJson("../databas/users.json");
    $data = loadJson("../databas/pets.json");
    /* echo "<pre>";
    echo var_dump(array_intersect_key($data, $users));
    echo "</pre>"; */

    /* echo "<pre>";
    echo var_dump(array_diff_key($data, $users));
    echo "</pre>"; */

    /* foreach($data as $pet){
        echo "<pre>";
    echo var_dump(
        array_replace_recursive($pet,
            array_intersect_key(
                $pet, $users
            ))
        );
    echo "</pre>";
    } */

    /* $result = array_intersect_key($data, $users);
    print_r($result); */

    /* var_dump(array_keys($data, 2)); */

    /* $userId = 0;
    foreach($users as $user) {
        $userId = $user["id"];
    }
    $petOwner = [];
    foreach($data as $pet) {
        $petOwner = $pet["owner"];
    }
    foreach($data as $pet) {
        if(in_array($pet["owner"], $users)) {
            $petOwner[] = $pet;
        }
    }

    echo "<pre>";
    echo $userId;
    echo $petOwner;
    echo "</pre>"; */

    $petOwner = 0;
    foreach($data as $pet) {
        $petOwner = $pet["owner"];
        echo $petOwner;
        break;
    }
    $userOwner = 0;
    
    foreach($users as $user) {
        $userOwner = $user["owner"];
        echo $userOwner;
        break;
    }
    /* $combined = [];
    foreach($users as $user) {
        if(in_array($petOwner, $users)) {
            $combined = $user;
        }
    }
    echo var_dump($combined); */
    
?>