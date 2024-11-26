<!-- index.php -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/background.css"> <!-- 背景用 -->
    <!-- isset()は変数が定義されているかをチェックする関数 -->
    <!-- つまり$additional_cssが記述されている場合は追加で読み込まれる -->
    <?php if (isset($additional_css)) echo $additional_css; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DotGothic16&display=swap" rel="stylesheet">
    <title>CODE ∞ LINK</title>
</head>

<body>
    <?php
    $page_title = 'CODE ∞ LINK';  // ページのタイトルを設定
    include 'inc/common_head.php';  // 共通のhead部分を読み込み
    include 'inc/header.php';      // ヘッダーを読み込み
    ?>
    <!-- 背景用 -->
    <div class="bg"></div>
    <main>
        <div class="main">
            <div class="greeting">
                コードリンクへようこそ！<br>
                先人たちの知恵を、努力を、みんなで享受し<br>
                新たにセカイを変えていきましょう！
            </div>
            <div class="guide">
                コードの紹介をしたい方は「SHARE」<br>
                コードを見たい、編集したい方は「READ」へ進んでね
            </div>
        </div>
    </main>
    <?php include 'inc/footer.php'; ?>
    <script src="js/background.js"></script> <!-- 背景用のJS -->
</body>

</html>