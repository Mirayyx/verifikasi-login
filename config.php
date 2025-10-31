<?php
// config.php - Konfigurasi Supabase

define('SUPABASE_URL', 'https://nykkjdlfrldjtlcqpvnp.supabase.co'); // Ganti dengan URL project lu
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im55a2tqZGxmcmxkanRsY3Fwdm5wIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjE4Nzk4NzYsImV4cCI6MjA3NzQ1NTg3Nn0.Yx12iGsgrScQ9us9fgcUwqUA5JU2YHSFFoMCiER06oE'); // Ganti dengan anon/public key lu

function supabaseRequest($endpoint, $method = 'GET', $data = null) {
    $url = SUPABASE_URL . '/rest/v1/' . $endpoint;
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 300) {
        return json_decode($response, true);
    } else {
        return ['error' => $response, 'http_code' => $httpCode];
    }
}
?>
