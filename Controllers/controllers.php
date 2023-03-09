<?php
require(__DIR__ . '/../Models/modelsUser.php');
require(__DIR__ . '/../Models/modelsFood.php');
require(__DIR__ . '/../Models/modelsDate.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['action']) && ($_GET['action'] === 'getAllFoodFromCategory')) {
    getAllFoodFromCategory();
}

// Affiche la liste de tous les employés de l'entreprise
function accueil()
{
    // $clients = getClients();
    session_start();
    require './Vues/VueAccueil.php';
}

function signInUser()
{
    require './Vues/VueSignIn.php';
}

function validSignIn()
{
    if (isset($_POST)) {
        $firstname = $_POST['prenom'];
        $lastname = $_POST['nom'];
        $email = $_POST['email'];
        $pwd = $_POST['motdepasse'];
        $date_of_birth = $_POST['date_of_birth'];
        $sexe = $_POST['sexe'];

        $result = signIn($firstname, $lastname, $email, $pwd, $date_of_birth, $sexe);
        if ($result) {
            require './Vues/VueLogIn.php';
        }
    }
}

function logInUser()
{
    require './Vues/VueLogIn.php';
}

function validLogIn()
{
    if (isset($_POST)) {
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];

        $user = logIn($email, $pwd);
        if ($user) {
            session_set_cookie_params([
                'SameSite' => 'None',
                'secure' => true
            ]);
            session_start();
            global $_SESSION;
            $_SESSION['user_id'] = $user['_id'];
            $_SESSION['user_email'] = $user['email'];

            $userInfo = getOneUser($user['_id']);
            require './Vues/VueUser.php';
        } else {
            echo "email ou mpd invalide";
        }
    }
}

function logOutUser()
{
    if (isset($_SESSION)) {
        session_destroy();
    }
    require './Vues/VueAccueil.php';
}

function addUserInfo()
{
}

function updateWeight()
{
    session_start();
    $weight = $_GET['weight'];
    updateAWeight($weight);

    $userInfo = getOneUser($_SESSION['user_id']);
    require './Vues/VueUser.php';
}

function updateSize()
{
    session_start();
    $size = $_GET['size'];
    updateASize($size);

    $userInfo = getOneUser($_SESSION['user_id']);
    require './Vues/VueUser.php';
}

function calculateQuotKcal()
{
    // session_start();
    if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
        $userInfo = getOneUser($_SESSION['user_id']);

        if (isset($userInfo['weight_user']) && $userInfo['weight_user'] != null && isset($userInfo['height']) && $userInfo['height'] != null) {
            $weight = $userInfo['weight_user'];
            $height = $userInfo['height'];
            $sexe = $userInfo['sexe'];
            $date_of_birth = $userInfo['date_of_birth'];
            $date_actuelle = date("Y-m-d");
            $age = date_diff(date_create($date_of_birth), date_create($date_actuelle));
            $age_str = $age->format('%y'); // Formatte l'objet DateInterval en une chaîne de caractères
            $kcalNecessary = 0;

            if ($sexe == "féminin") {
                $kcalNecessary = (10 * $weight) + (6.25 * $height) - (5 * $age_str) + 5;
                // echo $kcalNecessary;
            } else if ($sexe == "masculin") {
                $kcalNecessary = (10 * $weight) + (6.25 * $height) - (5 * $age_str) - 161;
                // echo $kcalNecessary;
            }
            return $kcalNecessary;
        }
    }
}
// Affiche une erreur
function erreur($msgErreur)
{
    require './Vues/VueErreur.php';
}

function VuesUser()
{
    session_start();
    $userInfo = getOneUser($_SESSION['user_id']);
    require './Vues/VueUser.php';
}

function getAllFoodFromCategory()
{
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (isset($data['category'])) {
        $category = $data['category'];
        $list = getAllFoodFromOneCategory($category);
        // $name_food = [];
        // foreach ($list as $aliment) {
        //     array_push($name_food, $aliment['name_food']);
        // }
        // $json_data = json_encode($name_food);
        // echo $json_data;

        $json_data = json_encode($list);
        echo $json_data;
        // echo json_encode($data['category']);
    } else {
        echo "Category is not set";
    }
}

function addNewDate()
{
    session_start();
    $userId = $_SESSION['user_id'];
    $foodId = $_POST['list'];
    $quantity = $_POST['mealQuantity'];
    $date = $_POST['mealDate'];
    $typeOfMeal = $_POST['check'];

    $result = addDate($userId, $foodId, $date, $quantity, $typeOfMeal);
    $userInfo = getOneUser($_SESSION['user_id']);
    require './Vues/VueUser.php';
}
