<?php

$dbh = new PDO('mysql:host=mysql;dbname=example_db;charset=utf8mb4', 'root', '');

$error = '';
$body = '';

if (isset($_POST['body'])) {
    $body = $_POST['body'];

    $image_url = null;
    if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
        $max_size = 5 * 1024 * 1024;
        if ($_FILES['image']['size'] > $max_size) {
            $error = '5MB以上の画像はアップロードできません';
        } elseif (preg_match('/^image\//', $_FILES['image']['type']) !== 1) {
            $error = '画像ファイルを選択してください';
        } else {
            $pathinfo = pathinfo($_FILES['image']['name']);
            $extension = $pathinfo['extension'];
            $filename = time() . bin2hex(random_bytes(10)) . '.' . $extension;

            $filepath = '/var/www/upload/image/' . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $filepath);

            $image_url = '/image/' . $filename;
        }
    }

    if (!$error) {
        $insert_sth = $dbh->prepare(
            "INSERT INTO posts (content, image_url) VALUES (:content, :image_url)"
        );
        $insert_sth->execute([
            ':content' => $body,
            ':image_url' => $image_url,
        ]);

        header("Location: ./zenkikadai.php");
        exit;
    }
}

$select_sth = $dbh->prepare('SELECT * FROM posts ORDER BY created_at DESC');
$select_sth->execute();
?>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="./zenkikadai.php" enctype="multipart/form-data">
    <textarea name="body" required><?= htmlspecialchars($body) ?></textarea>
    <div style="margin: 1em 0;">
        <input type="file" accept="image/*" name="image">
    </div>
    <button type="submit">送信</button>
</form>

<hr>

<?php foreach ($select_sth as $entry): ?>
    <dl style="margin-bottom: 1em; border-bottom: 1px solid #ccc;">
        <dt>ID</dt>
        <dd><?= $entry['id'] ?></dd>
        <dt>日時</dt>
        <dd><?= $entry['created_at'] ?></dd>
        <dt>内容</dt>
        <dd>
            <?= nl2br(htmlspecialchars($entry['content'], ENT_QUOTES, 'UTF-8')) ?>
            <?php if (!empty($entry['image_url'])): ?>
                <div>
                    <img src="<?= htmlspecialchars($entry['image_url'], ENT_QUOTES, 'UTF-8') ?>" style="max-height: 10em;">
                </div>
            <?php endif; ?>
        </dd>
    </dl>
<?php endforeach; ?>


