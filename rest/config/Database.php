<?php

class Database
{
    // Connexion à la base de données    
    private $host = "localhost";
    private $db_name = "todolist";
    private $username = "UnSeulChemin";
    private $password = "N0zenith1___";
    public $connexion;

    // Getter pour la connexion
    public function getConnection()
    {
        $this->connexion = null;

        try
        {
            $this->connexion = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->connexion->exec("set names utf8");
        }

        catch(PDOException $exception)
        {
            echo "Erreur de connexion : " . $exception->getMessage(); 
            exit();
        }

        return $this->connexion;
    }   
}
