<!-- insert.php -->
<!-- DBとの接続と、SQLの作成を担当する -->
<!-- それ以外の仕事はさせない！ -->
<?php
//エラー表示。下記内容をphpファイルすべての頭にくっ付けるとエラーが見える化する。
ini_set("display_errors", 1);
//セッションスタート
session_start();

//1. POSTデータ取得
$pagename = $_POST["pagename"];
$url = $_POST["url"];
$comment = $_POST["comment"];
$password = $_POST["password"];
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

//2. fileopen、ではなく、DB接続します。PHP DATA OBJECTでPDO！
try {
    //Password:MAMP='root',XAMPP='' dbname=自分で作成したdb名, 'root',''
    $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost', 'root', '');
    // $pdo = new PDO('mysql:dbname=einekleine_kadai10_php;charset=utf8;host=*********.db.sakura.ne.jp', 'user-name', '********');
} catch (PDOException $e) {
    exit('DBConnectError:' . $e->getMessage()); //データベース接続で起きたエラーを取得する！
}

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

//３．データ登録SQL作成 vindValueを介することでSQLインジェクションを避けることができる
$sql = "INSERT INTO code_links 
        (pagename, url, sort_html, sort_css, sort_js, sort_api, sort_php, sort_others, comment, password) 
        VALUES 
        (:pagename, :url, :sort_html, :sort_css, :sort_js, :sort_api, :sort_php, :sort_others, :comment, :password)"; //:バインド変数（橋渡し役の変数）
$stmt = $pdo->prepare($sql); //クエリ（要求）をセット。
$stmt->bindValue(':pagename', $pagename, PDO::PARAM_STR); //Integer（文字の場合 PDO::PARAM_STR)（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':sort_html', $sort_html, PDO::PARAM_INT);
$stmt->bindValue(':sort_css', $sort_css, PDO::PARAM_INT);
$stmt->bindValue(':sort_js', $sort_js, PDO::PARAM_INT);
$stmt->bindValue(':sort_api', $sort_api, PDO::PARAM_INT);
$stmt->bindValue(':sort_php', $sort_php, PDO::PARAM_INT);
$stmt->bindValue(':sort_others', $sort_others, PDO::PARAM_INT);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);  // ハッシュ値なので（＝英数字なので）STR
$status = $stmt->execute(); //クエリ（要求）実行役。trueかfalseが返ってくる

//４．データ登録処理後
if ($status == false) {
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("SQLError:" . $error[2]);
} else {
    $_SESSION['success_message'] = "以下の情報を登録しました！";
    header("Location:confirm.php");
    exit();
}
?>
