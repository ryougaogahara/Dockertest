<?php

$dbh = new PDO('mysql:host=mysql;dbname=example_db;charset=utf8mb4', 'root', '');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$remoteIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

$stmt = $dbh->prepare("INSERT INTO access_logs (user_agent, remote_ip) VALUES (:user_agent, :remote_ip)");
$stmt->execute([
    ':user_agent' => $userAgent,
    ':remote_ip' => $remoteIp,
]);

$stmt = $dbh->query("SELECT * FROM access_logs ORDER BY created_at DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アクセスログ</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h1>アクセスログ</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>IPアドレス</th>
            <th>User Agent</th>
            <th>アクセス日時</th>
        </tr>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log['id']) ?></td>
                <td><?= htmlspecialchars($log['remote_ip']) ?></td>
                <td><?= htmlspecialchars($log['user_agent']) ?></td>
                <td><?= htmlspecialchars($log['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>


