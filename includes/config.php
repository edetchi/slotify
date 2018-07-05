<?php
    $db_type = "mysql";
    $db_host = "localhost";
    $db_name = "slotify";
    $db_user = "root";
    $db_pass = "root";
    $dsn = "{$db_type}:host={$db_host};dbname={$db_name};charset=utf8";
    try {
        $pdo = new PDO($dsn, $db_user, $db_pass);
        //エラーモード設定
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //プリペアドステートメント用意
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        //デバッグ用
        print "接続完了<br>";
    } catch (PDOException $e) {
        //エラー発生時処理停止してエラー表示
        die("エラー: " . $e->getMessage());
    }

?>