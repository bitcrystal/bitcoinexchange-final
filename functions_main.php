<?php
require_once('coins.php');
$GLOBALS['cp']=$my_coins->coins_names_prefix;
$GLOBALS['cp_count']=count($my_coins->coins_names_prefix);
function getTradeId($i=0, $trade_account=true)
{
	if($trade_account)
		$i=0;
	return $GLOBALS['cp'][0+$i]."_".$GLOBALS['cp'][1+$i]."_".$GLOBALS['cp'][2+$i];
}
function security($value) {
   if(is_array($value)) {
      $value = array_map('security', $value);
   } else {
      if(!get_magic_quotes_gpc()) {
         $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
      } else {
         $value = htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8');
      }
      $value = str_replace("\\", "\\\\", $value);
   }
   return $value;
}

function apikeygen() {
   $keygen_characters = "0011223344--__5566778899aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzzAABBCCDDEEF--__FGGHHIIJJKKLLMMNNOOPPQQRRSSTUUVVWWXXYYZZ";
   $keygen_key = "";
   $keygen_length = rand(40, 60);
   for($keygen_i = 0; $keygen_i < $keygen_length; $keygen_i++) {
      $keygen_key .= $keygen_characters[rand(0, strlen($keygen_characters) - 1)];
   }
   return $keygen_key;
}

function satoshitize($satoshitize) {
   return sprintf("%.8f", $satoshitize);
}

function satoshitrim($satoshitrim) {
   return rtrim(rtrim($satoshitrim, "0"), ".");
}

function userbalance($function_user,$function_coin) {
	

	for($i = 0; $i < $GLOBALS['cp_count']; $i+=3)
	{
   if($function_coin==$GLOBALS['cp'][0+$i]) {
      $function_query = mysql_query("SELECT coin".($i+1)." FROM balances WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'");
      while($function_row = mysql_fetch_assoc($function_query)) { $function_return = $function_row['coin'.($i+1)]; }
	  break;
   }
   if($function_coin==$GLOBALS['cp'][2+$i]) {
      $function_query = mysql_query("SELECT coin".($i+2)." FROM balances WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'");
      while($function_row = mysql_fetch_assoc($function_query)) { $function_return = $function_row['coin'.($i+2)]; }
	  break;
   }
   if($function_coin==$GLOBALS['cp'][1+$i]) {
      $function_query = mysql_query("SELECT coin".($i+3)." FROM balances WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'");
	while($function_row = mysql_fetch_assoc($function_query)) { $function_return = $function_row['coin'.($i+3)]; }
	break;
	}
   }
   return $function_return;
}

function buyrate($function_coin, $function_coin2) {
	$found_value=false;
	for($i = 0; $i < $GLOBALS['cp_count']; $i+=3)
	{
   $function_query = mysql_query("SELECT rate FROM buy_orderbook WHERE want='$function_coin' and processed='1' and trade_id = '".getTradeId($i)."' AND trade_with = '$function_coin2' ORDER BY rate DESC LIMIT 1");
   while($function_row = mysql_fetch_assoc($function_query)) {
      $function_return = $function_row['rate'];
	  $found_value=true;
   }
   if($found_value)
		break;
   }
   return $function_return;
}

function sellrate($function_coin,$function_coin2) {
	$found_value=false;
	for($i = 0; $i < $GLOBALS['cp_count']; $i+=3)
	{
   $function_query = mysql_query("SELECT rate FROM sell_orderbook WHERE want='$function_coin' and processed='1' and trade_id = '".getTradeId($i)."' AND trade_with = '$function_coin2' ORDER BY rate ASC LIMIT 1");
   while($function_row = mysql_fetch_assoc($function_query)) {
      $function_return = $function_row['rate'];
	  $found_value=true;
   }
   if($found_value)
		break;
   }
   return $function_return;
}

function plusfunds($function_user,$function_coin,$function_amount) {
	$found_value=false;
	for($i = 0; $i < $GLOBALS['cp_count']; $i+=3)
	{
   $function_user_balance = userbalance($function_user,$function_coin);
   $function_balance = $function_user_balance + $function_amount;
   $function_balance = satoshitrim(satoshitize($function_balance));
   if($function_coin==$GLOBALS['cp'][0+$i]) { $sql = "UPDATE balances SET coin".($i+1)."='$function_balance' WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'"; $found_value=true;}
   if($function_coin==$GLOBALS['cp'][2+$i]) { $sql = "UPDATE balances SET coin".($i+2)."='$function_balance' WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'"; $found_value=true;}
   if($function_coin==$GLOBALS['cp'][1+$i]) { $sql = "UPDATE balances SET coin".($i+3)."='$function_balance' WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'"; $found_value=true;}
   if(!$found_value)
		continue;
   $result = mysql_query($sql);
   if($result) {
      $function_return = "success";
   } else {
      $function_return = "error";
   }
   break;
   }
   return $function_return;
}

function minusfunds($function_user,$function_coin,$function_amount) {
	$found_value=false;
	for($i = 0; $i < $GLOBALS['cp_count']; $i+=3)
	{
   $function_user_balance = userbalance($function_user,$function_coin);
   $function_balance = $function_user_balance - $function_amount;
   $function_balance = satoshitrim(satoshitize($function_balance));
   if($function_coin==$GLOBALS['cp'][0+$i]) { $sql = "UPDATE balances SET coin".($i+1)."='$function_balance' WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'"; $found_value=true;}
   if($function_coin==$GLOBALS['cp'][2+$i]) { $sql = "UPDATE balances SET coin".($i+2)."='$function_balance' WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'"; $found_value=true;}
   if($function_coin==$GLOBALS['cp'][1+$i]) { $sql = "UPDATE balances SET coin".($i+3)."='$function_balance' WHERE username='$function_user' AND trade_id = '".getTradeId($i)."'"; $found_value=true;}
   if(!$found_value)
		continue;
   $result = mysql_query($sql);
   if($result) {
      $function_return = "success";
   } else {
      $function_return = "error";
   }
   break;
   }
   return $function_return;
}

function get_current_url()
{
	return 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}

function get_exchange_url()
{
	$p = get_current_url();
	$p=str_replace("http://","",$p);
	$p=str_replace("https://","",$p);
	$p = str_replace("auth.php","index.php",$p);
	return $p;
}

function get_root_path($file)
{
	$str = dirname(__FILE__);
	$str = str_replace("ajax","",$str);
	$str = $str . '/' . $file;
	return $str;
}
?>
