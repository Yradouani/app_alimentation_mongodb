<?php
// require('./Models/modelsUser.php');


// transfert des données de mysql vers mongodb
// $bdd = getBdd();
// $query = $bdd->prepare('SELECT * FROM food');
// $query->execute();
// $donnees = $query->fetchAll();


// $database = getDatabase();
// $collection = $database->food;

// foreach ($donnees as $document) {
//     $result = $collection->insertOne($document);
//     echo "Document inséré avec l'ID : " . $result->getInsertedId() . "\n";
// }

function getAllFoodFromOneCategory($category)
{
    $database = getDatabase();
    $cursor = $database->food->find(['category' => $category]);
    $food = [];

    foreach ($cursor as $document) {
        $food[] = $document;
    }
    return $food;
}
