<!-- 共通のヘッダー設定部分 -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/share.css">
    <link rel="stylesheet" href="css/background.css"> <!-- 背景用 -->
       <!-- isset()は変数が定義されているかをチェックする関数 -->
       <!-- つまり$additional_cssが記述されている場合は追加で読み込まれる -->
    <?php if (isset($additional_css)) echo $additional_css; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DotGothic16&display=swap" rel="stylesheet">
    <title><?php echo $page_title ?? 'CODE ∞ LINK'; ?></title>
    <!-- もしページタイトルがnullの場合はCODE ∞ LINKを使い、何か設定されていればそのタイトルになる！ -->
</head>
    