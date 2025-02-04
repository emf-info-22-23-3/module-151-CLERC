<?php
class DBConfig
{

    const DB_TYPE = "mysql";
    const DB_HOST = 'database';
    const DB_USER = 'root';
    const DB_PASSWORD = 'root';
    const DB_NAME = 'hockey_stats';

    private static $_instance = null;
    private $conn;


    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new DBConfig();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        try {
            $this->conn = new PDO(self::DB_TYPE . ':host=' . self::DB_HOST . ';dbname=' . self::DB_NAME, self::DB_USER, self::DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->conn = null;
    }

    public function getEquipes()
    {
        $sql = "SELECT * FROM t_equipe";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();  // Exécution de la requête

        $equipes = [];
        // Récupère toutes les lignes sous forme d'un tableau associatif
        while ($row = $stmt->fetch()) {
            $equipes[] = new Equipe($row['PK_equipe'], $row['Nom']);
        }

        return $equipes;
    }

    // Fonction pour obtenir les joueurs d'une équipe donnée
    public function getJoueurs($equipeId)
    {
        $sql = "SELECT * FROM t_joueur WHERE FK_equipe = ?";
        $stmt = $this->conn->prepare($sql);

        // Lier le paramètre à la requête préparée
        $stmt->bindParam(1, $equipeId, PDO::PARAM_INT);
        $stmt->execute();  // Exécution de la requête

        $joueurs = [];
        // Récupère toutes les lignes sous forme d'un tableau associatif
        while ($row = $stmt->fetch()) {
            $joueurs[] = new Joueur($row['PK_joueur'], $row['Nom'], $row['Points']);
        }

        return $joueurs;
    }
}
?>