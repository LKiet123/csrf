<?php
class Security {
    // Kiểm tra số nguyên
    public static function validateInt($value) {
        if ($value === null || $value === '') {
            return false;
        }
        $options = [
            'options' => [
                'min_range' => PHP_INT_MIN,
                'max_range' => PHP_INT_MAX
            ]
        ];
        return filter_var($value, FILTER_VALIDATE_INT, $options);
    }
    
    // Làm sạch dữ liệu
    public static function sanitizeInput($data) {
        if (is_array($data)) return array_map([self::class, 'sanitizeInput'], $data);
        return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
    }

    // Tạo CSRF token
    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Xác thực CSRF token
    public static function verifyCsrfToken($token) {
        return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Giới hạn số lần thử
    public static function checkRateLimit($key, $limit, $timeout) {
        $now = time();
        $key = "rate_{$key}_" . ($_SERVER['REMOTE_ADDR'] ?? '');
        
        if (empty($_SESSION[$key]) || ($now - $_SESSION[$key]['time']) > $timeout) {
            $_SESSION[$key] = ['count' => 1, 'time' => $now];
            return true;
        }

        if (++$_SESSION[$key]['count'] > $limit) {
            $wait = $timeout - ($now - $_SESSION[$key]['time']);
            return ['message' => "Vui lòng thử lại sau " . ceil($wait/60) . " phút"];
        }

        return true;
    }

    // Thiết lập security headers
    public static function setSecurityHeaders() {
        $headers = [
            'X-Content-Type-Options: nosniff',
            'X-Frame-Options: DENY',
            'X-XSS-Protection: 1; mode=block',
            'Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' https://cdn.jsdelivr.net; style-src \'self\' \'unsafe-inline\' https://cdn.jsdelivr.net; img-src \'self\' data:',
            'Referrer-Policy: no-referrer-when-downgrade'
        ];
        
        foreach ($headers as $header) {
            header($header);
        }
    }

    // Làm sạch dữ liệu đầu ra
    public static function cleanOutput($data) {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    // Alias cho e() để tương thích ngược
    public static function e($data) {
        return self::cleanOutput($data);
    }
}