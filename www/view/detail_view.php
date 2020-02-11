<!DOCTYPE html>
<html lang="ja">

<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>購入詳細画面</title>
    <link rel="stylesheet" href="">
    <style>
        .table {
            border-style: solid gray;
        }
    </style>
</head>

<body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>

    <div class="container">
        <h1>購入詳細</h1>
        <?php include VIEW_PATH . 'templates/messages.php'; ?>
        <div class="container">
            <p>注文番号:<?php print(h($header['order_id'])) ?></p>
            <p>購入日時:<?php print(h($header['purchase_date'])) ?></p>
            <p>合計金額:<?php print(h($header['total'])); ?>円</p>
            <table class="table">
                <tr>
                    <th>商品名</th>
                    <th>商品価格</th>
                    <th>購入数</th>
                    <th>小計</th>
                </tr>
                <?php foreach ($infomation as $info) { ?>
                    <tr>
                        <td><?php print(h($info['name'])); ?></td>
                        <td><?php print(h($info['price'])); ?>円</td>
                        <td><?php print(h($info['amount'])); ?>個</td>
                        <td><?php print(h($info['sub_total'])); ?>円</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
</body>

</html>