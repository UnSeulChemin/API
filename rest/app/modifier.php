<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    include_once '../config/Database.php';
    include_once '../models/Utilisateurs.php';

    $database = new Database();
    $db = $database->getConnection();

    $utilisateur = new Utilisateurs($db);

    // On récupère les informations envoyées
    $donnees = json_decode(file_get_contents("php://input"));
    
    if(!empty($donnees->email) && !empty($donnees->username) && !empty($donnees->password))
    {
        // On hydrate notre objet
        $utilisateur->id = $donnees->id;
        $utilisateur->email = $donnees->email;
        $utilisateur->username = $donnees->username;
        $utilisateur->password = $donnees->password;

        if($utilisateur->modifier())
        {
            http_response_code(200);
            echo json_encode(["message" => "La modification a été effectuée"]);
        }
        
        else
        {
            http_response_code(503);
            echo json_encode(["message" => "La modification n'a pas été effectuée"]);         
        }
    }
}

else
{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
