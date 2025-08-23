<?php

$dbh = new PDO('mysql:host=mysql;dbname=example_db', 'root', '');

if (!isset($_GET['id'])) {
  header("Location: ./bbstest.php");
  exit;
}

$id = (int)$_GET['id'];

$sth = $dbh->prepare("SELECT * FROM bbs_entries WHERE id = :id");
$sth->execute([':id' => $id]);
$entry = $sth->fetch();

if (!$entry) {
  echo "<p>指定された投稿は存在しません。</p>";
  echo '<p><a href="bbstest.php">← 一覧に戻る</a></p>';
  exit;
}
?>

<h1>投稿の詳細</h1>
<dl>
  <dt>ID</dt>
  <dd><?= $entry['id'] ?></dd>
  <dt>日時</dt>
  <dd><?= $entry['created_at'] ?></dd>
  <dt>内容</dt>
  <dd><?= nl2br(htmlspecialchars($entry['body'])) ?></dd>
</dl>

<p><a href="bbstest.php">← 一覧に戻る</a></p>
