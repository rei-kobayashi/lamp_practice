<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>カートの中身</title>
</head>
<body>
<?php if (count($err_msg) === 0) { ?>
  <h1>購入画面</h1>
   <p>以下の商品の購入が完了しました</p>
    <div id="flex">
<?php foreach ($carts as $cart)  { ?>
<form action="" method="post">
      <div class="products">
        <span class="img_size"><img src="<?php print $img_dir . $value['img']; ?>"></span>
        <span><?php print $value['name']; ?></span>
        <span><?php print $value['price']; ?>円</span>
<form action="" method="post">
        <input type="text" name="amount" value="<?php print $value['amount']; ?>">個
        <input type="submit" value="数量変更">
        <input type="hidden" name="sql_kind" value="change">
        <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
</form>    
<form action="" method="post">
        <input type="submit" value="削除">
        <input type="hidden" name="sql_kind" value="delete">
        <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
</form>

      </div>
<?php } ?>
     </div>
    
    </div>
<?php } else { ?>
<?php foreach ($err_msg as $value) { ?>
    <p><?php print $value; ?></p>
<?php } ?>
<?php } ?>
</body>
</html>