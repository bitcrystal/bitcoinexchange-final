<?php
error_reporting(0);
if(!$user_session) {
   $do = "nothing";
} else {
   $TXSSS_DISP = "";
   $TXSSS = "";
   $bold_txxs = "";
   $name = "";
   $prefix = "";
   for($i=0;$i<$count_daemons;$i++)
		{
		$wallet_id=$my_coins->getWalletId($user_session,$Bitcoind[$i]["cid"]);
		$Bitcoind_List_Transactions = $Bitcoind[$i]["daemon"]->listtransactions($wallet_id,50);
		$name = $Bitcoind[$i]["name"];
		$prefix = $Bitcoind[$i]["prefix"];
		foreach($Bitcoind_List_Transactions as $Bitcoind_List_Transaction) {
			if($bold_txxs=="") { $bold_txxs = "color: #666666; "; } else { $bold_txxs = ""; }
			if($Bitcoind_List_Transaction['category']=="receive") {
				if(5>=$Bitcoind_List_Transaction['confirmations']) {
					$TXSSS_DISP = "1";
					$TXSSS .= '<tr>
                          <td align="right" style="'.$bold_txxs.'padding-left: 5px;" nowrap>'.abs($Bitcoind_List_Transaction['amount']).' '.$prefix.' / '.$Bitcoind_List_Transaction['confirmations'].' confs</span></td>
                       </tr>';
				}
			}
		}
   $wallet_id=$my_coins->getWalletId($user_session,$Bitcrystald[$i]["cid"]);
   $Bitcrystald_List_Transactions = $Bitcrystald[$i]["daemon"]->listtransactions($wallet_id,10);
   $name = $Bitcrystald[$i]["name"];
   $prefix = $Bitcrystald[$i]["prefix"];
   foreach($Bitcrystald_List_Transactions as $Bitcrystald_List_Transaction) {
	  if($bold_txxs=="") { $bold_txxs = "color: #666666; "; } else { $bold_txxs = ""; }
	  if($Bitcrystald_List_Transaction['category']=="receive") {
         if(5>=$Bitcrystald_List_Transaction['confirmations']) {
			$TXSSS_DISP = "1";
            $TXSSS .= '<tr>
                          <td align="right" style="'.$bold_txxs.'padding-left: 5px;" nowrap>'.abs($Bitcrystald_List_Transaction['amount']).'</span> '.$prefix.' / '.$Bitcrystald_List_Transaction['confirmations'].' confs</td>
                       </tr>';
         }
      }
   }
   $wallet_id=$my_coins->getWalletId($user_session,$Bitcrystalxd[$i]["cid"]);
   $Bitcrystalxd_List_Transactions = $Bitcrystalxd[$i]["daemon"]->listtransactions($wallet_id,10);
   $name = $Bitcrystalxd[$i]["name"];
   $prefix = $Bitcrystalxd[$i]["prefix"];
   foreach($Bitcrystalxd_List_Transactions as $Bitcrystalxd_List_Transaction) {
	  if($bold_txxs=="") { $bold_txxs = "color: #666666; "; } else { $bold_txxs = ""; }
      if($Bitcrystalxd_List_Transaction['category']=="receive") {
         if(5>=$Bitcrystalxd_List_Transaction['confirmations']) {
            $TXSSS_DISP = "1";
            $TXSSS .= '<tr>
                          <td align="right" style="'.$bold_txxs.'padding-left: 5px;" nowrap>'.abs($Bitcrystalxd_List_Transaction['amount']).' '.$prefix.' / '.$Bitcrystalxd_List_Transaction['confirmations'].' confs</td>
                       </tr>';
         }
      }
   }
   }
   if($TXSSS_DISP=="1") {
      echo '<div align="left" class="pending-right">
            <b>Incoming:</b>
            <table style="width: 100%;">'.$TXSSS.'</table>
            </center>
            </div>
            <p></p>';
   }

}
?>