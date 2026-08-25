<?php
//SQL文の接続とテーブル型の作成
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

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
?>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>読書のすゝめ</title>
</head>
<body>
  <div style="display: flex; gap: 20px; align-items: flex-start;">
        <div>
            <h1>読書のすゝめ</h1>
            <h2>~本好きのための本紹介サイト~</h2>
            <br />
            <h3>使い方</h3>
            <ul>
                <li>ユーザー登録・ログイン機能を行う</li>
                <li>自分の紹介したい本をマイリストに追加</li>
                <li>ランダムでおすすめ本の紹介</li>
            </ul>
        </div>

        <!-- ユーザー画面ボタン -->
        <form action="login.php" method="POST">
            <input type="submit" name="submit1" value="ユーザー画面" style="width: 250px; height: 60px; font-size: 20px;">
        </form>
    </div>
    <br />
    
    <!-- ▼ おすすめ本と黄色いボタンを横並びにするレイアウト ▼ -->
    <div style="display: flex; gap: 50px; align-items: center; margin-left: 50px;">
        
        <!-- 左側：おすすめ本の結果表示 -->
        <div>
            <?php
           $sql = 'SELECT * FROM book WHERE title != "" ORDER BY RAND() LIMIT 1';
           $stmt = $pdo->query($sql);

           $random_book = $stmt->fetch();


              if ($random_book) {
                echo "<h3>おすすめの1冊</h3>";
                echo "投稿者: " . $random_book['name'] . "<br>";
                echo "コメント: " . $random_book['comment'] . "<br>";
                echo '<img src="' . $random_book['image'] . '" width="50" alt="本の画像"><br>';
                
                } else {
                echo "まだ本が紹介されていません。";
                }
              ?>
        </div>

        <!-- 右側：黄色のボタン -->
        <form style="text-align: center; margin: 0;">
            <input type="submit" name="submit1" value="?" style="width: 250px; height: 250px; font-size: 20px; background-color: yellow;">
            <br />
            おすすめ本
        </form>
        
    </div>

    

    <h3>＊追加予定</h3>
    <ul>
        <li>気に入った本を購入できる</li>
        <li>ポイント付与</li>
        <li>ポイントが書籍購入カードへ還元</li>
    </ul>
    

</body>     
</html>