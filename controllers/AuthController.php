<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/JWT.php';

class AuthController {
    public static function register() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['email'] || !$data['password']) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password required']);
            return;
        }

        $db = Database::connect();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            echo json_encode(['error' => 'User already exists']);
            return;
        }

        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$data['email'], $hash]);

        echo json_encode(['message' => 'User registered']);
    }

    public static function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        $token = JWT::generate(['user_id' => $user['id'], 'email' => $user['email']]);
        echo json_encode(['token' => $token]);
    }
}