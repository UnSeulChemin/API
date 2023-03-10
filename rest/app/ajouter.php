<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include_once '../config/Database.php';
    include_once '../models/Utilisateurs.php';

    $database = new Database();
    $dbh = $database->getConnection();

    $utilisateur = new Utilisateurs($dbh);

    // On récupère les informations envoyées
    $donnees = json_decode(file_get_contents("php://input"));
    
    if(!empty($donnees->email) && !empty($donnees->username) && !empty($donnees->password))
    {
        // On hydrate notre objet
        $utilisateur->email = $donnees->email;
        $utilisateur->username = $donnees->username;
        $utilisateur->password = $donnees->password;

        if($utilisateur->ajouter())
        {
            http_response_code(201);
            echo json_encode(["message" => "L'ajout a été effectué"]);
        }
        
        else
        {
            http_response_code(503);
            echo json_encode(["message" => "L'ajout n'a pas été effectué"]);         
        }
    }
}

else
{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
