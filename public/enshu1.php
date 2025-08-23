<?php
$dbh = new PDO('mysql:host=mysql;dbname=example_db;charset=utf8mb4', 'root', '');

if (isset($_POST['body'])) {
  $insert_sth = $dbh->prepare("INSERT INTO hogehoge (text) VALUES (:body)");
  $insert_sth->execute([
      ':body' => $_POST['body'],
  ]);
  header("HTTP/1.1 302 Found");
  header("Location: ./enshu1.php");
  return;
}

$select_sth = $dbh->query("SELECT text, created_at FROM hogehoge ORDER BY created_at DESC");
$rows = $select_sth->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST" action="./enshu1.php">
  <textarea name="body"></textarea>
  <button type="submit">送信</button>
</form>

<table border="1">
  <tr>
    <th>本文</th>
    <th>作成日時</th>
  </tr>
  <?php foreach ($rows as $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8') ?></td>
      <td><?= htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
    </tr>
  <?php endforeach; ?>
</table>

