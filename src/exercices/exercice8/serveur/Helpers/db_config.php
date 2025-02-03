<?php
class DBConfig
{

    const DB_TYPE = "mysql";
    const DB_HOST = 'database';
    const DB_USER = 'root';
    const DB_PASSWORD = 'root';
    const DB_NAME = 'hockey_stats';

    public static function getConnection()
    {
        try {
            $conn = new PDO(self::DB_TYPE . ':host=' . self::DB_HOST . ';dbname=' . self::DB_NAME, self::DB_USER, self::DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec('SET NAMES utf8');
            return $conn;
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}
?>