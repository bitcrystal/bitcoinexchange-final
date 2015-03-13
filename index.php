<?php
session_start();
error_reporting(E_ALL);
require_once'jsonRPCClient.php';
require_once'auth.php';
if(isset($_SESSION['user_session']) && isset($_SESSION['user_password']) && isset($_SESSION['user_time']) && $_SESSION['user_time'] > 0)
{
	$Logged_In=7;
}
if($Logged_In===7) {
   header("Location: home.php");
}
$coin_selecter = security($_GET['c']);
if($coin_selecter) {
   $_SESSION['trade_coin'] = $my_coins->coinSelecter($coin_selecter);
   header("Location: index.php");
}
$Buying_Rate = buyrate($BTC,$BTCRYX);
$Selling_Rate = sellrate($BTC,$BTCRYX);
if(!$Buying_Rate) { $Buying_Rate = '0'; }
if(!$Selling_Rate) { $Selling_Rate = '0'; }

$login_attempts = $_SESSION['login_attempts'];
if(!$login_attempts) {
   $_SESSION['login_attempts'] = 0;
}
if(isset($_GET['recover']) && isset($_GET['username']) && isset($_GET['id']) )
{
	if($_GET['recover']==1)
	{
		$recover = $_GET['recover'];
		$username = $_GET['username'];
		$id = $_GET['id'];
		$sql = "SELECT * FROM users WHERE username='$username' AND trade_id = '".$my_coins->getTradeIdAccount()."'";
        $result = mysql_query($sql);
		if($result)
		{
			$count = mysql_num_rows($result);
			if($count == 1)
			{
				$Row = mysql_fetch_assoc($result);
				$my_id=md5(md5($Row['email'].$Row['username'].$Row['password']));
				if($my_id==$id)
				{
					$sql = "UPDATE users SET password = '".md5($Row['password'])."' WHERE username = '".$Row['username']."' AND trade_id = '".$my_coins->getTradeIdAccount()."';";
					if(mysql_query($sql))
					{
						mail($Row['email'], "New Password for Werris/Zelles Exchange Service","The new password for the user ".$Row['username']." is ".$Row['password']);
						$return_error = "Email with the new password was send!";
					} else {
						$return_error = "Cannot password recover!";
					}
				}
			} else {
				$return_error = "Cannot password recover!";
			}
			
		} else {
			$return_error = "Cannot password recover!";
		}
		
	} else {
		$return_error = "Cannot password recover!";
	}
}

$myusername = security($_POST['username']);
$mypassword = security($_POST['password']);
$myrepeat = security($_POST['repeat']);
$email = security($_POST['email']);
$email_repeat = security($_POST['email_repeat']);
$form_action = security($_POST['action']);
if($form_action=="forgot") {
	if($email) {
		$sql = "SELECT * FROM users WHERE email='$email' AND trade_id = '".$my_coins->getTradeIdAccount()."'";
        $result = mysql_query($sql);
		if($result)
		{
			$count = mysql_num_rows($result);
			if($count == 1)
			{
				$Row = mysql_fetch_assoc($result);
				$url = get_current_url();
				mail($email, "Passwort Recover for Werris/Zelles Exchange Service","Please copy the url in your browser<br/>\n ".$url."?recover=1&username=".$Row['username']."&id=".md5(md5($email.$Row['username'].$Row['password'])));
				$return_error = "Email with instructions details to the new password was sent to ".$email."!";
			} else {
				$return_error = "No Email found!";
			}
		} else {
			$return_error = "System error";
		}		
	} else {
		$return_error = "No Email was entered.";
	}
}

if($form_action=="login") {
   $_SESSION['login_attempts'] = $login_attempts + 1;
   if($login_attempts<=5) {
      if($myusername) {
         if($mypassword) {
            $mypassword2 = md5($mypassword);
            $sql = "SELECT * FROM users WHERE username='$myusername' AND trade_id = '".$my_coins->getTradeIdAccount()."'";
            $result = mysql_query($sql);
            $count = mysql_num_rows($result);
            if($count==1) {
               $Pass_Query = mysql_query("SELECT * FROM users WHERE username='$myusername' AND trade_id = '".$my_coins->getTradeIdAccount()."'");
               while($Pass_Row = mysql_fetch_assoc($Pass_Query)) {
                  $db_Login_Pass_Check = $Pass_Row['password'];
                  if($mypassword2==$db_Login_Pass_Check) {
                     $return_error = "Logged in.";
                     $_SESSION['user_session'] = $myusername;
					 $_SESSION['user_password'] = $mypassword2;
					 $_SESSION['user_time'] = time();
                     header("location: index.php");
                  } else {
                     $return_error = "Invalid Password.";
					 $_SESSION['user_time'] = 0;
                  }
               }
            } else {
               $return_error = "User does not exist.";
			   $_SESSION['user_time'] = 0;
            }
         } else {
            $return_error = "No password was entered.";
			$_SESSION['user_time'] = 0;
         }
      } else {
         $return_error = "No username was entered.";
		 $_SESSION['user_time'] = 0;
      }
   } else {
      $return_error = "Temporary block due to excessive failed logins.";
	  $_SESSION['user_time'] = 0;
   }
}
if($form_action=="register") {
   $FEEBEE_1=$my_coins->coins[$my_coins->coins_names[0]]['FEEBEE'];
   $FEEBEE_2=$my_coins->coins[$my_coins->coins_names[1]]['FEEBEE'];
   $FEEBEE_3=$my_coins->coins[$my_coins->coins_names[2]]['FEEBEE'];
   $create_feebee_account = $my_coins->coins["create_feebee_account"];
   
   if($myusername && ($FEEBEE_1==$myusername || $FEEBEE_2==$myusername || $FEEBEE_3==$myusername) && !$create_feebee_account) {
		$coo_coo="foo_foo";
   } else {
		$coo_coo="tootoo";
   }
   if($coo_coo=="tootoo") {
   if($myusername) {
      if($mypassword) {
         if($mypassword==$myrepeat) {
            $uLength = strlen($myusername);
            $pLength = strlen($mypassword);
            if($uLength >= 3 && $uLength <= 30) {
               $return_error = "";
            } else {
               $return_error = "Username must be between 3 and 30 characters.";
			   $_SESSION['user_time'] = 0;
            }
            if($pLength >= 3 && $pLength <= 30) {
               $return_error = "";
            } else {
               $return_error = "Password must be between 3 and 30 characters.";
			   $_SESSION['user_time'] = 0;
            }
			if($email) {
				if($email!=$email_repeat)
				{
					$return_error = "Email did not match";
				}
				$SQL = "SELECT * FROM users WHERE email='$email' AND trade_id = '".$my_coins->getTradeIdAccount()."'";
				$result=mysql_query($SQL);
				$num_rows = mysql_num_rows($result);
				if($num_rows==1) {
                     $return_error = "Email already taken.";
					 $_SESSION['user_time'] = 0;
				}
			} else {
				$return_error = "No Email was entered.";
			}
            if($return_error == "") {
               if($db_found) {
                  $mypassword = md5($mypassword);
                  $SQL = "SELECT * FROM users WHERE username='$myusername' AND trade_id = '".$my_coins->getTradeIdAccount()."'";
                  $result = mysql_query($SQL);
                  $num_rows = mysql_num_rows($result);
                  if($num_rows==1) {
                     $return_error = "Username already taken.";
					 $_SESSION['user_time'] = 0;
                  } else {
                     if(!mysql_query("INSERT INTO users (id,date,ip,username,password,email,trade_id) VALUES ('','$date','$ip','$myusername','$mypassword', '$email', '".$my_coins->getTradeIdAccount()."')")){
                        $return_error = "System error.";
						$_SESSION['user_time'] = 0;
                     } else {
                        $return_error = "Logged in.";
                        $_SESSION['user_session'] = $myusername;
						$_SESSION['user_password'] = $mypassword2;
						$_SESSION['user_time'] = time();
                        header ("Location: index.php");
                     }
                  }
               }
            }
         } else {
            $return_error = "Passwords did not match";
         }
      } else {
         $return_error = "No password was entered.";
      }
   } else {
      $return_error = "No username was entered.";
   }
   } else {
      $return_error = "Registrations are disabled.";
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
         <td align="right" nowrap>
			<?php 
			if(isset($_SESSION['user_session']) && isset($_SESSION['user_password']) && isset($_SESSION['user_time']) && $_SESSION['user_time'] > 0)
			{
				echo"<form action=\"index.php\" method=\"POST\">";
				echo"<input type=\"hidden\" name=\"action\" value=\"logout\">
				<table>
				<tr>
					<td align=\"right\"><input type=\"text\" name=\"username\" placeholder=\"Username\" value=\"$_SESSION[user_session]\" style=\"width: 110px;\" required autofocus readonly=\"readonly\"></td>
					<td align=\"right\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Logout\"></td>
				</tr>
				</table>
				</form>";
			}
			else
			{
			echo"<form action=\"index.php\" method=\"POST\">";
            echo"
			<input type=\"hidden\" name=\"action\" value=\"login\">
			<table>
               <tr>
                  <td align=\"right\"><input type=\"text\" name=\"username\" placeholder=\"Username\" value=\"$myusername\" style=\"width: 110px;\" required autofocus></td>
                  <td align=\"right\"><input type=\"password\" name=\"password\" placeholder=\"Password\" style=\"width: 110px;\" required></td>
                  <td align=\"right\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Login\"></td>
			   </tr>
            </table>
            </form>
			";
			}
			?>
         </td>
      </tr>
   </table>
   </div>
   <p></p>
   <?php if($return_error) { echo '<div align="center" class="error-msg" nowrap>'.$return_error.'</div><p></p>'; } ?>
   <table class="right-panel-table">
      <tr>
         <td valign="top" align="left" class="right-panel-left">
   <div align="center" class="bodydiv">
      <table style="width: 100%;">
      <tr>
       <div style="padding-left:5px;"><ul style="list-style-type:none;display:initial;">
   
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
                  <td align="left" nowrap><a href="#" onclick="setbuyrateamounts(0);">0</a> <?php echo $BTC; ?></td>
                  <td align="right" nowrap><?php echo '<a href="#" onclick="setbuyrates('.$Selling_Rate.');">'.$Selling_Rate.'</a> '.$BTC; ?></td>
               </tr>
            </table>
            </div>
            <table>
               <tr>
                  <td colspan="3" style="height: 10px;">
                  </td>
               </tr><tr>
                  <td align="right" nowrap><b>Quantity</b></td>
                  <td align="right" nowrap><input type="text" name="order-amount" value="0" id="buy-quantity" onkeyup="buycalculator();" onmouseenter="buycalculator();" onchange="buycalculator();" onmouseleave="buycalculator();" class="inputtrade"></td>
                  <td align="left" nowrap><?php echo $BTCRYX; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Rate</b></td>
                  <td align="right" nowrap><input type="text" name="order-rate" value="<?php echo $Selling_Rate; ?>" id="buy-rate" onkeyup="buycalculator();" onmouseenter="buycalculator();" onchange="buycalculator();" onmouseleave="buycalculator();" class="inputtrade"></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Sub-total</b></td>
                  <td align="right" nowrap><font id="buy-subtotal"><?php $bsubtotal = 0 * $Selling_Rate; echo satoshitize($bsubtotal); ?></font></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               <?php
			   $bfee=(($bsubtotal / 100) / 5);
			   $bfee=satoshitize($bfee);
			   if($my_coins->coins[$BTCS]["buy_fee"]==true)
			   {
			   echo'
			   </tr><tr>
                  <td align="right" nowrap><b>Fee</b></td>
                  <td align="right" nowrap><font id="buy-fee">'.$bfee.'</font></td>
                  <td align="left" nowrap>'.$BTC.'</td>
			   ';
			   } else {
					echo'<input id="buy-fee" type="hidden" value="0">';
			   }
			   ?>
               </tr><tr>
                  <td colspan="3" style="height: 10px;"></td>
               </tr><tr>
                  <td colspan="3" align="right" nowrap><input type="submit" value="Buy" onmouseenter="buycalculator();" class="buybutton"></td>
               </tr>
            </table>
         </td>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <div align="center" class="buy-sells-box">
            <table style="width: 260px; height: 50px;">
               <tr>
                  <td align="left" style="font-weight: bold;" nowrap>Balance:</td>
                  <td align="right" style="font-weight: bold;" nowrap>Highest Buy Value:</td>
               </tr><tr>
                  <td align="left" nowrap><a href="#" onclick="setsellamounts(0);">0</a> <?php echo $BTCRYX; ?></td>
                  <td align="right" nowrap><?php echo '<a href="#" onclick="setsellrates('.$Buying_Rate.');">'.$Buying_Rate.'</a> '.$BTC; ?></td>
               </tr>
            </table>
            </div>
            <table>
               <tr>
                  <td colspan="3" style="height: 10px;">
                  </td>
               </tr><tr>
                  <td align="right" nowrap><b>Quantity</b></td>
                  <td align="right" nowrap><input id="sell-quantity" type="text" name="order-amount" value="0" onkeyup="sellcalculator();" onchange="sellcalculator();" onmouseenter="sellcalculator();" onmouseleave="sellcalculator();" class="inputtrade"></td>
                  <td align="left" nowrap><?php echo $BTCRYX; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Rate</b></td>
                  <td align="right" nowrap><input id="sell-rate" type="text" name="order-rate" value="<?php echo $Buying_Rate; ?>" onkeyup="sellcalculator();" onchange="sellcalculator();" onmouseenter="sellcalculator();" onmouseleave="sellcalculator();" class="inputtrade"></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Sub-total</b></td>
                  <td align="right" nowrap><font id="sell-subtotal"><?php $ssubtotal = 0 * $Buying_Rate; echo satoshitize($ssubtotal); ?></font></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               <?php
			   $sfee=(($ssubtotal / 100) / 5);
			   $sfee=satoshitize($sfee);
			   if($my_coins->coins[$BTCS]["sell_fee"]==true)
			   {
			   echo'
			   </tr><tr>
                  <td align="right" nowrap><b>Fee</b></td>
                  <td align="right" nowrap><font id="sell-fee">'.$sfee.'</font></td>
                  <td align="left" nowrap>'.$BTC.'</td>
			   ';
			   } else {
					echo'<input id="sell-fee" type="hidden" value="0">';
			   }
			   ?>
               </tr><tr>
                  <td colspan="3" style="height: 10px;"></td>
               </tr><tr>
                  <td colspan="3" align="right" nowrap><input type="submit" value="Sell" onmouseenter="sellcalculator();" class="sellbutton"></td>
               </tr>
            </table>
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
            <div align="left" class="right-panel">
            <form action="index.php" method="POST">
            <input type="hidden" name="action" value="register">
            <table style="width: 100%;">
               <tr>
                  <td align="left"><b>Register a new account:</b></td>
               </tr><tr>
                  <td align="right"><input type="text" name="username" placeholder="Username" value"<?php echo $myusername; ?>" style="width: 100%;" required></td>
               </tr><tr>
                  <td align="right"><input type="password" name="password" placeholder="Password" style="width: 100%;" required></td>
               </tr><tr>
                  <td align="right"><input type="password" name="repeat" placeholder="Repeat Password" style="width: 100%;" required></td>
			   </tr><tr>
                  <td align="right"><input type="text" name="email" placeholder="Email" style="width: 100%;" required></td>
               </tr><tr>
                  <td align="right"><input type="text" name="email_repeat" placeholder="Repeat Email" style="width: 100%;" required></td>
               </tr><tr>
                  <td align="right"><input type="submit" name="submit" class="button" value="Register"></td>
               </tr>
            </table>
            </form>
            </div>
			<p></p>
			<div align="left" class="right-panel">
            <form action="index.php" method="POST">
            <input type="hidden" name="action" value="forgot">
            <table style="width: 100%;">
               <tr>
                  <td align="left"><b>Forgot Username or Password?</b></td>
               </tr><tr>
                  <td align="right"><input type="text" name="email" placeholder="Email" style="width: 100%;" required></td>
               </tr><tr>
                  <td align="right"><input type="submit" name="submit" class="button" value="Recover"></td>
               </tr>
            </table>
            </form>
            </div>
            <p></p>
            <div align="center" class="pending-right">
            Under development.
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