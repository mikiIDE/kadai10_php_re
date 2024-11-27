<!-- read.php -->
<?php
ini_set("display_errors", 1);
session_start();

// メッセージ表示の処理を追加
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// メッセージを表示したら、セッションから消去
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

//2. DB接続
// 関数ファイルを読み込む（includeではなくrequire_once推奨。二重呼び込みやエラーの際の実行を避ける）
require_once __DIR__ . '/funcs.php';
// DB接続
$pdo = db_conn();

//２．データ登録SQL作成
$sql = "SELECT * FROM code_links";
$stmt = $pdo->prepare($sql); //ここを$connに変更
$status = $stmt->execute(); //クエリの実行（ここは変わらず）

//３．データ表示
$values = [];
if ($status == false) {
    sql_error($stmt);
} else {
    $values = $stmt->fetchAll();
}
//JSONエンコードして出力（今回は不要）（JavaScriptに渡す場合などに使う？）
//$json = json_encode($values, JSON_UNESCAPED_UNICODE);
?>

<title>魔法のレシピ</title>

<style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
    }

    /* フィルターボタン */
    .filter-buttons {
        margin: 20px 0;
        text-align: center;
    }

    .filter-btn {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid #6c757d;
        padding: 8px 16px;
        margin: 5px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .filter-btn.active {
        background: #6c757d;
        color: white;
    }

    /* カードグリッド */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-title {
        font-size: 1.2em;
        margin-bottom: 10px;
        color: #333;
    }

    .card-link {
        color: #007bff;
        text-decoration: none;
        word-break: break-all;
    }

    .card-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin: 10px 0;
    }

    .tag {
        background: #e9ecef;
        padding: 3px 8px;
        border-radius: 15px;
        font-size: 0.8em;
    }

    .card-comment {
        margin-top: 10px;
        font-size: 0.9em;
        line-height: 1.6;
        color: #666;
    }

    /* レスポンシブ対応 */
    @media (max-width: 768px) {
        .cards-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }
</style>
<?php include 'inc/header.php'; ?>

<body>
    <!-- 背景用のbg -->
    <div class="bg"></div>

    <main>
        <div class="container">
            <!-- 削除・編集操作を行った場合のメッセージを表示 -->
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?= h($success_message) ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger">
                    <?= h($error_message) ?>
                </div>
            <?php endif; ?>
            <!-- フィルターボタン -->
            <!-- data-filter="選択されているもの" -->
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="html">HTML</button>
                <button class="filter-btn" data-filter="css">CSS</button>
                <button class="filter-btn" data-filter="js">JavaScript</button>
                <button class="filter-btn" data-filter="php">PHP</button>
                <button class="filter-btn" data-filter="api">API</button>
                <button class="filter-btn" data-filter="others">Others</button>
            </div>

            <!-- カードグリッド -->
            <div class="cards-grid">
                <?php foreach ($values as $v): ?>
                    <!-- data-html等の「data...」はカスタムデータ属性。実際に表示などはせず、データを持たせるためのもの -->
                    <div class="card"
                        data-html="<?= $v['sort_html'] ?>"
                        data-css="<?= $v['sort_css'] ?>"
                        data-js="<?= $v['sort_js'] ?>"
                        data-php="<?= $v['sort_php'] ?>"
                        data-api="<?= $v['sort_api'] ?>"
                        data-others="<?= $v['sort_others'] ?>">
                        <h3 class="card-title"><?= h($v['pagename']) ?></h3>
                        <a href="<?= h($v['url']) ?>" class="card-link" target="_blank"><?= h($v['url']) ?></a>

                        <div class="card-tags">
                            <!-- 条件：それがあるときのみspanをつけるよ、ってやつ -->
                            <?php if ($v['sort_html']): ?><span class="tag">HTML</span><?php endif; ?>
                            <?php if ($v['sort_css']): ?><span class="tag">CSS</span><?php endif; ?>
                            <?php if ($v['sort_js']): ?><span class="tag">JavaScript</span><?php endif; ?>
                            <?php if ($v['sort_php']): ?><span class="tag">PHP</span><?php endif; ?>
                            <?php if ($v['sort_api']): ?><span class="tag">API</span><?php endif; ?>
                            <?php if ($v['sort_others']): ?><span class="tag">Others</span><?php endif; ?>
                        </div>

                        <div class="card-comment">
                            <!-- nl2br → テキスト入力内の改行をbrタグに変換できる -->
                            <?= nl2br(h($v['comment'])) ?>
                        </div>
                        <button type="button" class="delete" onclick="confirmDelete(<?= $v['id'] ?>)">削除</button>
                        <button type="button" class="edit" onclick="confirmEdit(<?= $v['id'] ?>)">編集</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // タグの切り替え
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;

                    cards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = '';
                        } else {
                            if (card.dataset[filter] === "1") {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
        //削除操作用の関数
        function confirmDelete(id) {
            window.location.href = `delete_confirm.php?id=${id}`;
        }
        //編集操作用の関数
        function confirmEdit(id) {
            window.location.href = `edit_confirm.php?id=${id}`;
        }
    </script>
    <script src="js/background.js"></script>
</body>
<?php include 'inc/footer.php'; ?>