<?php

class Database {
    private static $host = 'localhost';
    private static $dbName = 'php_mvc_api';
    private static $username = 'root';
    private static $password = '';
    private static $conn;

    public static function connect() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    'mysql:host=' . self::$host . ';dbname=' . self::$dbName . ';charset=utf8',
                    self::$username,
                    self::$password
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
            }
        }
        return self::$conn;
    }
}