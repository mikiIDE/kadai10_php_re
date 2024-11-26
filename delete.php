<?php
ini_set("display_errors", 1);
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: read.php");
    exit;
}

// DB接続
try {
    $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost', 'root', '');
    // $pdo = new PDO('mysql:dbname=einekleine_kadai10_php;charset=utf8;host=*********.db.sakura.ne.jp', 'user-name', '********');
} catch (PDOException $e) {
    exit('DBConnection Error:' . $e->getMessage());
}

// POSTデータ取得
$id = $_POST['id'];
$password = $_POST['password'];

// テーブルからパスワード確認
$sql = "SELECT password FROM code_links WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status) {
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record && password_verify($password, $record['password'])) {
        // パスワードが一致したら削除実行
        $sql = "DELETE FROM code_links WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $status = $stmt->execute();

        if ($status) {
            $_SESSION['success_message'] = "投稿を削除しました。";
        } else {
            $_SESSION['error_message'] = "削除に失敗しました。";
        }
    } else {
        $_SESSION['error_message'] = "パスワードが正しくありません。";
    }
}

header("Location: read.php");
exit;
