<?php
require_once __DIR__ . '/../core/JWT.php';

class AuthMiddleware {
    public static function handle() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $user = JWT::verify($token);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token']);
            exit;
        }

        return $user; // Use in controller
    }
}