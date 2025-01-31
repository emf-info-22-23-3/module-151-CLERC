<?php
class DBConfig {
    const HOST = '127.0.0.1';
    const USER = 'root';
    const PASSWORD = '';
    const DB_NAME = 'hockey_stats';
    
    public static function getConnection() {
        $conn = new mysqli(self::HOST, self::USER, self::PASSWORD, self::DB_NAME);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>