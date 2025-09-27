<?php
session_start();
require_once 'models/UserModel.php';
require_once 'security.php';

$userModel = new UserModel();
$user = null;
$_id = null;

// Xử lý ID - validate là số nguyên
if (!empty($_GET['id'])) {
    $_id = Security::validateInt($_GET['id']);
    if ($_id === false) {
        $_SESSION['error'] = 'Invalid user ID';
        header('Location: list_users.php');
        exit();
    }
    $user = $userModel->findUserById($_id);
}

if (!empty($_POST['submit'])) {
    // Lọc dữ liệu đầu vào
    $postData = Security::sanitizeInput($_POST);
    
    // Validate dữ liệu
    if (empty($postData['name'])) {
        $_SESSION['error'] = 'Name is required';
    } else {
        if (!empty($_id)) {
            $result = $userModel->updateUser(array_merge($postData, ['id' => $_id]));
            $_SESSION['message'] = $result ? 'User updated successfully' : 'Failed to update user';
        } else {
            if (empty($postData['password'])) {
                $_SESSION['error'] = 'Password is required';
            } else {
                $result = $userModel->insertUser($postData);
                $_SESSION['message'] = $result ? 'User created successfully' : 'Failed to create user';
            }
        }
        
        if (empty($_SESSION['error'])) {
            header('Location: list_users.php');
            exit();
        }
    }
}

// Thiết lập headers bảo mật
Security::setSecurityHeaders();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
    <?php include 'views/header.php' ?>
    <div class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= Security::e($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= Security::e($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if ($user || !isset($_id)) { ?>
            <div class="alert alert-warning" role="alert">
                User form  
            </div>
            <form method="POST">
                
                <input type="hidden" name="id" value="<?= Security::e($_id ?? '') ?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" name="name" placeholder="Name" 
                           value="<?= Security::e($user[0]['name'] ?? ($_POST['name'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password <?= empty($_id) ? '(required)' : '(leave blank to keep current)' ?></label>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                <a href="list_users.php" class="btn btn-default">Cancel</a>
            </form>
        <?php } else { ?>
            <div class="alert alert-danger" role="alert">
                User not found!
            </div>
            <a href="list_users.php" class="btn btn-default">Back to list</a>
        <?php } ?>
    </div>
</body>
</html>