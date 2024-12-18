<!-- confirm.php 情報取得と簡易表示のページ -->
<?php
ini_set("display_errors", 1);
session_start();
require_once __DIR__ . '/funcs.php';

// セッションに保存されたデータがない場合はシェア画面へリダイレクト
//（更新ボタンとか押下するとshare.phpに戻る）
if (!isset($_SESSION['form_data'])) {
    redirect("share.php");
}

// セッションからデータを取得
$data = $_SESSION['form_data'];
?>

<title>登録完了</title>
<style>
    .confirm-container {
        background: rgba(170, 210, 227, 0.3);
        padding: 20px;
        border-radius: 10px;
        margin: 20px auto;
        max-width: 800px;
        color: #2a383e;
    }

    .success-message {
        background-color: rgba(190, 200, 220, 0.9);
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
    }

    .confirm-item {
        margin: 10px 0;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .label {
        font-weight: bold;
    }

    .btn {
        background-color: rgba(190, 200, 220, 0.9);
        color: #2a383e;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: rgba(170, 190, 210, 0.9);
    }

    a {
        text-decoration: none;
        color: black;
    }

    /* ポップアップ用 */
#popup-wrapper {
    background-color: rgba(0, 0, 0, .5);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    z-index: 9999; /* 追加 */
  }
  
  #popup-inside {
    text-align: center;
    width: 100%;
    max-width: 300px;
    background: white;
    margin: 10% auto;
    padding: 20px;
    position: relative;
  }
  
  #close {
    position: absolute;
    top: 0;
    right: 5px;
    cursor: pointer;
  }
</style>
<?php include 'inc/header.php'; ?>

<body>
    <div class="bg"></div>

    <main>
        <div class="confirm-container">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success-message" data-show-popup="true">  <!-- data属性を追加 -->
                    <?php
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="confirm-item">
                <span class="label">サイト名：</span>
                <?php echo h($data['pagename']); ?>
            </div>

            <div class="confirm-item">
                <span class="label">URL：</span>
                <a href="<?php echo h($data['url']); ?>" target="_blank">
                    <?php echo h($data['url']); ?>
                </a>
            </div>

            <div class="confirm-item">
                <span class="label">ジャンル：</span>
                <?php
                $genres = [];
                if ($data['sort_html']) $genres[] = 'HTML';
                if ($data['sort_css']) $genres[] = 'CSS';
                if ($data['sort_js']) $genres[] = 'JavaScript';
                if ($data['sort_api']) $genres[] = 'API';
                if ($data['sort_php']) $genres[] = 'PHP';
                if ($data['sort_others']) $genres[] = 'Others';
                echo implode(', ', $genres);
                ?>
            </div>
            <div class="confirm-item">
                <span class="label">一言：</span>
                <!-- nl2br = テキストで入力された改行をbrへ変換！ -->
                <?php echo nl2br(h($data['comment'])); ?>
            </div>

            <div class="text-center" style="margin-top: 20px;">
                <a href="read.php" class="btn">一覧を見る</a>
            </div>
        </div>
        <!-- ポップアップ -->
        <div id="popup-wrapper">
            <div id="popup-inside">
                <div id="close">x</div>
                <div id="message">
                    <h2>THANK YOU !!</h2>
                    <img src="img/halloween_nekomajo.png" width=300 height=auto alt="猫の魔法使い">
                </div>
            </div>
        </div>
    </main>
    <script src="js/background.js"></script>
</body>
<?php
include 'inc/footer.php';
// 表示が終わったらセッションデータを削除
unset($_SESSION['form_data']);
?>
<script>
    const popupWrapper = document.getElementById('popup-wrapper');
    const close = document.getElementById('close');
    const successMessage = document.querySelector('[data-show-popup="true"]');

    // ページ読み込み時にポップアップを表示
    window.onload = function() {
        if (successMessage) {
            popupWrapper.style.display = "block";
        }
    };

    // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
    popupWrapper.addEventListener('click', e => {
        if (e.target.id === popupWrapper.id || e.target.id === close.id) {
            popupWrapper.style.display = 'none';
        }
    });
</script>