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

$perPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

$totalStmt = $dbh->query("SELECT COUNT(*) FROM access_logs");
$totalRows = $totalStmt->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

$stmt = $dbh->prepare("SELECT * FROM access_logs ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アクセスログ（ページ表示）</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 1em; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
        }
        .pagination strong {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <h1>アクセスログ（<?= $totalRows ?> 件）</h1>
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

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $page): ?>
                <strong><?= $i ?></strong>
            <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</body>
</html>


