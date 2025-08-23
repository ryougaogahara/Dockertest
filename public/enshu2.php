<?php
$dbh = new PDO('mysql:host=mysql;dbname=example_db;charset=utf8mb4', 'root', '');

$count_per_page = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $count_per_page;

if (isset($_POST['body'])) {
    $insert_sth = $dbh->prepare("INSERT INTO hogehoge (text) VALUES (:body)");
    $insert_sth->execute([
        ':body' => $_POST['body'],
    ]);
    header("HTTP/1.1 302 Found");
    header("Location: ./enshu1.php");
    return;
}

$count_sth = $dbh->query("SELECT COUNT(*) FROM hogehoge");
$count_all = (int)$count_sth->fetchColumn();

$select_sth = $dbh->prepare("SELECT text, created_at FROM hogehoge ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$select_sth->bindValue(':limit', $count_per_page, PDO::PARAM_INT);
$select_sth->bindValue(':offset', $offset, PDO::PARAM_INT);
$select_sth->execute();
$rows = $select_sth->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST" action="./enshu1.php">
  <textarea name="body"></textarea>
  <button type="submit">送信</button>
</form>

<table border="1" style="margin-top: 1em;">
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

<div style="width: 100%; text-align: center; padding-top: 1em; border-top: 1px solid #ccc; margin-bottom: 0.5em">
  <?= $page ?>ページ目
  (全 <?= max(1, ceil($count_all / $count_per_page)) ?>ページ中)
</div>

<div style="display: flex; justify-content: center;">
  <div style="width: 100%; max-width: 1000px;">
    <div style="display: flex; justify-content: space-between; margin-bottom: 2em;">
      <div>
        <?php if($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>">← 前のページ</a>
        <?php endif; ?>
      </div>
      <div>
        <?php if($count_all > $page * $count_per_page): ?>
          <a href="?page=<?= $page + 1 ?>">次のページ →</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


