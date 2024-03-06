<!DOCTYPE html>
<html>
<head>
    <title>mission3-2</title>
</head>
<body>

<h2>投稿フォーム</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    名前: <input type="text" name="name"><br>
    コメント: <input type="text" name="comment"><br>
    <input type="submit" name="submit" value="送信">
</form>

<h2>投稿一覧</h2>
<?php
$filename = "mission_3.txt";

//投稿番号指定
   if(file_exists($filename)){
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    $num = count($lines) + 1;
   }else{
    $num = 1;
   }
//投稿された内容をtxtに書き込む
   if(!empty($_POST["name"]) && !empty($_POST["comment"])){
    $file = fopen($filename,"a");
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    fwrite($file, $num . "<>" . $name . "<>" . $comment ."<>" . date("Y/m/d H:i:s") . PHP_EOL);
        fclose($file);
   
} 
// txtを投稿ごとに表示
   if(file_exists($filename)){
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    foreach($lines as $line){
        if($line !== ""){
            $line = explode("<>", $line);
        echo "投稿番号: " .$line[0] . "<br>";
            echo "名前: " . $line[1] . "<br>";
            echo "コメント: " . $line[2] . "<br>";
            echo "投稿日時: " . $line[3] . "<br>";
        
    
    }
    echo "<br>";
  }
}
  
// 空が記入された際の処理
if(empty($_POST["name"]) && empty($_POST["comment"])){
            echo "名前とコメントを記入してください。";
        }
?>
</body>
</html>


