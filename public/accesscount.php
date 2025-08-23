<?php

$dbh = new PDO('mysql:host=mysql;dbname=example_db;charset=utf8mb4', 'root', '');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $dbh->prepare("INSERT INTO hogehoge (text) VALUES ('アクセス')");
$stmt->execute();
$countStmt = $dbh->query("SELECT COUNT(*) FROM hogehoge WHERE text = 'アクセス'");
$count = $countStmt->fetchColumn();

echo "このページは {$count} 回アクセスされました。";
?>

