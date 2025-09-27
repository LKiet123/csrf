<?php
// Redis Session Setup
// This file configures session to use Redis programmatically

echo "<h1>Redis Session Setup</h1>\n";

// Check if Redis extension exists
if (!extension_loaded('redis')) {
    echo "<div style='color: red; padding: 10px; background: #ffe6e6;'>";
    echo "Redis extension not loaded. Please install it first.";
    echo "</div>\n";
    exit;
}

// Configure session to use Redis
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://127.0.0.1:6379?auth=mypassword');

echo "<div style='color: green; padding: 10px; background: #e6ffe6;'>";
echo "Session configured to use Redis<br>";
echo "Save Handler: " . ini_get('session.save_handler') . "<br>";
echo "Save Path: " . ini_get('session.save_path');
echo "</div>\n";

// Start session
session_start();

echo "<h2>Session Test</h2>\n";
if (!isset($_SESSION['redis_test'])) {
    $_SESSION['redis_test'] = 'Hello Redis Session!';
    $_SESSION['timestamp'] = date('Y-m-d H:i:s');
    echo "<div style='color: blue; padding: 10px; background: #e6f7ff;'>";
    echo "Session created!";
    echo "</div>\n";
} else {
    echo "<div style='color: green; padding: 10px; background: #e6ffe6;'>";
    echo "Session exists: " . $_SESSION['redis_test'];
    echo "</div>\n";
}

echo "<pre>Session Data:\n";
print_r($_SESSION);
echo "</pre>\n";

echo "<hr>\n";
echo "<a href='?'>Refresh</a>\n";
?>
