<?php


$message = $_POST['DATA'];

$binPath = getcwd().'/src/App/CB/Sherlocks/bin/static/response message='.$message.' pathfile='.getcwd().'/src/App/CB/Sherlocks/param_demo/pathfile';

$infos = exec($binPath);

$tableau = explode('!', $infos);


$code = $tableau[1];
$error = $tableau[2];
$merchant_id = $tableau[3];
$merchant_country = $tableau[4];
$amount = $tableau[5]/100;
$transaction_id = $tableau[6];
$payment_means = $tableau[7];
$transmission_date= $tableau[8];
$payment_time = $tableau[9];
$payment_date = $tableau[10];
$response_code = $tableau[11];
$payment_certificate = $tableau[12];
$authorisation_id = $tableau[13];
$currency_code = $tableau[14];
$card_number = $tableau[15];
$cvv_flag = $tableau[16];
$cvv_response_code = $tableau[17];
$bank_response_code = $tableau[18];
$complementary_code = $tableau[19];
$complementary_info= $tableau[20];
$return_context = $tableau[21];
$caddie = $tableau[22];
$receipt_complement = $tableau[23];
$merchant_language = $tableau[24];
$language = $tableau[25];
$customer_id = $tableau[26];
$order_id = $tableau[27];
$customer_email = $tableau[28];
$customer_ip_address = $tableau[29];
$capture_day = $tableau[30];
$capture_mode = $tableau[31];
$data = $tableau[32];


if ($response_code == "00"){
    $info ='\n=========================\n';
    $info .= 'customer_id : '.$customer_id.'\n';
    $info .= 'transaction_id : '.$transaction_id.'\n';
    $info .= 'amount : '.$amount.'\n';
    $info .= 'payment_means : '.$payment_means.'\n';
    $info .= 'customer_email : '.$customer_email.'\n';
    file_put_contents('paiement_info.json', $info);
}
/*echo "toto";
file_put_contents('paiement_info.json', "toto");*/
