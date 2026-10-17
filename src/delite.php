<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS book"
       ." ("
       ."id INT AUTO_INCREMENT PRIMARY KEY,"
       ."name CHAR(32),"
       ."comment TEXT"
       .");";
       
$stmt = $pdo->query($sql);

$sql = 'DROP TABLE book';
$stmt = $pdo->query($sql);
?>