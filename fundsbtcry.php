<?php
session_start();
error_reporting(0);
require_once'auth.php';
if($Logged_In!==7) {
   header("Location: index.php");
}
$withdraw_withdraw = security($_POST['action']);
$withdraw_amount = security($_POST['amount']);
$withdraw_address = security($_POST['address']);
$iid = security($_POST['iid']);
if(!$iid) {
	$my_coins->setSelectInstanceId($my_coins->getCoinsInstanceId());
} else {
	if($iid!=$my_coins->getSelectInstanceId())
	{
		header("Location: home.php");
	}
}

$iid = $my_coins->getSelectInstanceId();
$cid = $my_coins->getCoinsSelectInstanceId()+1;
if($withdraw_withdraw=="withdraw") {
   if($withdraw_address) {
      if($withdraw_amount) {
         $withdraw_amount = satoshitize($withdraw_amount);
         if($withdraw_amount<=$Bitcrystald_Balance[$iid]) {
			$fee=$my_coins->coins[$my_coins->coins_names[$cid]]["fee"];
            $FEEBEE = $my_coins->coins[$my_coins->coins_names[$cid]]["FEEBEE"];
			$set_withdraw_amount = $withdraw_amount - $fee;       // minus the fee
            $true_withdraw_amount = satoshitize($set_withdraw_amount);
            $Bitcrystald_Withdraw_From = $Bitcrystald[$iid]["daemon"]->sendtoaddress($withdraw_address,(float)$true_withdraw_amount);
            if($Bitcrystald_Withdraw_From) {
               $result = minusfunds($user_session,$my_coins->coins_names_prefix[$cid],$withdraw_amount);
               $result = plusfunds($FEEBEE,$my_coins->coins_names_prefix[$cid],$fee);            // add fee to feebee account
               $Bitcrystald_Balance = userbalance($user_session,$my_coins->coins_names_prefix[$cid]);
               $withdraw_message = '<a href="#'.$Bitcrystald_Withdraw_From.'" target="_blank" style="color: #0B2161;">Withdraw was sent!<br>'.$Bitcrystald_Withdraw_From.'</a>';
               if(!mysql_query("INSERT INTO transactions (id,date,username,action,coin,address,txid,amount,trade_id) VALUES ('','$date','$user_session','withdraw','".$my_coins->coins_names_prefix[$cid]."','$withdraw_address','$Bitcrystald_Withdraw_From','$withdraw_amount','".$my_coins->getTradeId()."')")){
                  $eereturn_error = "System error.";
               } else {
                  $eereturn_error = "Logged in.";
               }
            }
         } else {
            $withdraw_message = 'You do not have enough '.$my_coins->coins_names[$cid].'s!';
         }
      } else {
         $withdraw_message = 'No amount to withdraw was entered!';
      }
   } else {
      $withdraw_message = 'No '.$my_coins->coins_names[$cid].' address was entered!';
   }
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
            $(".count").load("online.php");
            $("#stats").load("ajax.php?id=stats");
        }, 60000);
    });
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
   <?php if($withdraw_message) { echo '<div align="center" class="error-msg" nowrap>'.$withdraw_message.'</div><p></p>'; } ?>
   <table class="right-panel-table">
      <tr>
         <td valign="top" align="left" class="right-panel-left">
   <div align="center" class="bodydiv">
   <table style="width: 650px;">
      <tr>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <form action="fundsbtcry.php" method="POST">
            <input type="hidden" name="action" value="withdraw">
            <input type="hidden" name="iid" value="<?php echo $iid; ?>">
			<table>
               <tr>
                  <td colspan="2" align="left" nowrap>
                  <b>Withdraw:</b>
                  </td>
               </tr><tr>
                  <td align="right" nowrap><b>Amount</b></td>
                  <td align="left" nowrap><input type="text" name="amount" style="width: 100px;"></td>
               </tr><tr>
                  <td align="right" nowrap><b>Address</b></td>
                  <td align="right" nowrap><input type="text" name="address" style="width: 180px;"></td>
               </tr><tr>
                  <td colspan="2" align="right" nowrap><input type="submit" class="button" name="submit" value="Withdraw"></td>
               </tr>
            </table>
            </form>
         </td>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <table>
               <tr>
                  <td align="left" style="padding: 3px;" nowrap>
                     <b>Deposit:</b>
                  </td>
               </tr><tr>
                  <td align="center" style="padding: 2px; font-weight: bold; color: #666666;" nowrap><?php echo $Bitcrystald_Account_Address[$iid]; ?></td>
               </tr><tr>
                  <?php echo '<td align="left" style="padding: 2px; padding-left: 20px;">Deposits must have 6 confirmations to become active. There is a fee of '.$my_coins->coins[$my_coins->coins_names[$cid]]["fee"].' '.$my_coins->coins_names[$cid].' to make a withraw.</td>'; ?>
               </tr>
            </table>
            </center>
         </td>
      </tr><tr>
		 <td colspan="2" align="right" valign="top" style="font-weight: bold; padding: 5px;" nowrap><a href="savebtcry.php">Save to Wallet</a></td>
         <td colspan="2" align="right" valign="top" style="font-weight: bold; padding: 5px;" nowrap><a href="transactions.php">Transactions</a></td>
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
