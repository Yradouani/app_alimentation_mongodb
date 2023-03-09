<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// $dotenv = Dotenv::createImmutable('C:\MAMP\htdocs\app_alimentation\Comportement_alimentaire');
$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)) . '/app_alimentation_mongoDB');

$dotenv->load();

// require_once 'C:\MAMP\htdocs\app_alimentation\Comportement_alimentaire\vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

// $dotenv = Dotenv::createImmutable('C:/MAMP/htdocs/app_alimentation/Comportement_alimentaire/');
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$db_host = getenv('DATABASE_URL');
echo $db_host;


function getDatabase()
{
    $client = new MongoDB\Client("mongodb+srv://yasmineradouani:Rf3ydRJh39VrOByB@cluster-app-alimentatio.inskyhm.mongodb.net/test?retryWrites=true&w=majority");

    $database = $client->selectDatabase('app_alimentation');
    return $database;
    // $collectionNames = $database->listCollectionNames();

    // foreach ($collectionNames as $collectionName) {
    //     echo $collectionName . "\n";
    // }
}

function signIn($firstname, $lastname, $email, $password, $date_of_birth, $sexe)
{
    $database = getDatabase();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insertResult = $database->users->insertOne([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'pwd' => $hashed_password,
        'date_of_birth' => $date_of_birth,
        'sexe' => $sexe,
        'weight_user' => null,
        'height' => null
    ]);

    return $insertResult->getInsertedCount();
}

function logIn($email, $password)
{
    $database = getDatabase();
    $user = $database->users->findOne(['email' => $email]);

    if ($user) {
        if (password_verify($password, $user['pwd'])) {
            return $user;
        } else {
            echo "Le mot de passe est incorrect";
        }
    } else {
        echo "L'utilisateur n'a pas été trouvé";
    }
}

function getBdd()
{
    try {
        $bdd = new PDO($_ENV['DATABASE_URL'] . '; dbname=alimentation_app; charset=utf8', $_ENV['PASSWORD'], $_ENV['USER']);
        return $bdd;
    } catch (Exception $e) {
        die('Erreur :' . $e->getMessage());
    }
}

// function isEmailUnique($email)
// {
//     $bdd = getBdd();
//     $query = $bdd->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
//     $query->execute([$email]);
//     $count = $query->fetchColumn();
//     return $count == 0;
// }

function getOneUser($id)
{
    $database = getDatabase();
    $user = $database->users->findOne(['_id' => $id]);
    if ($user) {
        return $user;
    }
}

function updateAWeight($weight)
{
    $database = getDatabase();

    $result = $database->users->updateOne(
        ['_id' => $_SESSION['user_id']],
        ['$set' => [
            'weight_user' => $weight
        ]]
    );
    return $result->getModifiedCount();
}

function updateASize($size)
{
    $database = getDatabase();

    $result = $database->users->updateOne(
        ['_id' => $_SESSION['user_id']],
        ['$set' => [
            'height' => $size
        ]]
    );
    return $result->getModifiedCount();
}
