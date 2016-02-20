<?php
require_once('coins_class.php');
$my_coins_names="";
$init_feebee_account="";

//Only needed if you want add your own coins
//The array must dividable through 3 otherwise you get a error
$my_coins_names="Dogecoin,Bitcrystal,Litecoin,BitQuark,Bitcrystal,Karmacoin,Bitcoin,Bitcrystal,Pandacoin";
$my_coins_names=explode(",",$my_coins_names);
$init_feebee_account=false;
$my_coins = w_coins::get($my_coins_names,$init_feebee_account);
?>
