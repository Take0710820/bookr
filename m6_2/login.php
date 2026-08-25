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


//新規登録//
if (!empty($_POST["name"]) && !empty($_POST["pass"])) { 
   
    $name = $_POST["name"];
    $pass = $_POST["pass"]; 
    $date = date('Y-m-d H:i:s');   
    

    $check_sql = 'SELECT * FROM book WHERE name = :name';
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $check_stmt->execute();
    $exist = $check_stmt->fetch(); 
    
    if ($exist) {
        // すでにデータが見つかった場合
        echo "アカウントがあります。<br>";
    } else {
        // データが見つからなかった場合のみ、新規登録を実行する
        $sql = "INSERT INTO book (name, pass, date) VALUES (:name, :pass, :date)";
        $stmt = $pdo->prepare($sql); 
        
        // 変数の割り当て
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        
        // 実行
        $stmt->execute();

        echo "新規登録が完了しました。<br>";
    }
}
    
    
    if (!empty($_POST["login_name"]) && !empty($_POST["login_pass"])) {
       
       
    $login_name = $_POST["login_name"];
    $login_pass = $_POST["login_pass"];
     
     $sql = 'SELECT * FROM book WHERE name=:name';
     $stmt = $pdo->prepare($sql);
     $stmt->bindParam(':name', $login_name, PDO::PARAM_STR);
     $stmt->execute();
     $results = $stmt->fetchAll(); 
     
     foreach ($results as $row) {
     $db_pass = $row['pass']; 
      
       if ($login_pass === $db_pass) {
        $_SESSION['user_name'] = $login_name;
        header('Location: recommend.php');
        exit;
        
       }else {
        // パスワードが間違っている場合
        echo "パスワードが違います。";
    }
     }
}

?>

<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>読書のすゝめ</title>
</head>
<body>
    <h1>
        読書のすゝめ
    </h1>
    <h2>
        ~本好きのための本紹介サイト~
    </h2>
    <br />
    
      <!--新規作成フォーム-->
    <h3>新規ユーザー登録</h3>
    <form action="" method="post">
      <input type="text" name="name" placeholder="ユーザーネーム"><!--入力欄-->
      <input type="text" name="pass" placeholder="password"> <!--入力欄-->
      
      
     <input type="submit" name="submit1"  value="送信ボタン" > <!--送信ボタン-->
    </form>
    
    　<!--ログインフォーム-->
     <h3>ログイン</h3>
    <form action="" method="post">
      <input type="text" name="login_name" placeholder="ユーザーネーム"><!--入力欄-->
      <input type="text" name="login_pass" placeholder="password"> <!--入力欄-->
      
      
     <input type="submit" name="submit1"  value="送信ボタン" > <!--送信ボタン-->
    </form>
    

<a href="m6_2.php">ホームへ</a>
  
</body>
</html>