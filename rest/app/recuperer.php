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

    // On récupère les données
    $stmt = $utilisateur->recuperer();

    // On vérifie si on a au moins 1 utilisateur
    if($stmt->rowCount() > 0)
    {
        // On initialise un tableau associatif
        $tableauUtilisateurs = [];
        $tableauUtilisateurs['utilisateurs'] = [];

        // On parcourt les utilisateurs
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            $prod =[
                "id" => $id,
                "email" => $email,
                "username" => $username,
                "password" => $password        
            ];

            $tableauUtilisateurs['utilisateurs'][] = $prod;
        }

        http_response_code(200);
        echo json_encode($tableauUtilisateurs);
    }
}

else
{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
