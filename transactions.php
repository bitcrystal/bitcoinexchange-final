<?php
session_start();
error_reporting(0);
require_once'auth.php';
if($Logged_In!==7) {
   header("Location: index.php");
}
$transactions_message=array();
$select = mysql_query("SELECT * FROM transactions WHERE username='".$_SESSION['user_session']."';");
if(!$select)
{
	$transactions_message[0]="no transactions";
}
$count_transactions = mysql_num_rows($select);
if($count_transactions <= 0)
{
	$transactions_message[0]="no transactions";
}

if($count_transactions != 0)
{
		$i = 0;
		while($Row = mysql_fetch_assoc($select)) {
			$date = $Row["date"];
			$username = $Row["username"];
			$action = $Row["action"];
			$coin = $Row["coin"];
			$address = $Row["address"];
			$txid = $Row["txid"];
			$amount = $Row["amount"];
			$transactions_message[$i]="Date: $date<br/>Action: $action<br/>Coin: $coin<br/>Adress: $address<br/>Txid: $txid<br/>Amount: $amount";
			$i++;
		}
} else {
	$count_transactions = 1;
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
   <table class="right-panel-table">
      <tr>
         <td valign="top" align="left" class="right-panel-left">
   <div align="center" class="bodydiv">
   <table style="width: 650px;">
      <tr>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <table>
               <tr>
                  <td colspan="5" align="center" nowrap>
                  <b><h2>Transactions:</h2></b>
                  </td>
			   </tr>
			   <tr></tr>
			   <tr></tr>
			   <tr></tr/>
                   <?php
						for($i = 0; $i < $count_transactions; $i++)
						{
							echo "<tr></tr><td><table>";
							if($i+2<$count_transactions) {
								echo '<div align="center" class="my-pending-right" nowrap><b><font color="red">'.$transactions_message[$i].'</b></font></div>';
								echo '<br></br>';
								echo '<div align="center" class="my-pending-right" nowrap><b><font color="red">'.$transactions_message[$i+1].'</b></font></div>';
								echo '<br></br>';
								echo '<div align="center" class="my-pending-right" nowrap><b><font color="red">'.$transactions_message[$i+2].'</b></font></div>';
								$i+=2;
							} else {
								echo '<td><div align="center" class="my-pending-right" nowrap<b><font color="red">'.$transactions_message[$i].'</b></font></div><p></p></td><td></td>';
							}
							echo "</table></td><tr></tr>";
						}
					?>
            </table>
         </td>
      </tr><tr>
         <?php $my_coins->outputDepositWithdrawLink() ?>
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