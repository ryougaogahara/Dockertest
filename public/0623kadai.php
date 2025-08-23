<?php

echo '<img src="7FB95DB1-ED82-4AC5-9266-78269FAFCA34.jpeg" alt="画像">';


echo 'この画像のexif情報は以下の通りです';
$filename = '7FB95DB1-ED82-4AC5-9266-78269FAFCA34.jpeg';
if (file_exists($filename)) {
    $exif = exif_read_data($filename);
    if ($exif !== false) {
        echo '<pre>' . print_r($exif, true) . '</pre>';
    } else {
        echo 'Exif情報が読み取れませんでした。';
    }
} else {
    echo '画像ファイルが存在しません。';
}
?>

