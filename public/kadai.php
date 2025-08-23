<?php

 $date = new DateTime("now", new DateTimeZone("Asia/Tokyo"));

 // 各時刻要素を取得
 $h = (int)$date->format('G');  // 時（0–23）
 $m = (int)$date->format('i');  // 分
 $s = (int)$date->format('s');  // 秒

 // 各針の角度（時計回り、12時が0度）
 $hourAngle = ($h % 12 + $m / 60) * 30;
 $minuteAngle = $m * 6;
 $secondAngle = $s * 6;

 // 角度から座標を計算（長さ指定あり）
 function polarToXY($angleDeg, $length) {
     $angleRad = deg2rad($angleDeg - 90); // SVGでは上が0度なので-90
     $cx = 500;
     $cy = 500;
      return [
       $cx + cos($angleRad) * $length,
       $cy + sin($angleRad) * $length
      ];
      }
      list($hx, $hy) = polarToXY($hourAngle, 250);
      list($mx, $my) = polarToXY($minuteAngle, 350);
      list($sx, $sy) = polarToXY($secondAngle, 400);
 ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>現在の日本時間アナログ時計</title>
</head>
<body>
  <h1>現在の日本時間<br><?= $date->format("Y年m月d日 H:i:s") ?></h1>

  <div style="margin-top: 5em;">
    <svg width="500" height="500" viewBox="0 0 1000 1000"
         xmlns="http://www.w3.org/2000/svg" version="1.1">

      <!-- 時計の外枠 -->
      <circle cx="500" cy="500" r="495" stroke="black" fill="white" stroke-width="5"/>

      <!-- 目盛り（長針） -->
      <?php
      for ($i = 0; $i < 60; $i++) {
          $angle = deg2rad($i * 6 - 90);
          $r1 = ($i % 5 === 0) ? 460 : 480;  // 5分ごとに長くする
          $r2 = 495;
          $x1 = 500 + cos($angle) * $r1;
          $y1 = 500 + sin($angle) * $r1;
          $x2 = 500 + cos($angle) * $r2;
          $y2 = 500 + sin($angle) * $r2;
          echo "<line x1='$x1' y1='$y1' x2='$x2' y2='$y2' stroke='gray' stroke-width='2'/>\n";
      }
      ?>

      <!-- 時針 -->
      <line x1="500" y1="500" x2="<?= $hx ?>" y2="<?= $hy ?>" stroke="black" stroke-width="15"/>

      <!-- 分針 -->
      <line x1="500" y1="500" x2="<?= $mx ?>" y2="<?= $my ?>" stroke="black" stroke-width="10"/>

      <!-- 秒針 -->
      <line x1="500" y1="500" x2="<?= $sx ?>" y2="<?= $sy ?>" stroke="red" stroke-width="5"/>

      <!-- 中心点 -->
      <circle cx="500" cy="500" r="8" fill="black"/>
    </svg>
  </div>
</body>
</html>
