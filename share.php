<!-- share.php -->
<!-- 投稿をする画面（post.phpに相当する） -->


<body>
    <?php
    $page_title = '魔法分配！';  // ページのタイトルを設定
    include 'inc/header.php';
    ?>
    <!-- 背景用 -->
    <div class="bg"></div>
    <main>
        <!-- Main[Start] -->
        <div class="container">
            <h2>魔法のレシピ</h2>
            <form action="insert.php" method="post">
                <div class="form-group">
                    <label for="pagename">サイト名：</label>
                    <input type="text" id="pagename" name="pagename" required>
                </div>
                <div class="form-group">
                    <label for="url">URL：</label>
                    <input type="url" id="url" name="url" required>
                </div>
                <div class="form-group">
                    <label for="sort">ジャンル：</label>
                    <!-- value="0"でもいいけど、"1"が慣例的に分かりやすいらしい -->
                    <input type="checkbox" name="sort_html" value="1">HTML
                    <input type="checkbox" name="sort_css" value="1">CSS
                    <input type="checkbox" name="sort_js" value="1">JavaScript
                    <input type="checkbox" name="sort_api" value="1">API
                    <input type="checkbox" name="sort_php" value="1">PHP
                    <input type="checkbox" name="sort_others" value="1">others
                </div>
                <div class="form-group">
                    <label for="comment">一言：</label><br>
                    <textarea id="comment" name="comment" rows="10" cols="40" placeholder="内容や紹介文をどうぞ！！" required></textarea>
                </div>
                <div class="form-group">
                    <label for="password">パスワード：</label>
                    <input type="text" id="password" name="password" placeholder="編集・削除用のパスワードをいれてください" required>
                </div>
                <button type="submit" class="submit-btn">掲載</button>
            </form>
        </div>
    </main>
    <!-- Main[End] -->

    <?php include 'inc/footer.php'; ?>
    <script src="js/background.js"></script> <!-- 背景用のJS -->
</body>

</html>