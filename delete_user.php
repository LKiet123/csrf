<?php
 session_start();
// DEBUG: Hiển thị session info
error_log("Session ID: " . session_id());
error_log("Session data: " . print_r($_SESSION, true));

// Check if user is authenticated
if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = 'Please login first';
    header('location: login.php');
    exit();
}
// Check if user is authenticated
// if (!isset($_SESSION['id'])) {
//     $_SESSION['error'] = 'Please login first';
//     header('location: login.php');
//     exit();
// }

require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL; //Add new user
$id = NULL;

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Support both GET and POST methods for CSRF token validation
$csrf_token = $_GET['csrf_token'] ?? $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    $_SESSION['error'] = 'Invalid CSRF token';
    header('location: list_users.php');
    exit();
}

if (!empty($_GET['id']) || !empty($_POST['id'])) {
    $id = $_GET['id'] ?? $_POST['id'];

    // Check if user exists and current user has permission to delete
    $targetUser = $userModel->findUserById($id);
    if ($targetUser) {
        // Allow deletion if user is admin or deleting their own account
        if ($_SESSION['type'] === 'admin' || $_SESSION['id'] == $id) {
            $userModel->deleteUserById($id);
            $_SESSION['message'] = 'User deleted successfully';
        } else {
            $_SESSION['error'] = 'Permission denied: You can only delete your own account or must be an admin';
        }
    } else {
        $_SESSION['error'] = 'User not found';
    }
}

header('location: list_users.php');
?>