<?php
// Start session for CSRF protection
session_start();

// Check if user is authenticated
if (!isset($_SESSION['id'])) {
    header('location: login.php');
    exit();
}

require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL; //Add new user
$id = NULL;

// CSRF Token validation
if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF token';
    header('location: list_users.php');
    exit();
}

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    
    // Check if user exists and current user has permission to delete
    $targetUser = $userModel->getUserById($id);
    if ($targetUser && ($_SESSION['id'] == $id || $_SESSION['type'] == 'admin')) {
        $userModel->deleteUserById($id);//Delete existing user
        $_SESSION['message'] = 'User deleted successfully';
    } else {
        $_SESSION['error'] = 'Permission denied or user not found';
    }
}
header('location: list_users.php');
?>
