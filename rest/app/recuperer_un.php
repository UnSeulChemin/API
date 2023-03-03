<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    include_once '../config/Database.php';
    include_once '../models/Utilisateurs.php';

    $database = new Database();
    $dbh = $database->getConnection();

    $utilisateur = new Utilisateurs($dbh);

    // On récupère les informations envoyées
    $donnees = json_decode(file_get_contents("php://input"));

    if(!empty($donnees->id))
    {
        // On hydrate notre objet
        $utilisateur->id = $donnees->id;

        // On récupère l'utilisateur
        $utilisateur->recupererUn();

        // On vérifie si le utilisateur existe
        if($utilisateur->username != null)
        {
            $prod = [
                "id" => $utilisateur->id,
                "email" => $utilisateur->email,
                "username" => $utilisateur->username,
                "password" => $utilisateur->password,
            ];

            http_response_code(200);
            echo json_encode($prod);
        }
        
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "L'utilisateur n'existe pas."));
        }
    }
}

else
{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
