<!DOCTYPE html>
<html>
<head>
    <title>mission3-4</title>
</head>
<body>
<h2>投稿フォーム</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    名前: <input type="text" name="name"><br>
    コメント: <input type="text" name="comment"><br>
    パスワード:<input type="password" name="pw"><br>
    <input type="submit" name="submit" value="送信">
</form>

<h2>削除</h2>
<form method="post" action="">
    <input type="number" name="deletenum" placeholder="削除対象番号"><br>
    パスワード:<input type="password" name="deletepw"><br>
    <input type="submit" name="submit" value="送信">
</form>

<h2>編集</h2>
<form method="post" action="">
    投稿番号指定: <input type="number" name="editnum"><br>
    パスワード:<input type="password" name="editpw"><br>
    <input type="submit" name="submit" value="送信">
</form>

<h2>投稿一覧</h2>
<?php
$filename = "mission35.txt";

// 投稿された内容をtxtに書き込む
if(isset($_POST["name"]) && isset($_POST["comment"]) && isset($_POST["pw"])){
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pw = $_POST["pw"];
    
    if(!empty($name) && !empty($comment) && !empty($pw)){
        $lines = file($filename);
        $num = count($lines) + 1; // ファイル内の行数を取得して投稿番号を決定
        $file = fopen($filename, "a");
        fwrite($file, $num . "<>" . $name . "<>" . $comment . "<>" . date("Y/m/d H:i:s") . "<>" . $pw . PHP_EOL);
        fclose($file);
    } else {
        echo "名前、コメント、パスワードをすべて記入してください<br>";
    }
}

// 削除処理
if(!empty($_POST["deletenum"]) && !empty($_POST["deletepw"])){
    $deletenum = $_POST["deletenum"];
    $deletepw = $_POST["deletepw"];

    if(file_exists($filename)){
        $lines = file($filename);
        $file = fopen($filename, "w");
        foreach ($lines as $line){
            $post = explode("<>", $line);
            // 削除番号と投稿番号が一致しない場合、その投稿を書き込む
            if($post[0] != $deletenum){
                fwrite($file, $line . PHP_EOL);
            } else {
                if($post[4] === $deletepw){ // パスワードの一致を確認
                    fwrite($file, "delete" . PHP_EOL); // 削除を示す文字列を書き込む
                } else {
                    fwrite($file, $line . PHP_EOL); // パスワードが一致しない場合は書き込まない
                }
            }
        }
        fclose($file);
    } else {
        echo "削除対象が見つかりませんでした<br>";
    }
}
/*
// 削除された投稿より後の投稿の投稿番号を修正
$lines = file($filename, FILE_IGNORE_NEW_LINES);
$file = fopen($filename, "w");
$delete_flag = false; // 削除フラグ
foreach ($lines as $line){
    if($line === "delete"){
        $delete_flag = true; // 削除フラグを立てる
    } else {
        $post = explode("<>", $line);
        if($delete_flag){ // 削除フラグが立っている場合は "delete" で上書き
            fwrite($file, "delete" . PHP_EOL);
            $delete_flag = false; // フラグをリセット
        } else {
            fwrite($file, $line . PHP_EOL);
        }
    }
}
fclose($file);
*/
// txtを投稿ごとに表示
if(file_exists($filename)){
    $lines = file($filename);
    foreach($lines as $line){
        if($line !== "" && $line !== "delete"){
            $line = explode("<>", $line);
            echo "投稿番号: " .$line[0] . "<br>";
            echo "名前: " . $line[1] . "<br>";
            echo "コメント: " . $line[2] . "<br>";
            echo "投稿日時: " . $line[3] . "<br>";
            echo "<br>";
        }
    }
}
// 編集で投稿番号が指定されたときの処理
if(!empty($_POST["editnum"])){
    $editnum = $_POST["editnum"];
    if(isset($lines[1])){
        foreach($lines as $line){
            if(strpos($line, "<>")){
                $items = explode("<>", $line);
                if($items[0] == $editnum){
                    $editnumtf = true;
                    $editname = $items[1];
                    $editcomment = $items[2];
                } 
            }
        }
    }
}

// 編集で編集後の内容が投稿されたときの処理
if(!empty($_POST["editname"]) && !empty($_POST["editcomment"]) && isset($_POST["editnumset"])){
    $editnumset = $_POST["editnumset"];
    $editname = $_POST["editname"];
    $editcomment = $_POST["editcomment"];

    $file = fopen($filename,"w");
    fwrite($file, $lines[0] . PHP_EOL);
    foreach($lines as $line){
        if(strpos($line, "<>")){
            $items = explode("<>", $line);
            if($items[0] !== $editnumset){
                fwrite($file, $line . PHP_EOL);
            } else {
                fwrite($file, $editnumset . "<>" . $editname . "<>" . $editcomment . "<>" . date("Y/m/d H:i:s") . "<>" . $items[4] . PHP_EOL);
            }
        }
    }
    fclose($file);
}
?>
</body>
</html>
