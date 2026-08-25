<?php

//SQL文の接続とテーブル型の作成
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

session_start();


$sql = "CREATE TABLE IF NOT EXISTS book"
       ." ("
       ."id INT AUTO_INCREMENT PRIMARY KEY,"
       ."name CHAR(32),"          // ユーザー名
       ."pass CHAR(32),"          // パスワード
       ."title VARCHAR(255),"     // 本のタイトル
       ."comment TEXT,"           // 内容
       ."image VARCHAR(255),"     // 本の画像
       ."date DATETIME"           // 登録日時
       .");";
       
$stmt = $pdo->query($sql);     


if (isset($_SESSION['user_name'])) {    //ログイン確認//
    $login_user_name = $_SESSION['user_name']; 
   

    if (!empty($_POST["submit1"]) && !empty($_POST["title"]) && !empty($_POST["txt"])) {
    $title = $_POST["title"];
    $comment = $_POST["txt"];
    $date = date('Y-m-d H:i:s');
    $image_path = '';
    
   
    
    if (!empty($_FILES['image']['name'])) {
        $uploaded_path = './images/' . $_FILES['image']['name'];
        
        //フォルダに画像保存//
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploaded_path)) {
            $image_path = $uploaded_path; 
        }
    }
    
    $sql_pass = "SELECT pass FROM book WHERE name = :name AND pass != '' LIMIT 1";
    $stmt_pass = $pdo->prepare($sql_pass);
    $stmt_pass->bindParam(':name', $login_user_name, PDO::PARAM_STR);
    $stmt_pass->execute();
    $user_record = $stmt_pass->fetch();
    $my_pass = $user_record['pass'];
    
    $sql_insert = "INSERT INTO book (name, pass, title, comment, image, date) VALUES (:name, :pass, :title, :comment, :image, :date)";
    $stmt_insert = $pdo->prepare($sql_insert);
    
    // 変数を割り当て
    $stmt_insert->bindParam(':name', $login_user_name, PDO::PARAM_STR);
    $stmt_insert->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt_insert->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt_insert->bindParam(':pass', $my_pass, PDO::PARAM_STR);
    $stmt_insert->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt_insert->bindParam(':image', $image_path, PDO::PARAM_STR);
    
    // 実行
    $stmt_insert->execute();
}


//レコード削除//
elseif ((!empty($_POST["del_num"]))&&(!empty($_POST["pass"]))){
$id = $_POST["del_num"];
$pass = $_POST["pass"];

$sql = 'SELECT * FROM book WHERE id=:id';
     $stmt = $pdo->prepare($sql);
     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
     $stmt->execute();
     $results = $stmt->fetchAll(); //配列を1つづつ変数として取り出す
     
     foreach ($results as $row) {
     $db_pass = $row['pass']; 

      if($db_pass == $pass){

      $sql = 'delete from book where id = :id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();// 実行
       echo "削除しました。<br>";
       } 
       
   else {
            echo "パスワードが違います。<br>";
        }
    }
}
    
} else {
    // セッションがない（ログインしていない）場合は login.php に戻す
    header('Location: login.php');
    exit;
}
?>

<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>読書のすゝめ</title>
</head>
<body>
       
       user_name = <?php echo htmlspecialchars($login_user_name ?? '(未設定)'); ?><br>
       mybooks= <?php
       $count_sql = 'SELECT COUNT(*) FROM book WHERE name = :name AND title != ""';
       
       $count_stmt = $pdo->prepare($count_sql);
       $count_stmt->bindParam(':name', $login_user_name, PDO::PARAM_STR);
       $count_stmt->execute();
       echo $count_stmt->fetchColumn(); 
?>
    
    <h1>
      マイリスト　　　　　　　<a href="m6_2.php">ホームへ</a>
    </h1>
    <h2>
    新しく紹介する
    </h2>
    
    <!--ユーザーのおすすめ本入力-->
    <form action="" method="post" enctype="multipart/form-data">

            <input type="file" name="image" accept="image/*">
            <input type="text" name="title" placeholder="本の名前">
            <input type="text" name="txt" placeholder="内容">
          　<input type="submit" name="submit1" value="送信ボタン">
　　</form>
　　
　　       <!--削除機能フォーム-->
     <form action="" method="post">
      <input type="number" name="del_num" placeholder="delatenumber"> <!--入力欄-->
       <input type="text" name="pass" placeholder="password"> <!--入力欄-->
      
      <input type="submit" name="submit2"  value="削除" > <!--削除ボタン-->
     </form>
    
    <h2>
      紹介リスト
    </h2>
</body>
</html>

<?php
$sql = 'SELECT * FROM book WHERE name = :name AND title != "" ORDER BY name';
$stmt = $pdo->prepare($sql);

// ③ 文字列としてパラメータを割り当てるため、PDO::PARAM_STR を使用する
$stmt->bindParam(':name', $login_user_name, PDO::PARAM_STR); 
$stmt->execute();


$results = $stmt->fetchAll();
 
// ループして、取得したデータを表示
foreach ($results as $row) {
  // $row の中にはテーブルのカラム名が入る
  echo $row['id'].',';
  echo $row['title'].',';
  echo $row['comment'].',';
  echo '<img src="' . $row['image'] . '" width="60" alt="本の画像"><br>';
  echo $row['date'].'<br />';
  echo "<hr>";
}
?>


