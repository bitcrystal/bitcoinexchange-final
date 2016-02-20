<?php
session_start();
error_reporting(E_ALL);
require_once'jsonRPCClient.php';
require_once'auth.php';
if($Logged_In!==7) {
   header("Location: index.php");
}
$coin_selecter = security($_GET['c']);
if($coin_selecter) {
   $_SESSION['trade_coin'] = $my_coins->coinSelecter($coin_selecter);
   header("Location: market.php");
}
$Coin_A_Balance = userbalance($user_session,$BTC);
$Coin_B_Balance = userbalance($user_session,$BTCRYX);
$Buying_Rate = buyrate($BTC,$BTCRYX);
$Selling_Rate = sellrate($BTC,$BTCRYX);
if(!$Buying_Rate) { $Buying_Rate = '0'; }
if(!$Selling_Rate) { $Selling_Rate = '0'; }
$trader = security($_POST['order-trader']);
$buyer = security($_POST['order-buyer']);
$trade_action = security($_POST['order-action']);
$trade_action_2 = security($_POST['trade-action']);
$trade_amount = security($_POST['order-amount']);
$trade_rate = security($_POST['order-rate']);
$trade_total = security($_POST['order-total']);
/*echo $Coin_A_Balance."<br/>";
echo $Coin_B_Balance."<br/>";
echo $trader."<br/>";
echo $trade_action."<br/>";
echo $trade_action_2."<br/>";
echo $trade_amount."<br/>";
echo $trade_rate."<br/>";
echo $trade_total."<br/>";
echo $BTC."<br/>";
echo $buyer."<br/>";*/
if($trade_action_2=="tradebuy"){
	//echo "hallo leute<br/>";
	$my_action="sell";
	$my_action_2="buy";
	$trade_id=0;
	$processed=1;
	if($trade_amount) {
		if($trade_rate) {
			$trade_error=false;
			if($trader==$user_session)
			{
				$Trade_Message = 'Could, Trade matching not done.';
				$trade_error=true;
			}
			if(!$trade_error)
			{
				$sql = "SELECT * FROM ".$my_action."_orderbook WHERE username = '$trader' AND want='$BTC' AND amount = '$trade_amount' AND action='$my_action' AND processed = '$processed' AND trade_with = '$BTCRYX' LIMIT 1";
				//echo $sql."<br/>";
				$Query = mysql_query($sql);
				if(!$Query)
				{
					$Trade_Message = 'Could, Trade matching not done. 1';
					$trade_error=true;
				} else {
					$sql = "UPDATE ".$my_action."_orderbook SET processed = '4' WHERE username = '$trader' AND want='$BTC' AND amount = '$trade_amount' AND action='$my_action' AND processed = '$processed' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX' LIMIT 1;";
					//echo $sql."<br/>";
					$Query_update = mysql_query($sql);
					if(!$Query_update)
					{
						$Trade_Message = 'Could, Trade matching not done. 2';
						$trade_error=true;
					}
				}
			}
			
			if(!$trade_error)
			{
				$nums = mysql_num_rows($Query);
				if($nums<=0)
				{
					$Trade_Message = 'Could, Trade matching not done. 3';
					$trade_error=true;
				}
			
				if(!$trade_error) {
					$id = false;
					$username = false;
					$amount = false;
					$rate = false;
					
					while($Row = mysql_fetch_assoc($Query)) {
						$id = $Row['id'];
						$username = $Row['username'];
						$amount = $Row['amount'];
						$rate = $Row['rate'];
					}
					
					if(!$id) {
						$Trade_Message = 'Could, Trade matching not done. 4';
						$trade_error=true;
					}
					
					if(!$trade_error)
					{
						$coin_balance=$Coin_A_Balance;
						$totalamount = $amount * $rate;
						$totalamount = satoshitrim(satoshitize($totalamount));
						if($coin_balance>=$totalamount)
						{
							$result1 = minusfunds($user_session,$BTC,$totalamount);
							$result2 = plusfunds($user_session,$BTCRYX,$amount);
							if($result1=="success" && $result2 == "success")
							{
								$result = plusfunds($trader, $BTC, $totalamount);
								if($result == "success")
								{
									$handler=$user_session;
									//$myhelp=$my_coins->coins_names_prefix[0]."_".$my_coins->coins_names_prefix[1]."_".$my_coins->coins_names_prefix[2];
									//echo $myhelp."<br/>";
									$Query = "INSERT INTO ordersfilled (date, ip, username, trader, oid, action, want, amount, rate, total, processed, trade_id, trade_with) VALUES ('$date', '$ip', '$handler', '$trader', '$trade_id', '$my_action_2', '$BTC', '$amount', '$rate', '$totalamount', '$processed', '".$my_coins->getTradeId()."', '$BTCRYX');";
									//echo $Query."<br/>";
									if(!mysql_query($Query)) {
										$Trade_Message = 'Could, Trade matching not done. 5';
										$trade_error=true;
									}
									$Trade_Message = "Trade is complete!";
								} else {
									$Trade_Message = 'Could, Trade matching not done. 6';
									$trade_error=true;
								}
							} else {
								$Trade_Message = 'Could, Trade matching not done. 7';
								$trade_error=true;
							}
							
						} else {
							$Trade_Message = "You don't have enough ".$BTCS."'s to complete the trade!";
							$trade_error=true;
						}
					}
				}
			}
		} else {
			$Trade_Message = 'Could, Trade matching not done. 9';
			$trade_error=true;
		}
	} else {
		$Trade_Message = 'Could, Trade matching not done. 10';
		$trade_error=true;
	}
	if($trade_error)
	{
		$sql = "UPDATE ".$my_action."_orderbook SET processed = '$processed' WHERE username = '$trader' AND want='$BTC' AND amount = '$trade_amount' AND action='$my_action' AND processed = '4' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX' LIMIT 1;";
		//echo $sql."<br/>";
		mysql_query($sql);
	} else {
		$sql = "DELETE FROM ".$my_action."_orderbook WHERE username = '$trader' AND want='$BTC' AND amount = '$trade_amount' AND action='$my_action' AND processed = '4' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX' LIMIT 1;";
		//echo $sql."<br/>";
		mysql_query($sql);
	}
	//header("Location: market.php");
}

if($trade_action_2=="tradesell"){
	$my_action="buy";
	$my_action_2="sell";
	$trade_id=1;
	$processed=1;
	if($trade_amount) {
		if($trade_rate) {
			$trade_error=false;
			$sql = "SELECT * FROM ".$my_action."_orderbook WHERE username = '$buyer' AND want='$BTC' AND amount = '$trade_amount' AND action='$my_action' AND processed = '$processed' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX' LIMIT 1;";
			//echo $sql."<br/>";
			$Query = mysql_query($sql);
            if(!$Query)
			{
				 $Trade_Message = 'Could, Trade matching not done. 1';
				 $trade_error=true;
			} else {
				$sql = "UPDATE ".$my_action."_orderbook SET processed = '4' WHERE username = '$buyer' AND want='$BTC' AND amount = '$trade_amount' AND action='$my_action' AND processed = '$processed' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX' LIMIT 1;";
				//echo $sql."<br/>";
				$Query_update = mysql_query($sql);
				if(!$Query_update)
				{
					$Trade_Message = 'Could, Trade matching not done. 2';
					$trade_error=true;
				}
			}
			
			if($trader!=$user_session)
			{
				$Trade_Message = 'Could, Trade matching not done. 3';
				$trade_error=true;
			}
			
			if(!$trade_error)
			{
				$nums = mysql_num_rows($Query);
				if($nums<=0)
				{
					$Trade_Message = 'Could, Trade matching not done. 4';
					$trade_error=true;
				}
			
				if(!$trade_error) {
					$id = false;
					$username = false;
					$amount = false;
					$rate = false;
					
					while($Row = mysql_fetch_assoc($Query)) {
						$id = $Row['id'];
						$username = $Row['username'];
						$amount = $Row['amount'];
						$rate = $Row['rate'];
						if($username!=$buyer)
						{
							$id = false;
							break;
						}
					}
					
					if(!$id) {
						$Trade_Message = 'Could, Trade matching not done. 5';
						$trade_error=true;
					}
					
					if(!$trade_error)
					{
						$coin_balance=$Coin_B_Balance;
						$totalamount = $amount * $rate;
						$totalamount = satoshitrim(satoshitize($totalamount));
						if($coin_balance>=$amount)
						{
							$result1 = minusfunds($user_session,$BTCRYX,$amount);
							$result2 = plusfunds($username,$BTCRYX,$amount);
							if($result1=="success" && $result2 == "success")
							{
								$result = plusfunds($user_session, $BTC, $totalamount);
								if($result == "success")
								{
									$handler=$buyer;
									$Query = "INSERT INTO ordersfilled (date, ip, username, trader, oid, action, want, amount, rate, total, processed, trade_id, trade_with) VALUES ('$date', '$ip', '$handler', '$trader', '$trade_id', '$my_action_2', '$BTC', '$amount', '$rate', '$totalamount', '$processed', '".$my_coins->getTradeId()."', '$BTCRYX');";
									//echo $Query."<br/>";
									if(!mysql_query($Query)) {
										$Trade_Message = 'Could, Trade matching not done. 6';
										$trade_error=true;
									}
									$Trade_Message = "Trade is complete!";
								} else {
									$Trade_Message = 'Could, Trade matching not done. 7';
									$trade_error=true;
								}
							} else {
								$Trade_Message = 'Could, Trade matching not done. 8';
								$trade_error=true;
							}
							
						} else {
							$Trade_Message = "You don't have enough ".$BTCRYXS."'s to complete that trade!";
							$trade_error=true;
						}
					}
				}
			}
		} else {
			$Trade_Message = 'Could, Trade matching not done. 10';
			$trade_error=true;
		}
	} else {
		$Trade_Message = 'Could, Trade matching not done. 11';
		$trade_error=true;
	}
	if($trade_error)
	{
		$sql = "UPDATE ".$my_action."_orderbook SET processed = '$processed' WHERE username = '$buyer' AND want='$BTC' AND amount = '$trade_amount' AND action='$my_action' AND processed = '4' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX' LIMIT 1;";
		//echo $sql."<br/>";
		mysql_query($sql);
	} else {
		$sql = "DELETE FROM ".$my_action."_orderbook WHERE username = '$buyer' AND want='$BTC' AND amount = '$trade_amount' AND action='$my_action' AND processed = '4' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX' LIMIT 1;";
		//echo $sql."<br/>";
		mysql_query($sql);
	}
	//header("Location: market.php");
}

$sell_orderbook=array();
$buy_orderbook=array();
$buy_orderbook["id"]=array();
$buy_orderbook["username"]=array();
$buy_orderbook["amount"]=array();
$buy_orderbook["rate"]=array();
$sell_orderbook["id"]=array();
$sell_orderbook["username"]=array();
$sell_orderbook["amount"]=array();
$sell_orderbook["rate"]=array();
$id = false;
$username = false;
$amount = false;
$rate = false;
$sell_orderbook["message"]="";
$buy_orderbook["message"]="";

$Query = mysql_query("SELECT * FROM sell_orderbook WHERE username != '$user_session' AND want='$BTC' AND processed = '1' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX';");
if(!$Query)
{
	$sell_orderbook["message"]="No buy orders for you!";
}

if(mysql_num_rows($Query) <= 0)
{
	$sell_orderbook["message"]="No buy orders for you!";
}

$i = 0;
while($Row = mysql_fetch_assoc($Query)) {
	$id = $Row['id'];
	$username = $Row['username'];
	$amount = $Row['amount'];
	$rate = $Row['rate'];
	$sell_orderbook["id"][$i]=$id;
	$sell_orderbook["username"][$i]=$username;
	$sell_orderbook["amount"][$i]=$amount;
	$sell_orderbook["rate"][$i]=$rate;
	$i++;
}
$count_sell_orderbook = $i;

$Query = mysql_query("SELECT * FROM buy_orderbook WHERE username != '$user_session' AND want='$BTC' AND processed = '1' AND trade_id = '".$my_coins->getTradeId()."' AND trade_with = '$BTCRYX';");
if(!$Query)
{
	$buy_orderbook["message"]="No sell orders for you!";
}

if(mysql_num_rows($Query) <= 0)
{
	$buy_orderbook["message"]="No sell orders for you!";
}

$i = 0;
while($Row = mysql_fetch_assoc($Query)) {
	$id = $Row['id'];
	$username = $Row['username'];
	$amount = $Row['amount'];
	$rate = $Row['rate'];
	$buy_orderbook["id"][$i]=$id;
	$buy_orderbook["username"][$i]=$username;
	$buy_orderbook["amount"][$i]=$amount;
	$buy_orderbook["rate"][$i]=$rate;
	$i++;
}
$count_buy_orderbook = $i;
if($Trade_Message)
{
	header("Refresh: 5;");
}
?>
<html>
<head>
   <title><?php echo $script_title; ?></title>
   <link rel="shortcut icon" href="image/favicon.ico">
   <?php echo $CSS_Stylesheet; ?>
   <script src="js/jquery-1.9.1.js"></script>
   <script type="text/javascript">
      $(document).ready(function () {
         setInterval(function () {
            $("#balances").load("ajax.php?id=balances");
            $("#pending-deposits").load("ajax.php?id=pending-deposits");
         }, 30000);
        setInterval(function () {
            $("#orderspast").load("ajax.php?id=orderspast");
            $("#buyorders").load("ajax.php?id=buyorders");
            $("#sellorders").load("ajax.php?id=sellorders");
            $(".count").load("online.php");
            $("#stats").load("ajax.php?id=stats");
        }, 60000);
        $("#buy-sells").scrollbars();
      });
   </script>
   <script type="text/javascript">
	   function buycalculator() {
         m = document.getElementById('buy-quantity').value;
         n = document.getElementById('buy-rate').value;
         if(m=='') { m = 0; }
         if(n=='') { n = 0; }
         o = m*n;
         g = o.toFixed(8);
         b = o/100;
         c = b/5;
         l = c.toFixed(8);
         document.getElementById('buy-subtotal').innerHTML = g;
         document.getElementById('buy-fee').innerHTML = l;
      }
      function sellcalculator() {
         x = document.getElementById('sell-quantity').value;
         y = document.getElementById('sell-rate').value;
         if(x=='') { x = 0; }
         if(x=='') { x = 0; }
         z = x*y;
         r = z.toFixed(8);
         e = z/100;
         f = e/5;
         s = f.toFixed(8);
         document.getElementById('sell-subtotal').innerHTML = r;
         document.getElementById('sell-fee').innerHTML = s;
      }
      function setbuyamounts(text) {
         document.getElementById('buy-quantity').value = text;
         buycalculator();
      }
      function setbuyrates(text) {
         document.getElementById('buy-rate').value = text;
         buycalculator();
      }
      function setsellamounts(text) {
         document.getElementById('sell-quantity').value = text;
         sellcalculator();
      }
      function setsellrates(text) {
         document.getElementById('sell-rate').value = text;
         sellcalculator();
      }
      function setbuyrateamounts(text) {
         pla = document.getElementById('buy-rate').value;
         plb = text/pla;
         plc = plb.toFixed(8);
         document.getElementById('buy-quantity').value = plc;
         buycalculator();
      }
	  function changeIndex(elementId) {
		var e = document.getElementById(elementId);
		var index = e.selectedIndex;
        var a = document.getElementById('buy-seller');
		var b = document.getElementById('buy-quantity');
		var c = document.getElementById('buy-rate');
		var d = document.getElementById('buy-subtotal');
		a.selectedIndex = index;
		b.selectedIndex = index;
		c.selectedIndex = index;
		d.selectedIndex = index;
      }
	  
	  function changeIndexBuy(elementId) {
		var e = document.getElementById(elementId);
		var index = e.selectedIndex;
        var a = document.getElementById('sell-buyer');
		var b = document.getElementById('sell-quantity');
		var c = document.getElementById('sell-rate');
		var d = document.getElementById('sell-subtotal');
		a.selectedIndex = index;
		b.selectedIndex = index;
		c.selectedIndex = index;
		d.selectedIndex = index;
      }
	  
	  function changeTradeAction(text) {
		var e = document.getElementById('tradeActionD');
		e.value = text;
      }
   </script>
</head>
<body title="[zelles]">
   <center>
   <div align="center" class="headdiv">
   <div align="center" class="balancesdiv"><span id="stats"><?php require'ajax/stats.php'; ?></span></div>
   <table style="width: 100%; height: 50px;">
      <tr>
         <td align="left" style="width: 30px;" nowrap>
            <a href="http://<?php echo $server_url; ?>"><img src="image/logob.png" title="<?php echo $script_title; ?>" alt="Logo" border="0"></a>
         </td>
         <td align="left" style="font-size: 18px; font-weight: bold;" nowrap>
            <a href="http://<?php echo $server_url; ?>"><img src="image/logo.png" title="<?php echo $script_title; ?>" alt="[zelles]" border="0"></a>
         </td>
         <td align="right" valign="top" nowrap>
            <table>
               <tr>
                  <td><a href="home.php">Home</a></td>
				  <td style="padding-left: 5px;"><a href="market.php">Market</a></td>
				<td style="padding-left: 5px;"><a href="transactions.php">Transactions</a></td>
                  <td style="padding-left: 5px;"><a href="account.php">Account</a></td>
                  <td style="padding-left: 5px;"><a href="logout.php">Logout</a></td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
   </div>
   <p></p>
   <?php if($Trade_Message) { echo '<div align="center" class="error-msg" nowrap>'.$Trade_Message.'</div><p></p>'; } sleep(3); header('Location: market.php'); ?>
   <table class="right-panel-table">
      <tr>
         <td valign="top" align="left" class="right-panel-left">
   <div align="center" class="bodydiv">
   <table style="width: 100%;">
      <tr>
       <div style="padding-left:07%; width: 100%;"><ul style="list-style-type:center;display:initial;">
   
            <?php $my_coins->outputCoinButtonLinks(); ?>
   
        </ul></div>
   </tr><tr>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <div align="center" class="buy-sells-box">
            <table style="width: 260px; height: 50px;">
               <tr>
                  <td align="left" style="font-weight: bold;" nowrap>Balance:</td>
                  <td align="right" style="font-weight: bold;" nowrap>Lowest Sell Value:</td>
               </tr><tr>
                  <td align="left" nowrap><?php echo '<a href="#" onclick="setbuyrateamounts('.$Coin_A_Balance.');">'.$Coin_A_Balance.'</a> '.$BTC; ?></td>
                  <td align="right" nowrap><?php echo '<a href="#" onclick="setbuyrates('.$Selling_Rate.');">'.$Selling_Rate.'</a> '.$BTC; ?></td>
               </tr>
            </table>
            </div>
            <form action="market.php" method="POST">
            <input name="order-action" type="hidden" value="buy">
			<input id="tradeAction" name="trade-action" type="hidden" value="tradebuy">
            <table>
               <tr>
                  <td colspan="3" style="height: 10px;"></td>
			   </tr><tr>
                  <td align="right" nowrap><b>Seller</b></td>
                  <td align="right" nowrap><select id="buy-seller" name="order-trader" size="1" onchange="changeIndex('buy-seller');"><?php
						if($count_sell_orderbook > 0)
						{
							for($i = 0; $i < $count_sell_orderbook; $i++)
							{
								echo '<option value="'.$sell_orderbook["username"][$i].'">'.$sell_orderbook["username"][$i].'</option>';
							}
						} else {
							echo '<option value="1">'.$sell_orderbook["message"].'</option>';
						}
				  ?></select></td>
               </tr><tr>
                  <td align="right" nowrap><b>Quantity</b></td>
                  <td align="right" nowrap><select id="buy-quantity" name="order-amount" size="1" onchange="changeIndex('buy-quantity');"><?php
						if($count_sell_orderbook > 0)
						{
							for($i = 0; $i < $count_sell_orderbook; $i++)
							{
								echo '<option value="'.$sell_orderbook["amount"][$i].'">'.$sell_orderbook["amount"][$i].'</option>';
							}
						} else {
							echo '<option value="2">'.$sell_orderbook["message"].'</option>';
						}
				  ?></select></td>
                  <td align="left" nowrap><?php echo $BTCRYX; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Rate</b></td>
                  <td align="right" nowrap><select id="buy-rate" name="order-rate" size="1" onchange="changeIndex('buy-rate');"><?php
						if($count_sell_orderbook > 0)
						{
							for($i = 0; $i < $count_sell_orderbook; $i++)
							{
								echo '<option value="'.$sell_orderbook["rate"][$i].'">'.$sell_orderbook["rate"][$i].'</option>';
							}
						} else {
							echo '<option value="3">'.$sell_orderbook["message"].'</option>';
						}
				  ?></select></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Sub-total</b></td>
                  <td align="right" nowrap><select id="buy-subtotal" name="order-total" size="1" onchange="changeIndex('buy-rate');"><?php
						if($count_sell_orderbook > 0)
						{
							for($i = 0; $i < $count_sell_orderbook; $i++)
							{
								$value = satoshitrim(satoshitize($sell_orderbook["amount"][$i]*$sell_orderbook["rate"][$i]));
								echo '<option value="'.$value.'">'.$value.'</option>';
							}
						} else {
							echo '<option value="4">'.$sell_orderbook["message"].'</option>';
						}
				  ?></select></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td colspan="3" style="height: 10px;"></td>
               </tr><tr>
                  <td colspan="3" align="right" nowrap><input type="submit" value="Buy" class="buybutton"></td>
               </tr>
            </table>
            </form>
         </td>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <div align="center" class="buy-sells-box">
            <table style="width: 260px; height: 50px;">
               <tr>
                  <td align="left" style="font-weight: bold;" nowrap>Balance:</td>
                  <td align="right" style="font-weight: bold;" nowrap>Highest Buy Value:</td>
               </tr><tr>
                  <td align="left" nowrap><?php echo '<a href="#" onclick="setsellamounts('.$Coin_B_Balance.');">'.$Coin_B_Balance.'</a> '.$BTCRYX; ?></td>
                  <td align="right" nowrap><?php echo '<a href="#" onclick="setsellrates('.$Buying_Rate.');">'.$Buying_Rate.'</a> '.$BTC; ?></td>
               </tr>
            </table>
            </div>
            <form action="market.php" method="POST">
                  <input type="hidden" name="order-action" value="sell">
				  <input type="hidden" id="tradeAction" name="trade-action" value="tradesell">
				  <input type="hidden" name="order-trader" value="<?php echo $user_session ?>">
            <table>
               <tr>
                  <td colspan="3" style="height: 10px;"></td>
			   </tr><tr>
                  <td align="right" nowrap><b>Buyer</b></td>
                  <td align="right" nowrap><select id="buy-seller" name="order-buyer" size="1" onchange="changeIndexBuy('sell-buyer');"><?php
						if($count_buy_orderbook > 0)
						{
							for($i = 0; $i < $count_buy_orderbook; $i++)
							{
								echo '<option>'.$buy_orderbook["username"][$i].'</option>';
							}
						} else {
							echo '<option value="5">'.$buy_orderbook["message"].'</option>';
						}
				  ?></select></td>
               </tr><tr>
                  <td align="right" nowrap><b>Quantity</b></td>
                  <td align="right" nowrap><select id="sell-quantity" name="order-amount" size="1" onchange="changeIndexBuy('sell-quantity');"><?php
						if($count_buy_orderbook > 0)
						{
							for($i = 0; $i < $count_buy_orderbook; $i++)
							{
								echo '<option value="'.$buy_orderbook["amount"][$i].'">'.$buy_orderbook["amount"][$i].'</option>';
							}
						} else {
							echo '<option value="6">'.$buy_orderbook["message"].'</option>';
						}
				  ?></select></td>
                  <td align="left" nowrap><?php echo $BTCRYX; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Rate</b></td>
                  <td align="right" nowrap><select id="sell-rate" name="order-rate" size="1" onchange="changeIndexBuy('sell-rate');"><?php
						if($count_buy_orderbook > 0)
						{
							for($i = 0; $i < $count_buy_orderbook; $i++)
							{
								echo '<option value="'.$buy_orderbook["rate"][$i].'">'.$buy_orderbook["rate"][$i].'</option>';
							}
						} else {
							echo '<option value="7">'.$buy_orderbook["message"].'</option>';
						}
				  ?></select></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Sub-total</b></td>
                  <td align="right" nowrap><select id="sell-subtotal" name="order-total" size="1" onchange="changeIndexBuy('sell-subtotal');"><?php
						if($count_buy_orderbook > 0)
						{
							for($i = 0; $i < $count_buy_orderbook; $i++)
							{
								$value = satoshitrim(satoshitize($buy_orderbook["amount"][$i]*$buy_orderbook["rate"][$i]));
								echo '<option value="'.$value.'">'.$value.'</option>';
							}
						} else {
							echo '<option value="8">'.$buy_orderbook["message"].'</option>';
						}
				  ?></select></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td colspan="3" style="height: 10px;"></td>
               </tr><tr>
                  <td colspan="3" align="right" nowrap><input type="submit" value="Sell" class="sellbutton"></td>
               </tr>
            </table>
            </form>
         </td>
      </tr><tr>
         <td align="left" valign="top" style="padding: 5px;" nowrap>
            <span id="sellorders"><?php require"ajax/buyorders.php"; ?></span>
         </td>
         <td align="left" valign="top" style="padding: 5px;" nowrap>
            <span id="buyorders"><?php require"ajax/sellorders.php"; ?></span>
         </td>
      </tr><tr>
         <td colspan="2" align="left" valign="top" style="padding: 5px;" nowrap>
            <span id="orderspast"><?php require"ajax/orderspast.php"; ?></span>
         </td>
      </tr>
   </table>
   </div>
         </td>
         <td style="width: 6px;">
         </td>
         <td valign="top" align="left" class="right-panel-right">
            <span id="pending-deposits"><?php require'ajax/pending-deposits.php'; ?></span>
            <div align="left" class="right-panel">
            <span id="balances"><?php require'ajax/balances.php'; ?></span>
            </div>
            <p></p>
            <div align="left" class="right-panel">
            <?php require'ajax/menu.php'; ?>
            </div>
            <p></p>
            <div align="left" class="right-panel"><b>Online:</b><p></p><span class="count"><?php require"online.php"; ?></span></div>
         </td>
      </tr>
   </table>
   <p></p>
   <?php require'ajax/footer.php'; ?>
   <p></p>
   </center>
</body>
</html>
<?php
mysql_close($db_handle);
?>