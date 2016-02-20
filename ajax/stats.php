<?php
error_reporting(0);
$buy_subtotal = "0";
$buy_amounttotal = "0";
$Query = mysql_query("SELECT amount, rate FROM buy_orderbook WHERE want='".$BTC."' and trade_id='".$my_coins->getTradeId()."' and trade_with = '".$BTCRYX."' and processed='1' ORDER BY rate ASC");
while($Row = mysql_fetch_assoc($Query)) {
   $buy_amount = $Row['amount'];
   $buy_rate = $Row['rate'];
   $buy_subtotal += ($buy_amount * $buy_rate);
}
$btevalue = satoshitrim(satoshitize($buy_subtotal));

$buy_amounttotal = "0";
$Query = mysql_query("SELECT amount FROM sell_orderbook WHERE want='".$BTC."' and trade_id='".$my_coins->getTradeId()."' and trade_with = '".$BTCRYX."' and processed='1' ORDER BY rate ASC");
while($Row = mysql_fetch_assoc($Query)) {
   $buy_amount = $Row['amount'];
   $buy_amounttotal += $buy_amount;
}
$btevolume = satoshitrim(satoshitize($buy_amounttotal));

echo '<table>
         <tr>
            <td nowrap>'.$BTCRYX.'/'.$BTC.'</td>
            <td style="padding-left: 10px;" nowrap>Volume '.$btevolume.' / '.$btevalue.' '.$BTC.'</td>
         </tr>
      </table>';
?>