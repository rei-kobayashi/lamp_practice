<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//admin
function get_histories($db, $user)
{
    $pram = [];
    $sql = "
            SELECT
                order_id,
                user_id,
                purchase_date,
                total
            FROM
                histories
            
            ";
    if (is_admin($user) === FALSE) {
        $sql .= "WHERE user_id = ?";
        $pram = [$user['user_id']];
    }
    return fetch_all_query($db, $sql, $pram);
}
function get_details($db,$order_id){
    $sql = "
            SELECT
                name,
                details.price,
                amount,
                details.price * amount AS sub_total
            FROM
                details
            INNER JOIN
                items
            ON
                details.item_id = items.item_id
            WHERE
                order_id = ?
            ";
    return fetch_all_query($db,$sql,[$order_id]);
}
function get_history($db, $order_id)
{
    $sql = "
            SELECT
                order_id,
                user_id,
                purchase_date,
                total
            FROM
                histories
            WHERE
                order_id = ?
            ";
    
    return fetch_query($db, $sql, [$order_id]);
}
function get_ranking($db){
    $sql = "
    SELECT
        items.name,
        items.price,
        items.image,
        sum(details.amount) AS amount
    FROM
        items
    JOIN
        details
    ON
        items.item_id = details.item_id
    WHERE
        status = 1
    GROUP BY
        items.name,
        items.price,
        items.image
    ORDER BY
        amount DESC
    LIMIT
        3";
    return fetch_all_query($db,$sql);
}