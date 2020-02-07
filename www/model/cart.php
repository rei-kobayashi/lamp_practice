<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
  ";
  return fetch_all_query($db, $sql);
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
    AND
      items.item_id = {$item_id}
  ";

  return fetch_query($db, $sql);

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";

  return execute_query($db, $sql);
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = {$amount}
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  return execute_query($db, $sql);
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";

  return execute_query($db, $sql);
}

function purchase_carts($db, $carts,$user_id,$total){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  //トランザクション開始
  $db->beginTransaction();
  try {
  //商品購入処理。購入できないときはエラーメッセージ
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
      }
  }
  //履歴と明細作成処理を呼び出す
  create_result($db,$carts,$user_id,$total);
  //コミット
  $db->commit();
  print'データ登録ができました。';
  } catch (PDOException $e) {
     // ロールバック処理
     $db->rollback();
     // 例外をスロー
     throw $e;
  } print 'データベース処理でエラーが発生しました。理由：'.$e->getMessage();
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";

  execute_query($db, $sql);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

function create_result($db,$carts,$user_id,$total){
  //合計金額を計算 
  $total = 0;
   //foreach
  foreach($carts as $cart){
    $total += $cart['price'] * $cart['amount'];
  
  //add_historiesを呼び出す  履歴のテーブルに情報を入れる処理 order_idないのにどうやって取得するの？
  add_histories($db,$user_id,$total);
  if(add_histories(
      $db,
      $user_id,
      $total
      ) === TRUE ) {
      //order_idを取得
      $order_id = $db->lastInsertId();
      //cartsをループさせてadd_detailsを呼び出す　詳細テーブルに情報を入れる
      foreach($carts as $cart){
        add_details($db,$order_id,$cart['item_id'],$cart['price'],$cart['amount']);
      }
  }
}
function add_histories($db, $user_id, $total){
  $sql = "
    INSERT INTO histories(user_id,total) VALUES(?,?)
  ";
  return execute_query($db, $sql,array($user_id,$total));
}
function add_details($db,$order_id,$cart){
  $sql = "
    INSERT INTO details VALUES(?,?,?,?)
  ";
  return execute_query($db, $sql,array($order_id,$cart['item_id'],$cart['price'],$cart['amount']));
}
