<?php
require_once('my_coin.php');
$coin=new my_coin();
$coin->set_name("Bitcoin");
$coin->set_prefix("BTC");
$coin->set_fee(0.0000002);
$coin->set_feebee($coin->getName());
$coin->set_buy_fee(false);
$coin->set_sell_fee(false);
$coin->set_rpc_settings_coin("bitcoinrpc", "fickdiehenneextended", "127.0.0.1", "8332", "", 99999999);
?>