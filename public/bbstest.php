<?php
$dbh = new PDO('mysql:host=mysql;dbname=example_db', 'root', '');

if (isset($_POST['body'])) {
  $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (body) VALUES (:body)");
   $insert_sth->execute([
      ':body' => $_POST['body'],
   ]);
  header("HTTP/1.1 302 Found");
  header("Location: ./bbstest.php");
  return;
}
$query = $_GET['query'] ?? '';

if ($query !== '') {
  $select_sth = $dbh->prepare('SELECT * FROM bbs_entries WHERE body LIKE :query ORDER BY created_at DESC');
  $select_sth->execute([':query' => '%' . $query . '%']);
} else {
  $select_sth = $dbh->prepare('SELECT * FROM bbs_entries ORDER BY created_at DESC');
  $select_sth->execute();
}
?>

<form method="POST" action="./bbstest.php">
  <textarea name="body"></textarea>
  <button type="submit">送信</button>
</form>

<form method="GET" action="./bbstest.php">
  <input type="text" name="query">
  <button type="submit">検索</button>
<?php
if(!empty($_GET['query'])): ?>
<p>現在「<?= htmlspecialchars($query) ?> 」で検索中</p>  
<a href="./bbstest.php">検索解除</a>
<?php
endif;
?>

</form>

<hr>

<?php foreach($select_sth as $entry): ?>
  <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
    <dt>ID</dt>
    <dd><a href="show.php?id=<?= $entry['id'] ?>"><?= $entry['id'] ?></a></dd>

<dt>日時</dt>
    <dd><?= $entry['created_at'] ?></dd>

    <dt>内容</dt>
    <dd><?= nl2br(htmlspecialchars($entry['body'])) // 必ず htmlspecialchars() すること ?></dd>
</dl>
<?php endforeach ?>
