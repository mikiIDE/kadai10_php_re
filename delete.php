<!-- delete.php -->
<?php
ini_set("display_errors", 1);
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect("read.php");
}

//2. DB接続
// 関数ファイルを読み込む（includeではなくrequire_once推奨。二重呼び込みやエラーの際の実行を避ける）
require_once __DIR__ . '/funcs.php';
// DB接続
$pdo = db_conn();

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
        //以下のmessageはリダイレクトした先（read.php）で表示
        if ($status) {
            $_SESSION['success_message'] = "投稿を削除しました。";
        } else {
            $_SESSION['error_message'] = "削除に失敗しました。";
        }
    } else {
        $_SESSION['error_message'] = "パスワードが正しくありません。";
    }
}
redirect("read.php");
