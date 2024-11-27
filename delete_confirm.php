<!-- delete_confirm.php -->
<!-- 削除する内容の確認のみ！ -->
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
?>
    <?php include 'inc/header.php'; ?>

    <title>削除確認</title>
    <style>
        .confirm-container {
            background: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
        }

        .warning {
            color: #dc3545;
            margin-bottom: 20px;
        }

        .item-details {
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

    </style>
</head>

<body>
    <div class="bg"></div>
    <main>
        <div class="confirm-container">
            <h2>削除確認</h2>
            <div class="warning">
                <p>以下の投稿を削除しますか？</p>
                <p>※この操作は取り消せません。</p>
            </div>
            <div class="item-details">
                <p><strong>サイト名：</strong> <?= h($record['pagename']) ?></p>
                <p><strong>URL：</strong> <?= h($record['url']) ?></p>
                <p><strong>コメント：</strong> <?= nl2br(h($record['comment'])) ?></p>
            </div>
            <form action="delete.php" method="post" class="delete-form">
                <!-- idは隠す -->
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="form-group">
                    <label for="password">パスワード確認：</label>
                    <input type="password" name="password" required>
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-delete">削除する</button>
                    <a href="read.php" class="btn btn-cancel">キャンセル</a>
                </div>
            </form>
        </div>
    </main>
    <script src="js/background.js"></script>
</body>
<?php include 'inc/footer.php'; ?>