<?php
require_once('my_coin.php');
$coin=new my_coin();
$coin->set_name("Bitcrystal");
$coin->set_prefix("BTCRY");
$coin->set_fee(0.0000002);
$coin->set_feebee($coin->getName());
$coin->set_buy_fee(false);
$coin->set_sell_fee(false);
$coin->set_rpc_settings_coin("WernerChainer", "fickdiehenne", "127.0.0.1", "19332", "", 99999999);
?>