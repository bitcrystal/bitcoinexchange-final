<?php
require'database.php';
require_once'functions_main.php';
include'coins.php';

$server_url = get_exchange_url();      // url to the exchange
$script_title = "[zelles/Werris] Bitcoin Exchange";  // title of the exchange

$CSS_Stylesheet = '<link rel="stylesheet" type="text/css" href="stylesheet.css">';  // a global style sheet
$ip = $_SERVER['REMOTE_ADDR'];
$date = date("n/j/Y g:i a");;

$db_handle = mysql_connect($dbdb_host,$dbdb_user,$dbdb_pass)or die("Server error.");
$db_found = mysql_select_db($dbdb_database)or die("Server error.");

//echo $my_coins->coins_names_prefix[2];
$coin_selected = $my_coins->coinSelecter($_SESSION['trade_coin']);
if(!$coin_selected) {
   $_SESSION['trade_coin'] = $my_coins->coinSelecter($my_coins->coins_names_prefix[0]);    // default trade section to load when user first arrives
   header("Location: home.php");
}
$trade_coin = $my_coins->coinSelecterSelect($_SESSION['trade_coin']);
$BTC = $my_coins->trade_coins["BTCRY"]["BTC"]; // rate coin
$BTCRYX = $my_coins->trade_coins["BTCRY"]["BTCRYX"]; // amount coin
$BTCS = $my_coins->trade_coins["BTCRY"]["BTCS"]; // rate coin name
$BTCRYXS = $my_coins->trade_coins["BTCRY"]["BTCRYXS"]; // amount coin name

/*$coin0rpc = $coins[$my_coins->coins_names[0]]["rpcsettings"];
$coin1rpc = $coins[$my_coins->coins_names[1]]["rpcsettings"];
$coin2rpc = $coins[$my_coins->coins_names[2]]["rpcsettings"];
set_coins_daemon($my_coins->coins_names[0], $coin0rpc["user"], $coin0rpc["pass"], $coin0rpc["host"], $coin0rpc["port"]);
set_coins_daemon($my_coins->coins_names[1], $coin1rpc["user"], $coin1rpc["pass"], $coin1rpc["host"], $coin1rpc["port"]);
set_coins_daemon($my_coins->coins_names[2], $coin2rpc["user"], $coin2rpc["pass"], $coin2rpc["host"], $coin2rpc["port"]);
*/
$rv=$my_coins->getBitcoindDaemons();
$Bitcoind = $rv[0];
//print_r($rv[1]);
$rv=$my_coins->getBitcrystaldDaemons();
$Bitcrystald = $rv[0];
//print_r($rv[1]);
$rv=$my_coins->getBitcrystalxdDaemons();
$Bitcrystalxd = $rv[0];
//print_r($rv[1]);
//print_r($Bitcoind);
//print_r($Bitcrystald);
//print_r($Bitcrystalxd);
$count_daemons = $rv[1];
$Bitcoind_Account_Address=array();
$Bitcrystald_Account_Address=array();
$Bitcrystalxd_Account_Address=array();
$Bitcoind_Balance=array();
$Bitcrystald_Balance=array();
$Bitcrystalxd_Balance=array();

$user_session = $_SESSION['user_session'];
if(!$user_session) {
   $Logged_In = 2;
} else {
   $Logged_In = 7;
   $Logged_In = 7;
   //$wallet_id = "zellesExchange(".$user_session.")";
	$string="";
	$i=0;
	for(;$i<$count_daemons;$i++)
	{
		$wallet_id = $my_coins->getWalletId($user_session,$Bitcoind[$i]["cid"]);
		$Bitcoind_Account_Address[$i] = $Bitcoind[$i]["daemon"]->getaccountaddress($wallet_id);
		$wallet_id = $my_coins->getWalletId($user_session,$Bitcrystald[$i]["cid"]);
		$Bitcrystald_Account_Address[$i] = $Bitcrystald[$i]["daemon"]->getaccountaddress($wallet_id);
		$wallet_id = $my_coins->getWalletId($user_session,$Bitcrystalxd[$i]["cid"]);
		$Bitcrystalxd_Account_Address[$i] = $Bitcrystalxd[$i]["daemon"]->getaccountaddress($wallet_id);
		$string=$string . "'".$Bitcoind_Account_Address[$i]."','".$Bitcrystald_Account_Address[$i]."','".$Bitcrystalxd_Account_Address[$i]."'";
		if($i+1<$count_daemons)
		{
			$string = $string .",";
		}
	}
		$i=$i*3;
	    if($i<10)
			$string = $string . ",";
		for(;$i<10;$i++)
		{
			$string = $string . "'0'";
			if($i+1<10)
				$string = $string .",";
		}
		$SQL = "SELECT * FROM balances WHERE username='$user_session' and trade_id = '".$my_coins->getTradeIdAccount()."'";
		$result = mysql_query($SQL);
		$num_rows = mysql_num_rows($result);
		if($num_rows!=1) {
			if(!mysql_query("INSERT INTO balances (id,username,coin1,coin2,coin3,coin4,coin5,coin6,coin7,coin8,coin9,coin10,trade_id) VALUES ('','$user_session','0','0','0','0','0','0','0','0','0','0','".$my_coins->getTradeIdAccount()."')")) {
				die("Server error");
			} else {
				$r_system_action = "success";
			}
		}
		$SQL = "SELECT * FROM addresses WHERE username='$user_session' and trade_id = '".$my_coins->getTradeIdAccount()."'";
		$result = mysql_query($SQL);
		$num_rows = mysql_num_rows($result);
		if($num_rows!=1) {
			if(!mysql_query("INSERT INTO addresses (id,username,coin1,coin2,coin3,coin4,coin5,coin6,coin7,coin8,coin9,coin10,trade_id) VALUES ('','$user_session',".$string.",'".$my_coins->getTradeIdAccount()."')")) {
				die("Server error");
			} else {
				$r_system_action = "success";
			}
		}

		for($i=0;$i<$count_daemons;$i++)
		{
			$wallet_id = $my_coins->getWalletId($user_session,$Bitcoind[$i]["cid"]);
			$Bitcoind_List_Transactions = $Bitcoind[$i]["daemon"]->listtransactions($wallet_id,50);
   
			foreach($Bitcoind_List_Transactions as $Bitcoind_List_Transaction) {
				if($Bitcoind_List_Transaction['category']=="receive") {
					if(6<=$Bitcoind_List_Transaction['confirmations']) {
						$DEPOSIT_tx_type = 'deposit';
						$DEPOSIT_coin_type = $Bitcoind[$i]["prefix"];
						$DEPOSIT_date = date('n/j/y h:i a',$Bitcoind_List_Transaction['time']);
						$DEPOSIT_address = $Bitcoind_List_Transaction['address'];
						$DEPOSIT_amount = abs($Bitcoind_List_Transaction['amount']);
						$DEPOSIT_txid = $Bitcoind_List_Transaction['txid'];
						$SQL = "SELECT * FROM transactions WHERE coin='$DEPOSIT_coin_type' and txid='$DEPOSIT_txid' and trade_id = '".$my_coins->getTradeIdAccount()."'";
						$result = mysql_query($SQL);
						$num_rows = mysql_num_rows($result);
						if($num_rows!=1) {
							if(!mysql_query("INSERT INTO transactions (id,date,username,action,coin,address,txid,amount,trade_id) VALUES ('','$DEPOSIT_date','$user_session','$DEPOSIT_tx_type','$DEPOSIT_coin_type','$DEPOSIT_address','$DEPOSIT_txid','$DEPOSIT_amount','".$my_coins->getTradeIdAccount()."')")) {
								die("Server error");
							} else {
								$result = plusfunds($user_session,$Bitcoind[$i]["prefix"],$DEPOSIT_amount);
								if($result) {
									$r_system_action = "success";
								} else {
									die("Server error");
								}
							}
						}
					}
				}
			}
			
			$wallet_id = $my_coins->getWalletId($user_session,$Bitcrystald[$i]["cid"]);
			$Bitcrystald_List_Transactions = $Bitcrystald[$i]["daemon"]->listtransactions($wallet_id,50);
   
			foreach($Bitcrystald_List_Transactions as $Bitcrystald_List_Transaction) {
				if($Bitcrystald_List_Transaction['category']=="receive") {
					if(6<=$Bitcrystald_List_Transaction['confirmations']) {
						$DEPOSIT_tx_type = 'deposit';
						$DEPOSIT_coin_type = $Bitcrystald[$i]["prefix"];
						$DEPOSIT_date = date('n/j/y h:i a',$Bitcrystald_List_Transaction['time']);
						$DEPOSIT_address = $Bitcrystald_List_Transaction['address'];
						$DEPOSIT_amount = abs($Bitcrystald_List_Transaction['amount']);
						$DEPOSIT_txid = $Bitcrystald_List_Transaction['txid'];
						$SQL = "SELECT * FROM transactions WHERE coin='$DEPOSIT_coin_type' and txid='$DEPOSIT_txid' and trade_id = '".$my_coins->getTradeIdAccount()."'";
						$result = mysql_query($SQL);
						$num_rows = mysql_num_rows($result);
						if($num_rows!=1) {
							if(!mysql_query("INSERT INTO transactions (id,date,username,action,coin,address,txid,amount,trade_id) VALUES ('','$DEPOSIT_date','$user_session','$DEPOSIT_tx_type','$DEPOSIT_coin_type','$DEPOSIT_address','$DEPOSIT_txid','$DEPOSIT_amount','".$my_coins->getTradeIdAccount()."')")) {
								die("Server error");
							} else {
								$result = plusfunds($user_session,$Bitcrystald[$i]["prefix"],$DEPOSIT_amount);
								if($result) {
									$r_system_action = "success";
								} else {
									die("Server error");
								}
							}
						}
					}
				}
			}
			
			$wallet_id = $my_coins->getWalletId($user_session,$Bitcrystalxd[$i]["cid"]);
			$Bitcrystalxd_List_Transactions = $Bitcrystalxd[$i]["daemon"]->listtransactions($wallet_id,50);
   
			foreach($Bitcrystalxd_List_Transactions as $Bitcrystalxd_List_Transaction) {
				if($Bitcrystalxd_List_Transaction['category']=="receive") {
					if(6<=$Bitcrystalxd_List_Transaction['confirmations']) {
						$DEPOSIT_tx_type = 'deposit';
						$DEPOSIT_coin_type = $Bitcrystalxd[$i]["prefix"];
						$DEPOSIT_date = date('n/j/y h:i a',$Bitcrystalxd_List_Transaction['time']);
						$DEPOSIT_address = $Bitcrystalxd_List_Transaction['address'];
						$DEPOSIT_amount = abs($Bitcrystalxd_List_Transaction['amount']);
						$DEPOSIT_txid = $Bitcrystalxd_List_Transaction['txid'];
						$SQL = "SELECT * FROM transactions WHERE coin='$DEPOSIT_coin_type' and txid='$DEPOSIT_txid' and trade_id = '".$my_coins->getTradeIdAccount()."'";
						$result = mysql_query($SQL);
						$num_rows = mysql_num_rows($result);
						if($num_rows!=1) {
							if(!mysql_query("INSERT INTO transactions (id,date,username,action,coin,address,txid,amount,trade_id) VALUES ('','$DEPOSIT_date','$user_session','$DEPOSIT_tx_type','$DEPOSIT_coin_type','$DEPOSIT_address','$DEPOSIT_txid','$DEPOSIT_amount','".$my_coins->getTradeIdAccount()."')")) {
								die("Server error");
							} else {
								$result = plusfunds($user_session,$Bitcrystalxd[$i]["prefix"],$DEPOSIT_amount);
								if($result) {
									$r_system_action = "success";
								} else {
									die("Server error");
								}
							}
						}
					}
				}
			}
			
			$Bitcoind_Balance[$i] = userbalance($user_session,$Bitcoind[$i]["prefix"]);      // Simple function to call the users balance
			$Bitcrystald_Balance[$i] = userbalance($user_session,$Bitcrystald[$i]["prefix"]);
			$Bitcrystalxd_Balance[$i] = userbalance($user_session,$Bitcrystalxd[$i]["prefix"]);
		}
}
?>
