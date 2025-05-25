<?php

class JWT {
    public static function generate($payload, $expMinutes = 60) {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $payload['exp'] = time() + ($expMinutes * 60);

        $base64UrlHeader = self::base64UrlEncode(json_encode($header));
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", Env::get('JWT_SECRET'), true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public static function verify($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        [$header, $payload, $signature] = $parts;
        $check = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", Env::get('JWT_SECRET'), true)
        );

        if (!hash_equals($check, $signature)) return false;

        $payloadData = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        return ($payloadData && $payloadData['exp'] >= time()) ? $payloadData : false;
    }

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}