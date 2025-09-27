<?php
session_start();
require_once 'models/UserModel.php';
require_once 'security.php'; // 🛡️ Thêm file security

$userModel = new UserModel();
$user = NULL;
$_id = NULL;

if (!empty($_GET['id'])) {
    $_id = Security::validateInput($_GET['id']); // 🛡️ Validate input
    $user = $userModel->findUserById($_id);
}

if (!empty($_POST['submit'])) {
    Security::checkCSRF(); // 🛡️ Kiểm tra CSRF token
    
    // 🛡️ Validate inputs
    $name = Security::validateInput($_POST['name']);
    $password = Security::validateInput($_POST['password']);
    
    Security::logActivity("User form submission"); // 🛡️ Ghi log
    
    if (!empty($_id)) {
        $userModel->updateUser(['id' => $_id, 'name' => $name, 'password' => $password]);
    } else {
        $userModel->insertUser(['name' => $name, 'password' => $password]);
    }
    header('location: list_users.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
    <?php include 'views/header.php'?>
    <div class="container">
        <?php if ($user || !isset($_id)) { ?>
            <div class="alert alert-warning" role="alert">
                User form - 🛡️ Protected Version
            </div>
            <form method="POST">
                <!-- 🛡️ CSRF Token Protection -->
                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                
                <input type="hidden" name="id" value="<?php echo $_id ?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" name="name" placeholder="Name" 
                           value='<?php if (!empty($user[0]['name'])) echo htmlspecialchars($user[0]['name']); ?>'>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
            </form>
        <?php } else { ?>
            <div class="alert alert-success" role="alert">
                User not found!
            </div>
        <?php } ?>
    </div>
</body>
</html>