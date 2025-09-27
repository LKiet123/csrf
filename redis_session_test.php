<?php
// Redis Session Configuration
// This file demonstrates how to configure Redis for session storage

echo "<h1>Redis Session Configuration Test</h1>\n";

// Check if Redis extension is loaded
if (!extension_loaded('redis')) {
    echo "<div style='color: red; padding: 10px; background: #ffe6e6; border: 1px solid red; margin: 10px 0;'>";
    echo "<strong>Redis Extension NOT Loaded!</strong><br>";
    echo "Please install Redis extension for PHP<br>";
    echo "Instructions below...";
    echo "</div>\n";
} else {
    echo "<div style='color: green; padding: 10px; background: #e6ffe6; border: 1px solid green; margin: 10px 0;'>";
    echo "<strong>Redis Extension is Loaded ✓</strong>";
    echo "</div>\n";

    try {
        // Test Redis connection
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->auth('mypassword');

        echo "<div style='color: green; padding: 10px; background: #e6ffe6; border: 1px solid green; margin: 10px 0;'>";
        echo "<strong>Redis Connection Successful ✓</strong>";
        echo "</div>\n";

        // Configure session to use Redis
        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', 'tcp://127.0.0.1:6379?auth=mypassword');

        echo "<div style='color: blue; padding: 10px; background: #e6f7ff; border: 1px solid blue; margin: 10px 0;'>";
        echo "<strong>Session configured to use Redis</strong><br>";
        echo "Save Handler: " . ini_get('session.save_handler') . "<br>";
        echo "Save Path: " . ini_get('session.save_path');
        echo "</div>\n";

    } catch (Exception $e) {
        echo "<div style='color: red; padding: 10px; background: #ffe6e6; border: 1px solid red; margin: 10px 0;'>";
        echo "<strong>Redis Connection Failed:</strong> " . $e->getMessage();
        echo "</div>\n";
    }
}

echo "<hr>\n";

// Test current session configuration
echo "<h2>Current Session Configuration</h2>\n";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr><th>Setting</th><th>Value</th></tr>\n";
echo "<tr><td>session.save_handler</td><td>" . ini_get('session.save_handler') . "</td></tr>\n";
echo "<tr><td>session.save_path</td><td>" . ini_get('session.save_path') . "</td></tr>\n";
echo "<tr><td>session.gc_maxlifetime</td><td>" . ini_get('session.gc_maxlifetime') . "</td></tr>\n";
echo "<tr><td>session.cookie_lifetime</td><td>" . ini_get('session.cookie_lifetime') . "</td></tr>\n";
echo "</table>\n";

echo "<hr>\n";

// Test session functionality
echo "<h2>Session Test</h2>\n";
session_start();

if (isset($_GET['clear'])) {
    session_destroy();
    echo "<div style='color: orange; padding: 10px; background: #fff2e6; border: 1px solid orange; margin: 10px 0;'>";
    echo "Session cleared!";
    echo "</div>\n";
    session_start();
}

if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 0;
}
$_SESSION['counter']++;

echo "<div style='padding: 10px; background: #f0f0f0; border: 1px solid #ccc; margin: 10px 0;'>";
echo "Session Counter: " . $_SESSION['counter'] . "<br>";
echo "Session ID: " . session_id() . "<br>";
echo "Session Data: <pre>" . print_r($_SESSION, true) . "</pre>";
echo "</div>\n";

echo "<hr>\n";

// Installation Instructions
echo "<h2>Installation Instructions</h2>\n";
echo "<div style='padding: 15px; background: #f9f9f9; border-left: 4px solid #007cba; margin: 10px 0;'>\n";
echo "<h3>1. Install Redis Extension for PHP</h3>\n";
echo "<pre>\n";
echo "For Windows XAMPP:\n";
echo "1. Download redis.dll from: https://pecl.php.net/package/redis\n";
echo "2. Copy redis.dll to: C:\\xampp\\php\\ext\\\n";
echo "3. Add to php.ini: extension=redis\n";
echo "4. Restart Apache\n";
echo "</pre>\n";

echo "<h3>2. Start Redis Server</h3>\n";
echo "<pre>\n";
echo "Using Docker:\n";
echo "docker run -d -p 6379:6379 --name redis redis:7.2 redis-server --requirepass mypassword\n";
echo "\n";
echo "Or using docker-compose.yml (already configured)\n";
echo "</pre>\n";

echo "<h3>3. Update php.ini</h3>\n";
echo "<pre>\n";
echo "[session]\n";
echo "session.save_handler = redis\n";
echo "session.save_path = \"tcp://127.0.0.1:6379?auth=mypassword\"\n";
echo "</pre>\n";
echo "</div>\n";

echo "<hr>\n";
echo "<a href='?clear=1' style='color: red;'>Clear Session</a> | ";
echo "<a href='?'>Refresh</a>\n";
?>
