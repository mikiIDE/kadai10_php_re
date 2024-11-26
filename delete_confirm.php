<!-- delete_confirm.php -->
<!-- 削除する内容の確認のみ！ -->
<?php
ini_set("display_errors", 1);
session_start();

// DB接続
try {
    $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost', 'root', '');
    // $pdo = new PDO('mysql:dbname=einekleine_kadai10_php;charset=utf8;host=*********.db.sakura.ne.jp', 'user-name', '********');
} catch (PDOException $e) {
    exit('DBConnection Error:' . $e->getMessage());
}

// idを取得（GETメソッドに修正）
// ここでは情報の表示のみ、削除操作などは行わないためGETが適切とのこと（設計原則REST）
$id = isset($_GET['id']) ? $_GET['id'] : '';

// IDが空の場合は一覧ページへリダイレクト
if (!$id) {
    header("Location: read.php");
    exit;
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
        header("Location: read.php");
        exit;
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

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
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
                <p><strong>サイト名：</strong> <?= htmlspecialchars($record['pagename']) ?></p>
                <p><strong>URL：</strong> <?= htmlspecialchars($record['url']) ?></p>
                <p><strong>コメント：</strong> <?= nl2br(htmlspecialchars($record['comment'])) ?></p>
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
    <?php include 'inc/footer.php'; ?>
    <script src="js/background.js"></script>
</body>

</html>