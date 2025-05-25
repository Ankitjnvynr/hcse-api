<?php

require_once __DIR__ . '/Env.php';

class Database {
    private static $conn;

    public static function connect() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    'mysql:host=' . Env::get('DB_HOST') . ';dbname=' . Env::get('DB_NAME'),
                    Env::get('DB_USER'),
                    Env::get('DB_PASS') || ""
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die(json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]));
            }
        }
        return self::$conn;
    }
}