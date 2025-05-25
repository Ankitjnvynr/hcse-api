<?php
require_once __DIR__ . '/../core/Database.php';

class UserController {
    public static function getAllUsers() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }

    public static function getUserById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    }

    public static function createUser() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['name']) || !isset($data['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Name and Email are required']);
            return;
        }

        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['email']]);

        echo json_encode(['message' => 'User created', 'id' => $db->lastInsertId()]);
    }

    public static function updateUser($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        $db = Database::connect();
        $stmt = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['email'], $id]);

        echo json_encode(['message' => 'User updated']);
    }

    public static function deleteUser($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['message' => 'User deleted']);
    }
}