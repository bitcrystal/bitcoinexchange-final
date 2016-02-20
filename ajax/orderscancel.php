<?php
error_reporting(0);
$actions = array("buy","sell");
$actions_ = array("Buy", "Sell");
for($i=0; $i < 2; $i++)
{
	$sql = "SELECT * FROM ".$actions[$i]."_orderbook WHERE want='".$BTC."' and username='".$user_session."' and trade_id='".$my_coins->getTradeId()."' and trade_with = '".$BTCRYX."' and (processed = '1' or processed = '3' or processed > 1000) ORDER BY rate ASC";
	$result = mysql_query($sql);
	$count = mysql_num_rows($result);
	if($count!=0) {
		echo '<table>
				<tr>
               <td colspan="5" align="left" style="font-weight: bold; padding: 2px; padding-left: 5px;" nowrap>Your '.$actions_[$i].' Orders</td>
            </tr><tr>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Date</td>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Action</td>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Amount ('.$BTCRYX.')</td>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Rate ('.$BTC.')</td>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Total ('.$BTC.')</td>
			   <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Cancel Order</td>
            </tr>';
		$Query = mysql_query($sql);
		while($Row = mysql_fetch_assoc($Query)) {
			$Orders_Processed = $Row['processed'];
			$Orders_DATE = $Row['date'];
			$Orders_Action = $Row['action'];
			$Orders_Amount = $Row['amount'];
			$Orders_Rate = $Row['rate'];
			$Orders_Total = $Row['total'];
			$Orders_ID = $Row['id'];
			$cancel_order="Cancel";
			$cancel_order_="cancel";
			if($Orders_Action=="buy") { $Orders_Action = '<div align="center" class="sellbuttonmini">Buy</div>'; }
			if($Orders_Action=="sell") { $Orders_Action = '<div align="center" class="sellbuttonmini">Sell</div>'; }
			if($Orders_Processed>1000) 
			{
				$cancel = true;
				$diff = time()-$Orders_Processed;
				if($diff >= 300)
				{
					$cancel=false;
					$diff = "";
				} else {
					$diff = (300 - $diff);
					$diff = " (".$diff.")";
				}
				
				if($cancel)
				{
					$cancel_order = "Delete".$diff; 
					$cancel_order_ = "delete";
				} else {
					$sql = "UPDATE ".$actions[$i]."_orderbook SET processed='1' WHERE want='".$BTC."' and id='$Orders_ID' and username='$user_session' and trade_id  = '".$my_coins->getTradeId()."' and trade_with = '$BTCRYX' and processed = '".$Orders_Processed."'";
					$result = mysql_query($sql);
				}
			} else if ($Orders_Processed == 3) {
				$sql = "UPDATE ".$actions[$i]."_orderbook SET processed='".time()."' WHERE want='".$BTC."' and id='$Orders_ID' and username='$user_session' and trade_id  = '".$my_coins->getTradeId()."' and trade_with = '$BTCRYX' and processed = '3'";
				$result = mysql_query($sql);
				$cancel_order = "Delete"; 
				$cancel_order_ = "delete";
			}
			
			echo '<tr>
					<td align="left" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_DATE.'</td>
					<td align="left" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Action.'</td>
					<td align="right" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Amount.'</td>
					<td align="right" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Rate.'</td>
					<td align="right" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Total.'</td>
					<td align="right" style="padding: 1px; padding-left: 10px;" nowrap><a href="home.php?'.$cancel_order_.'='.$Orders_ID.'&type='.$actions[$i].'&processed='.$Orders_Processed.'"><div align="center" class="'.$cancel_order_.'buttonmini">'.$cancel_order.'</div></a></td>
				</tr>';
		}
		echo '</table>';
	} else {
		echo '<table>
				<tr>
					<td align="left" style="font-weight: bold; padding: 2px; padding-left: 5px;" nowrap>Your '.$actions_[$i].' Orders</td>
				</tr><tr>
					<td align="left" style="padding: 2px; padding-left: 15px;" nowrap>There has been no '.$actions[$i].' orders created!</td>
				</tr>
			</table>';
	}
}
?>