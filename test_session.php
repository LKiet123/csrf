<?php
// Test Session Storage
session_start();

echo "Session Save Path: " . session_save_path() . "\n";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . "\n";
echo "Session Name: " . session_name() . "\n";

if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = 'Hello Session!';
    echo "Session created with test value\n";
} else {
    echo "Session already exists with test value: " . $_SESSION['test'] . "\n";
}

echo "Session data:\n";
print_r($_SESSION);

echo "\nChecking for session files...\n";
$sessionPath = session_save_path();
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/sess_*');
    echo "Found " . count($files) . " session files\n";
    foreach ($files as $file) {
        echo "Session file: " . basename($file) . "\n";
    }
} else {
    echo "Session path is not a directory: $sessionPath\n";
}
?>
