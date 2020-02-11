<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴一覧</title>
  <link rel="stylesheet" href="">
    <style>
      .table{
          border-style: solid gray;
      }
    </style>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>

  <div class="container">
    <h1>購入履歴画面</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
        <table class="table">
            <tr>
                <th>注文番号</th>
                <th>購入日時</th>
                <th>合計金額</th>
                <th></th>
            </tr>
            <?php foreach($infomation as $info){ ?>
            <tr>
                <td><?php print(h($info['order_id'])); ?></td>
                <td><?php print(h($info['purchase_date']));?></td>
                <td><?php print(h($info['total']));?>円</td>
                <td>
                    <form method = "post" action = "detail.php">
                        <input type = "submit" name = "display" value = "購入明細表示">
                        <input type = "hidden" name = "token" value = "<?php print $token;?>">
                        <input type = "hidden" name = "order_id" value = "<?php print (h($info['order_id']));?>">
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>