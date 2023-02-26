<?php

class Utilisateurs
{
    // La connexion
    private $connexion;
    private $table = "utilisateur";

    // Objets propriétés, colonnes de la base de données
    public $id;
    public $avatar;
    public $nom;
    public $mdp;
    public $email;
    public $sexe;
    public $niveau;
    public $experience;

    public function __construct($db)
    {
        $this->connexion = $db;
    }

    public function recuperer()
    {
        // On écrit la requête
        $sql = "SELECT * FROM " . $this->table . " ORDER BY id";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        // On retourne le résultat
        return $query;
    }

    public function recupererUn()
    {
        // On écrit la requête
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On attache l'id
        $query->bindParam(1, $this->id);

        // On exécute la requête
        $query->execute();

        // on récupère la ligne
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->avatar = !empty($row['avatar']) ? $row['avatar'] : NULL;
        $this->nom = !empty($row['nom']) ? $row['nom'] : NULL;
        $this->mdp = !empty($row['mdp']) ? $row['mdp'] : NULL;
        $this->email = !empty($row['email']) ? $row['email'] : NULL;
        $this->sexe = !empty($row['sexe']) ? $row['sexe'] : NULL;
        $this->niveau = !empty($row['niveau']) ? $row['niveau'] : NULL;
        $this->experience = !empty($row['experience']) ? $row['experience'] : NULL;
    }

    public function ajouter()
    {
        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "INSERT INTO " . $this->table . " SET avatar = :avatar, nom = :nom, mdp = :mdp, email = :email,
            sexe = :sexe, niveau = :niveau, experience = :experience";

        // Préparation de la requête
        $query = $this->connexion->prepare($sql);

        // Protection contre les injections
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->mdp = htmlspecialchars(strip_tags(password_hash($this->mdp, PASSWORD_DEFAULT)));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->sexe = htmlspecialchars(strip_tags($this->sexe));
        $this->niveau = htmlspecialchars(strip_tags($this->niveau));
        $this->experience = htmlspecialchars(strip_tags($this->experience));

        // Ajout des données protégées
        $query->bindParam(":avatar", $this->avatar);
        $query->bindParam(":nom", $this->nom);
        $query->bindParam(":mdp", $this->mdp);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":sexe", $this->sexe);
        $query->bindParam(":niveau", $this->niveau);
        $query->bindParam(":experience", $this->experience);

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
        // On écrit la requête
        $sql = "UPDATE " . $this->table . " SET avatar = :avatar, nom = :nom, mdp = :mdp, email = :email,
            sexe = :sexe, niveau = :niveau, experience = :experience WHERE id = :id";
        
        // On prépare la requête
        $query = $this->connexion->prepare($sql);
        
        // On sécurise les données
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->mdp = htmlspecialchars(strip_tags(password_hash($this->mdp, PASSWORD_DEFAULT)));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->sexe = htmlspecialchars(strip_tags($this->sexe));
        $this->niveau = htmlspecialchars(strip_tags($this->niveau));
        $this->experience = htmlspecialchars(strip_tags($this->experience));
        $this->id = htmlspecialchars(strip_tags($this->id));  
        
        // Ajout des données protégées
        $query->bindParam(":avatar", $this->avatar);
        $query->bindParam(":nom", $this->nom);
        $query->bindParam(":mdp", $this->mdp);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":sexe", $this->sexe);
        $query->bindParam(":niveau", $this->niveau);
        $query->bindParam(":experience", $this->experience);
        $query->bindParam(':id', $this->id);        
        
        // On exécute
        if($query->execute())
        {
            return true;
        }

        return false;
    }
}
