<?php
// Session Comparison Test
// This file compares file-based vs Redis-based sessions

echo "<h1>Session Storage Comparison</h1>\n";

echo "<h2>Current Session Configuration</h2>\n";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr><th>Setting</th><th>Value</th></tr>\n";
echo "<tr><td>session.save_handler</td><td>" . ini_get('session.save_handler') . "</td></tr>\n";
echo "<tr><td>session.save_path</td><td>" . ini_get('session.save_path') . "</td></tr>\n";
echo "<tr><td>session.gc_maxlifetime</td><td>" . ini_get('session.gc_maxlifetime') . "</td></tr>\n";
echo "</table>\n";

echo "<hr>\n";

// Test current session
session_start();

if (isset($_GET['test_type'])) {
    $test_type = $_GET['test_type'];

    if ($test_type === 'file') {
        // Configure for file-based sessions
        ini_set('session.save_handler', 'files');
        ini_set('session.save_path', 'C:\xampp\tmp');

        echo "<div style='color: blue; padding: 10px; background: #e6f7ff;'>";
        echo "Switched to FILE-based sessions<br>";
        echo "Save Handler: " . ini_get('session.save_handler') . "<br>";
        echo "Save Path: " . ini_get('session.save_path');
        echo "</div>\n";

    } elseif ($test_type === 'redis') {
        // Configure for Redis sessions
        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', 'tcp://127.0.0.1:6379?auth=mypassword');

        echo "<div style='color: green; padding: 10px; background: #e6ffe6;'>";
        echo "Switched to REDIS-based sessions<br>";
        echo "Save Handler: " . ini_get('session.save_handler') . "<br>";
        echo "Save Path: " . ini_get('session.save_path');
        echo "</div>\n";
    }
}

echo "<h2>Session Test</h2>\n";

// Reset session for new test
if (isset($_GET['reset'])) {
    session_destroy();
    session_start();
    $_SESSION = array();
    echo "<div style='color: orange; padding: 10px; background: #fff2e6;'>Session reset!</div>\n";
}

if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 0;
    $_SESSION['start_time'] = date('H:i:s');
}
$_SESSION['counter']++;
$_SESSION['last_access'] = date('H:i:s');

echo "<div style='padding: 15px; background: #f0f0f0; border: 1px solid #ccc;'>\n";
echo "<h3>Session Information</h3>\n";
echo "Session ID: <strong>" . session_id() . "</strong><br>\n";
echo "Counter: <strong>" . $_SESSION['counter'] . "</strong><br>\n";
echo "Start Time: <strong>" . $_SESSION['start_time'] . "</strong><br>\n";
echo "Last Access: <strong>" . $_SESSION['last_access'] . "</strong><br>\n";
echo "Session Data:<br>\n";
echo "<pre>" . print_r($_SESSION, true) . "</pre>\n";
echo "</div>\n";

echo "<hr>\n";

// Session storage location info
echo "<h2>Session Storage Locations</h2>\n";

echo "<h3>File-based Sessions:</h3>\n";
$sessionPath = session_save_path();
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/sess_*');
    echo "Session files in: $sessionPath<br>\n";
    echo "Total files: " . count($files) . "<br>\n";
    foreach (array_slice($files, 0, 5) as $file) {
        echo " - " . basename($file) . "<br>\n";
    }
    if (count($files) > 5) {
        echo " ... and " . (count($files) - 5) . " more<br>\n";
    }
} else {
    echo "Session path not found or not a directory: $sessionPath<br>\n";
}

echo "<h3>Redis Sessions:</h3>\n";
if (extension_loaded('redis')) {
    try {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->auth('mypassword');

        $keys = $redis->keys('PHPREDIS_SESSION:*');
        echo "Redis session keys: " . count($keys) . "<br>\n";
        foreach (array_slice($keys, 0, 5) as $key) {
            echo " - " . $key . "<br>\n";
        }
        if (count($keys) > 5) {
            echo " ... and " . (count($keys) - 5) . " more<br>\n";
        }
    } catch (Exception $e) {
        echo "Redis connection failed: " . $e->getMessage() . "<br>\n";
    }
} else {
    echo "Redis extension not loaded<br>\n";
}

echo "<hr>\n";

// Test buttons
echo "<h2>Test Options</h2>\n";
echo "<a href='?test_type=file' style='margin: 5px; padding: 10px; background: #e6f7ff; border: 1px solid blue;'>Test File Sessions</a>\n";
echo "<a href='?test_type=redis' style='margin: 5px; padding: 10px; background: #e6ffe6; border: 1px solid green;'>Test Redis Sessions</a>\n";
echo "<a href='?reset=1' style='margin: 5px; padding: 10px; background: #fff2e6; border: 1px solid orange;'>Reset Session</a>\n";
echo "<a href='?' style='margin: 5px; padding: 10px; background: #f0f0f0; border: 1px solid gray;'>Refresh</a>\n";

echo "<hr>\n";

// Advantages/Disadvantages
echo "<h2>Comparison</h2>\n";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr><th>Feature</th><th>File Sessions</th><th>Redis Sessions</th></tr>\n";
echo "<tr><td>Performance</td><td>Good for small apps</td><td>Excellent for high traffic</td></tr>\n";
echo "<tr><td>Scalability</td><td>Limited to single server</td><td>Multi-server support</td></tr>\n";
echo "<tr><td>Persistence</td><td>Server restart loses data</td><td>Persistent across restarts</td></tr>\n";
echo "<tr><td>Setup Complexity</td><td>Simple (default)</td><td>Requires Redis setup</td></tr>\n";
echo "<tr><td>Data Size</td><td>Limited by disk space</td><td>Limited by memory</td></tr>\n";
echo "</table>\n";
?>
