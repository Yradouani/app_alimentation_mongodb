<?php

function addDate($id_user, $id_food, $date_of_eaten, $quantity, $typeOfMeal)
{
    $database = getDatabase();

    $result = $database->eaten_date->insertOne([
        'id_user' => $id_user,
        'id_food' => $id_food,
        'date_of_eaten' => $date_of_eaten,
        'quantity' => $quantity,
        'typeOfMeal' => $typeOfMeal
    ]);
    return $result->getInsertedCount();
}
