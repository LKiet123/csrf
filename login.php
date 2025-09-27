<?php
// Cấu hình session bảo mật
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'models/UserModel.php';
require_once 'security.php';

$userModel = new UserModel();

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra CSRF
    if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Lỗi bảo mật';
        header('Location: login.php');
        exit;
    }

    // Giới hạn số lần thử
    if (($rateLimit = Security::checkRateLimit('login', 5, 300)) !== true) {
        $_SESSION['error'] = $rateLimit['message'];
        header('Location: login.php');
        exit;
    }

    // Xử lý đăng nhập
    $username = Security::sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Vui lòng điền đủ thông tin';
        header('Location: login.php');
        exit;
    }

    $user = $userModel->auth($username, $password);
    
    if ($user) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user[0]['id'],
            'name' => Security::cleanOutput($user[0]['name']),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'last_login' => time()
        ];
        header('Location: list_users.php');
        exit;
    } else {
        $_SESSION['error'] = 'Sai tên đăng nhập hoặc mật khẩu';
        header('Location: login.php');
        exit;
    }
}

// Tạo CSRF token
$csrfToken = Security::generateCsrfToken();
Security::setSecurityHeaders();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Đăng nhập</h3>
            
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= Security::e($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']) ?>
            <?php endif ?>

            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                
                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" required
                           value="<?= Security::e($_POST['username'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>