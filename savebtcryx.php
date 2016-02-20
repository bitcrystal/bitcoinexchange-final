<?php
session_start();
error_reporting(0);
require_once'auth.php';
if($Logged_In!==7) {
   header("Location: index.php");
}
$savemoney = security($_POST['action']);
$iid = security($_POST['iid']);
if(!$iid) {
	$my_coins->setSelectInstanceId($my_coins->getInstanceId());
} else {
	if($iid!=$my_coins->getSelectInstanceId())
	{
		header("Location: home.php");
	}
}

$iid = $my_coins->getSelectInstanceId();
$cid = $my_coins->getCoinsSelectInstanceId();
if($savemoney=="savemoney") {
			$id=2+$cid;
			$wallet_id = $my_coins->getWalletId($user_session,$Bitcrystalxd[$iid]["cid"]);
			$FEEBEE = $my_coins->coins[$my_coins->coins_names[$id]]["FEEBEE"];
			$wallet_id_feebee = "zellesExchange(".$FEEBEE.")";
			$my_balance=userbalance($user_session,$my_coins->coins_names_prefix[$id]);
			$result = minusfunds($user_session,$my_coins->coins_names_prefix[$id],$my_balance);
			$my_balance_n = $my_coins->set_coins_balance($my_coins->coins_names[$id], $wallet_id, $wallet_id_feebee, $my_balance); 
			$result = plusfunds($user_session,$my_coins->coins_names_prefix[$id],$my_balance_n);            // add fee to feebee account
            $Bitcrystalxd_Balance[$iid] = userbalance($user_session,$my_coins->coins_names_prefix[$id]);
            $savemoney_message = 'success';
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
   <?php if($savemoney_message) { echo '<div align="center" class="error-msg" nowrap>'.$savemoney_message.'</div><p></p>'; } ?>
   <table class="right-panel-table">
      <tr>
         <td valign="top" align="left" class="right-panel-left">
   <div align="center" class="bodydiv">
   <table style="width: 650px;">
      <tr>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <form action="savebtcryx.php" method="POST">
            <input type="hidden" name="action" value="savemoney">
            <input type="hidden" name="iid" value="<?php echo $iid; ?>">
			<table>
               <tr>
                  <td colspan="2" align="left" nowrap>
                  <b>Save Money(<?php $id=2+$cid; echo $my_coins->coins_names_prefix[$id]?>):</b>
                  </td>
               </tr><tr>
                  <td colspan="2" align="right" nowrap><input type="submit" class="button" name="submit" value="Save"></td>
               </tr>
            </table>
            </form>
         </td>
      </tr><tr>
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