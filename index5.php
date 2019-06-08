
<?php

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


$fp = fopen('data.json', 'c+b'); // cモードは任意の位置から書き込み可能

// ファイル内容全てを読み取り，JSON形式としてデコードする
// 空文字列をデコードしたときにはNULLになるので，配列にキャストして空の配列にする
$rows = (array)json_decode(stream_get_contents($fp), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // もし投稿内容があれば，読み取った配列変数に要素を追加する
    $rows[] = ['name' => $_POST['name'] , 'mail' => $_POST['mail'] , 'birth' => $_POST['birth']];
  
    // ファイルポインタを先頭に戻す
   rewind($fp);
    // ファイルにJSON形式として配列全体を上書きする (オプションは読みやすくするためのもの)
    fwrite($fp,json_encode($rows,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    // 上書きされなかった後の余分なデータを消去する
    ftruncate($fp, ftell($fp));

   
}
fclose($fp);

?>






<!DOCTYPE html>

<html>
  <head>
    <title>json</title>
  </head>
    <body>
        



<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <p>お名前</p><input type="text" name="name"><br>
    <p>メールアドレス</p><input type="text" name="mail"><br>
    <p>生年月日</p><input type="text" name="birth"><br>
<input type="submit" name="btn1" value="入力">
</form>



<section>
    <h2>入力一覧</h2>
<?php if (!empty($rows)): ?>
    <ul>
<?php foreach ($rows as $row): ?>
        <li><?=h($row["name"])?></li>
        <li><?=h($row["mail"])?></li>
        <li><?=h($row["birth"])?></li>
<?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>入力はまだありません</p>
<?php endif; ?>
</section>