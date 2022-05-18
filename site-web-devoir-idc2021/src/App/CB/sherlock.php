<?php


use \Miniframework\App\CB\SherlockPay;

$paiementCB = new SherlockPay();

$paiementCB->vads_order_id = (int)  rand(1, 999999);

$paiementCB->vads_site_id = "00000000"; // dispo en BO systempay

$paiementCB->merchant_key = "0000000000000000"; // dispo en BO systempay

$paiementCB->vads_ctx_mode = 'TEST'; //(obligatoire) TEST ou PRODUCTION
$paiementCB->vads_amount = 1000; //(obligatoire) en cents, ici 10 EUR

$ts = time();
$paiementCB->vads_trans_date = gmdate("YmdHis", $ts); //(obligatoire) format AAAAMMJJHHMMSS
$paiementCB->vads_trans_id = gmdate("His", $ts);


$paiementCB->vads_url_success   = "http://".$_SERVER['SERVER_NAME']."/retour.php?result=OK";
$paiementCB->vads_url_referral  = "http://".$_SERVER['SERVER_NAME']."/retour.php?result=referral";
$paiementCB->vads_url_refused   = "http://".$_SERVER['SERVER_NAME']."/retour.php?result=NOK";
$paiementCB->vads_url_cancel    = "http://".$_SERVER['SERVER_NAME']."/retour.php?result=cancel";
$paiementCB->vads_url_error     = "http://".$_SERVER['SERVER_NAME']."/retour.php?result=error";
$paiementCB->vads_url_return    = "http://".$_SERVER['SERVER_NAME']."/retour.php";