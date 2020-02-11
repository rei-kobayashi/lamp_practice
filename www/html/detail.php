<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/history.php';
require_once '../model/user.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$order_id = get_post('order_id');
$header = get_history($db,$order_id);
$user = get_login_user($db);
//入力チェック

//------------------------------------------------
    $infomation = get_details($db,$order_id);
//------------------------------------------------
$token = get_csrf_token();


include_once VIEW_PATH . 'detail_view.php';