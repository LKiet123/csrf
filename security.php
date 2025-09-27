<?php
// security.php - Các hàm bảo mật
session_start();

class Security {
    
    // Kiểm tra CSRF Token
    public static function checkCSRF() {
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die(' CSRF Token validation failed!');
        }
    }
    
    // Tạo CSRF Token
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    // Validate input
    public static function validateInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    // Kiểm tra đăng nhập
    public static function checkLogin() {
        if (empty($_SESSION['user_id'])) {
            header('location: login.php');
            exit();
        }
    }
    
    // Log activity (ghi log để theo dõi)
    public static function logActivity($action) {
        $log = date('Y-m-d H:i:s') . " - IP: " . $_SERVER['REMOTE_ADDR'] . 
               " - Action: " . $action . " - User: " . ($_SESSION['user_id'] ?? 'guest') . "\n";
        file_put_contents('security.log', $log, FILE_APPEND);
    }
}
?>