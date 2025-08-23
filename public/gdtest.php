<?php
if (isset($_GET['color'])) {
    $hex = $_GET['color'];
    $hex = ltrim($hex, '#');
    if (preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        header('Content-Type: image/png');
        $img = imagecreatetruecolor(500, 500);
        $color = imagecolorallocate($img, $r, $g, $b);
        imagefilledrectangle($img, 0, 0, 500, 500, $color);
        imagepng($img);
        imagedestroy($img);
        exit;
    } else {
        echo "色コードが不正です。";
        exit;
    }
exit;
}
?>

色を選んで「決定」を押してね。<br>
<form method="GET">
  <input type="color" name="color" value="#000000">
  <button type="submit">決定</button>
</form>







