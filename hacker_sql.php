<?php
// hacker_sql.php - Demo tấn công SQL Injection
session_start();

if (!empty($_GET['attack'])) {
    $keyword = $_GET['attack'];
    
    // Mô phỏng code vulnerable
    $conn = new mysqli('localhost', 'root', '', 'test_db');
    $sql = "SELECT * FROM users WHERE name LIKE '%$keyword%'";
    
    echo "<h3>💀 Câu lệnh SQL nguy hiểm:</h3>";
    echo "<code>" . htmlspecialchars($sql) . "</code>";
    
    $result = $conn->query($sql);
    if ($result) {
        echo "<h3>✅ Tấn công thành công!</h3>";
    }
}
?>

<form method="GET">
    <input type="text" name="attack" placeholder="Nhập payload SQL Injection" size="50">
    <button type="submit">Tấn công</button>
</form>

<p>💡 Thử các payload sau:</p>
<ul>
    <li><code>test' OR '1'='1</code> - Hiển thị tất cả users</li>
    <li><code>test'; DROP TABLE users; --</code> - Xóa bảng users</li>
    <li><code>' UNION SELECT 1,2,3,4 --</code> - Union attack</li>
</ul>