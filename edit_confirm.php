<!-- edit_confirm.php -->
<?php
ini_set("display_errors", 1);
session_start();

//2. DB接続
// 関数ファイルを読み込む（includeではなくrequire_once推奨。二重呼び込みやエラーの際の実行を避ける）
require_once __DIR__ . '/funcs.php';
// DB接続
$pdo = db_conn();

// idを取得（GETメソッドに修正）
// ここでは情報の表示のみ、削除操作などは行わないためGETが適切とのこと（設計原則REST）
$id = isset($_GET['id']) ? $_GET['id'] : '';

// IDが空の場合は一覧ページへリダイレクト
if (!$id) {
    redirect("read.php");
}

// 指定されたIDから残りのデータを取得
$sql = "SELECT * FROM code_links WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

// データ取得の追加
if ($status) {
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    // データが見つからない場合は一覧ページへリダイレクト
    if (!$record) {
        redirect("read.php");
    }
} else {
    exit("Error occurred.");
}
$page_title = '魔法変更！';  // ページのタイトルを設定
include 'inc/header.php';
?>

<body>
    <!-- 背景用 -->
    <div class="bg"></div>
    <main>
        <!-- Main[Start] -->
        <div class="container">
            <h2>魔法のレシピ</h2>
            <form action="edit.php" method="post">
                <!-- idは隠す -->
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="form-group">
                    <label for="pagename">サイト名：</label>
                    <input type="text" id="pagename" name="pagename" value="<?= h($record['pagename']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="url">URL：</label>
                    <input type="url" id="url" name="url" value="<?= h($record['url']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="sort">ジャンル：</label>
                    <!-- 三項演算子 ?= $record['sort_html'] ? 'checked' : '' ? sortの値が1の場合、checked属性が付与される仕組み -->
                    <input type="checkbox" name="sort_html" value="1" <?= $record['sort_html'] ? 'checked' : '' ?>>HTML
                    <input type="checkbox" name="sort_css" value="1" <?= $record['sort_css'] ? 'checked' : '' ?>>CSS
                    <input type="checkbox" name="sort_js" value="1" <?= $record['sort_js'] ? 'checked' : '' ?>>JavaScript
                    <input type="checkbox" name="sort_api" value="1" <?= $record['sort_api'] ? 'checked' : '' ?>>API
                    <input type="checkbox" name="sort_php" value="1" <?= $record['sort_php'] ? 'checked' : '' ?>>PHP
                    <input type="checkbox" name="sort_others" value="1" <?= $record['sort_others'] ? 'checked' : '' ?>>others
                </div>
                <div class="form-group">
                    <label for="comment">一言：</label><br>
                    <textarea id="comment" name="comment" rows="10" cols="40"><?= h($record['comment']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="password">パスワード確認：</label>
                    <input type="text" id="password" name="password" required>
                </div>
                <button type="submit" class="submit-btn">編集して掲載</button>
                <a href="read.php" class="btn btn-cancel">キャンセル</a>
            </form>
        </div>
    </main>
    <!-- Main[End] -->
    <script src="js/background.js"></script> <!-- 背景用のJS -->
</body>
<?php include 'inc/footer.php'; ?>