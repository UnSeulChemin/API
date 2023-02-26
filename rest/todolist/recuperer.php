<?php

// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../models/Utilisateurs.php';

    // On instancie la base de données
    $database = new Database();
    $dbh = $database->getConnection();

    // On instancie les utilisateurs
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
                "avatar" => $avatar,
                "nom" => $nom,
                "mdp" => $mdp,
                "email" => $email,
                "sexe" => $sexe,
                "niveau" => $niveau,
                "experience" => $experience               
            ];

            $tableauUtilisateurs['utilisateurs'][] = $prod;
        }

        // On envoie le code réponse 200 OK
        http_response_code(200);

        // On encode en json et on envoie
        echo json_encode($tableauUtilisateurs);
    }
}
else
{
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
