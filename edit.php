<!-- edit.php -->
<?php
ini_set("display_errors", 1);
session_start();

//直接このページを見に来た場合はリダイレクトする
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect("read.php");
}

// POSTデータ取得
$id = $_POST["id"];
$pagename = $_POST["pagename"];
$url = $_POST["url"];
$comment = $_POST["comment"];
$password = $_POST["password"];

try {
    require_once __DIR__ . '/funcs.php';
    $pdo = db_conn();
    
    // 既存のパスワードを取得
    $sql = "SELECT password FROM code_links WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $status = $stmt->execute();

    if ($status) {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$record || !password_verify($password, $record['password'])) {
            // パスワードが一致しない場合
            $_SESSION['error_message'] = "パスワードが正しくありません。";
            redirect("read.php");
        }
    }
} catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
}

// チェックボックスの値を取得（チェックされていない場合は0をセット）
// isset()は変数が定義されているかをチェックする関数
$sort_html = isset($_POST["sort_html"]) ? 1 : 0;
$sort_css = isset($_POST["sort_css"]) ? 1 : 0;
$sort_js = isset($_POST["sort_js"]) ? 1 : 0;
$sort_api = isset($_POST["sort_api"]) ? 1 : 0;
$sort_php = isset($_POST["sort_php"]) ? 1 : 0;
$sort_others = isset($_POST["sort_others"]) ? 1 : 0;
// パスワードのハッシュ化
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ※追加※表示用にセッションに保存
$_SESSION['form_data'] = [
    'pagename' => $pagename,
    'url' => $url,
    'comment' => $comment,
    'sort_html' => $sort_html,
    'sort_css' => $sort_css,
    'sort_js' => $sort_js,
    'sort_api' => $sort_api,
    'sort_php' => $sort_php,
    'sort_others' => $sort_others
];

// データ登録SQL作成 vindValueを介することでSQLインジェクションを避けることができる
$sql = "UPDATE code_links SET pagename=:pagename, url=:url, sort_html=:sort_html, sort_css=:sort_css, sort_js=:sort_js, sort_api=:sort_api, sort_php=:sort_php, sort_others=:sort_others, comment=:comment WHERE id=:id"; //:バインド変数（橋渡し役の変数）
$stmt = $pdo->prepare($sql); //クエリ（要求）をセット。
$stmt->bindValue(':id', $id, PDO::PARAM_INT); 
$stmt->bindValue(':pagename', $pagename, PDO::PARAM_STR); //Integer（文字の場合 PDO::PARAM_STR)（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':sort_html', $sort_html, PDO::PARAM_INT);
$stmt->bindValue(':sort_css', $sort_css, PDO::PARAM_INT);
$stmt->bindValue(':sort_js', $sort_js, PDO::PARAM_INT);
$stmt->bindValue(':sort_api', $sort_api, PDO::PARAM_INT);
$stmt->bindValue(':sort_php', $sort_php, PDO::PARAM_INT);
$stmt->bindValue(':sort_others', $sort_others, PDO::PARAM_INT);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$status = $stmt->execute(); //クエリ（要求）実行役。trueかfalseが返ってくる

//４．データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    $_SESSION['success_message'] = "更新が完了しました！";
    redirect("confirm.php");
}