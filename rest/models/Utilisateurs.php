<?php

class Utilisateurs
{
    private $connexion;
    private $table = "utilisateur";

    public $id;
    public $email;
    public $username;
    public $password;

    public function __construct($db)
    {
        $this->connexion = $db;
    }

    public function recuperer()
    {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY id";
        $query = $this->connexion->prepare($sql);
        $query->execute();

        return $query;
    }

    public function recupererUn()
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $query = $this->connexion->prepare( $sql );
        // On attache l'id
        $query->bindParam(1, $this->id);
        $query->execute();

        // on récupère la ligne
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->email = !empty($row['email']) ? $row['email'] : NULL;
        $this->username = !empty($row['username']) ? $row['username'] : NULL;
        $this->password = !empty($row['password']) ? $row['password'] : NULL;
    }

    public function ajouter()
    {
        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "INSERT INTO " . $this->table . " SET email = :email, username = :username, password = :password ";

        // Préparation de la requête
        $query = $this->connexion->prepare($sql);

        // Protection contre les injections
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags(password_hash($this->password, PASSWORD_DEFAULT)));

        // Ajout des données protégées
        $query->bindParam(":email", $this->email);
        $query->bindParam(":username", $this->username);
        $query->bindParam(":password", $this->password);

        // Exécution de la requête
        if($query->execute())
        {
            return true;
        }

        return false;
    }

    public function supprimer()
    {
        // On écrit la requête
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On sécurise les données
        $this->id = htmlspecialchars(strip_tags($this->id));

        // On attache l'id
        $query->bindParam(1, $this->id);

        // On exécute la requête
        if($query->execute())
        {
            return true;
        }
        
        return false;
    }

    public function modifier()
    {
        $sql = "UPDATE " . $this->table . " SET email = :email, username = :username, password = :password, email = :email WHERE id = :id";
        $query = $this->connexion->prepare($sql);
        
        // On sécurise les données
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags(password_hash($this->password, PASSWORD_DEFAULT)));
        
        // Ajout des données protégées
        $query->bindParam(':id', $this->id);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":username", $this->username);
        $query->bindParam(":password", $this->password);     
        
        // On exécute
        if($query->execute())
        {
            return true;
        }

        return false;
    }
}
