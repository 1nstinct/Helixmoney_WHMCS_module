<?php
/**
 * @author 1nstinct <yura.syedin@gmail.com>
 * Date: 6/15/15
 * Time: 4:26 PM
 * @version 1.0
 */

function helixmoney_config() {
    $configarray = array(
        "FriendlyName" => array("Type" => "System", "Value"=>"Helix Money"),
        "shop_id" => array("FriendlyName" => "Shop ID", "Type" => "text", "Size" => "4", "Description" => "Shop ID, defined in shop settings"),
        "password1" => array("FriendlyName" => "Password 1", "Type" => "text", "Size" => "40", "Description" => "Defined in shop settings as Password 1"),
        "password2" => array("FriendlyName" => "Password 2", "Type" => "text", "Size" => "40", "Description" => "Defined in shop settings as Password 2"),
        "paysystem_id" => array("FriendlyName" => "Pay System ID", "Type" => "dropdown", "Options" => "4,5,6,7,8,9",  "Description" => "4 - HELIX MONEY USD; 5 - HELIX MONEY RUR; 6 - HELIX MONEY UAH; 7 - HELIX MONEY EUR; 8 - Perfect Money USD; 9 - Perfect Money EUR", "Default" => "4")
    );
    return $configarray;
}

function helixmoney_link($params) {
    global $_POST;

    # Gateway Specific Variables
    $gateway_shop_id = $params['shop_id'];
    $gateway_password1 = $params['password1'];
    $gateway_paysystem = $params['paysystem_id'];
    $gateway_operation = $params['invoiceid']; // invoice ID

    # URLS
    $return_url = $params['returnurl'];
    $system_url = $params['systemurl'];
    $notify_url = $params['systemurl'].'/modules/gateways/callback/helixmoney.php';

    # Invoice Variables
    $amount = $params['amount']; # Format: ##.##

    # Your variables
    $hash = md5($gateway_shop_id.':'.$gateway_paysystem.':'.$gateway_operation.':'.$amount.':'.$gateway_password1);  // Shop subscription
    $note = 'Client ID - '.$params['clientdetails']['userid'].'; Client name -'.$params['clientdetails']['firstname'].' '.$params['clientdetails']['lastname'].'; Invoice ID - '.$invoiceid = $params['invoiceid'].'; Description - '.$params["description"];


    $code = '
        <form method="post" action="https://shop.helixmoney24.com/ru/payment">
        <input type="hidden" name="shop_id" value="'.$gateway_shop_id.'">
        <input type="hidden" name="paysystem_id" value="'.$gateway_paysystem.'">
        <input type="hidden" name="operation_id" value="'.$gateway_operation.'">
        <input type="hidden" name="note" value="'.$note.'">
        <input type="hidden" name="amount" value="'.$amount.'">
        <input type="hidden" name="hash" value="'.$hash.'">
        <input type="hidden" name="encoding" value="utf-8">
        <input type="submit" value="Submit">
        </form>
    ';

    return $code;
}