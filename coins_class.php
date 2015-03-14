<?php
require_once 'jsonRPCClient.php';
require_once 'w_coins_settings.php';
require_once 'my_all_coins.php';
class w_coins {
	private $my_all_coins;
	private $my_w;
	public $coins_names;
	public $coins_names_prefix;
	private $coins_count;
	public $coins;
	private $enabled_coins;
	private $enabled_coins_count;
	private $is_enabled_coins;
	private $is_enabled_default_coins;
	private $current_trade_coin_names;
	private $current_trade_coin_names_prefix;
	private $current_trade_from_coin_prefix;
	private $current_trade_from_coin_name;
	private $current_trade_to_coin_prefix;
	private $current_trade_to_coin_name;
	public $trade_coins;
	private $instance_id;
	private $select_instance_id;
	private $w_coins_settings = array();
	private static $SINGLETON = NULL;
	
	private function initCoins($add_coins) {
		//The amount of coins you are add must dividable through 3
		//The coins configs file for this coins you can find in the directory my_coins
		$count = count($add_coins);
		if($count%3!=0)
			throw("ERROR ELEMENTS OF THE COINS ARRAY IS NOT DIVIDABLE THROUGH 3");
		for($i=0; $i < $count; $i+=3)
		{
			$this->my_all_coins->add($add_coins[0+$i]);
			$this->my_all_coins->add($add_coins[1+$i]);
			$this->my_all_coins->add($add_coins[2+$i]);
		}
	}
	
	private function initFeeBeeAccount($set) {
		$this->setFeeBeeAccount($set);
	}
	
	private function __construct($add_coins = false, $init_feebee_account = false) {
		$this->my_all_coins=my_all_coins::get();
		if($add_coins!=false)
		{
			$this->initCoins($add_coins);
		}
		else
		{
			$add_coins = array();
			$add_coins[0] = "Bitcoin";
			$add_coins[1] = "Bitcrystal";
			$add_coins[2] = "Bitcrystalx";
			$this->initCoins($add_coins);
		}
		$this->my_all_coins->build();
		if($init_feebee_account!=true)
			$init_feebee_account=false;
		$this->initFeeBeeAccount($init_feebee_account);
		$instance_id = 0;
		$select_instance_id = 0;
		$tmp = $this->my_all_coins->get_last_w_coins_settings();
		$this->w_coins_settings = $tmp[0];
		$tmp = $this->my_all_coins->getCoins();
		$this->coins = $tmp[0];
		$tmp = $this->my_all_coins->getCoinsNames();
		$this->coins_names = $tmp[0];
		$tmp = $this->my_all_coins->getCoinsNamesPrefix();
		$this->coins_names_prefix = $tmp[0];
		$this->coins_count=count($this->coins_names);
		
		$this->my_w = $this->w_coins_settings[0];
		$this->coins["create_feebee_account"]=$this->my_w->coins["create_feebee_account"];
		
		$this->coins_names[0]=$this->my_w->coins["coin_name_1"];
		$this->coins_names[1]=$this->my_w->coins["coin_name_2"];
		$this->coins_names[2]=$this->my_w->coins["coin_name_3"];

		$this->coins_names_prefix[0]=$this->my_w->coins["coin_prefix_1"];
		$this->coins_names_prefix[1]=$this->my_w->coins["coin_prefix_2"];
		$this->coins_names_prefix[2]=$this->my_w->coins["coin_prefix_3"];
		
		$this->coins[$this->coins_names[0]] = array();
		$this->coins[$this->coins_names[0]]["enabled"]=false;
		$this->coins[$this->coins_names[0]]["daemon"]=false;
		$this->coins[$this->coins_names[0]]["rpcsettings"]=array();
		$this->coins[$this->coins_names[0]]["fee"]=$this->my_w->coins["coin_fee_1"];
		$this->coins[$this->coins_names[0]]["FEEBEE"]=$this->my_w->coins["coin_feebee_1"];
		$this->coins[$this->coins_names[0]]["buy_fee"]=$this->my_w->coins["coin_buy_fee_1"];
		$this->coins[$this->coins_names[0]]["sell_fee"]=$this->my_w->coins["coin_sell_fee_1"];
		
		$this->coins[$this->coins_names[1]] = array();
		$this->coins[$this->coins_names[1]]["enabled"]=false;
		$this->coins[$this->coins_names[1]]["daemon"]=false;
		$this->coins[$this->coins_names[1]]["rpcsettings"]=array();
		$this->coins[$this->coins_names[1]]["fee"]=$this->my_w->coins["coin_fee_2"];
		$this->coins[$this->coins_names[1]]["FEEBEE"]=$this->my_w->coins["coin_feebee_2"];
		$this->coins[$this->coins_names[1]]["buy_fee"]=$this->my_w->coins["coin_buy_fee_2"];
		$this->coins[$this->coins_names[1]]["sell_fee"]=$this->my_w->coins["coin_sell_fee_2"];
		
		$this->coins[$this->coins_names[2]] = array();
		$this->coins[$this->coins_names[2]]["enabled"]=false;
		$this->coins[$this->coins_names[2]]["daemon"]=false;
		$this->coins[$this->coins_names[2]]["rpcsettings"]=array();
		$this->coins[$this->coins_names[2]]["fee"]=$this->my_w->coins["coin_fee_3"];
		$this->coins[$this->coins_names[2]]["FEEBEE"]=$this->my_w->coins["coin_feebee_3"];
		$this->coins[$this->coins_names[2]]["buy_fee"]=$this->my_w->coins["coin_buy_fee_3"];
		$this->coins[$this->coins_names[2]]["sell_fee"]=$this->my_w->coins["coin_sell_fee_3"];
		
		$coin0rpc = $this->my_w->coins[$this->coins_names[0]]["rpcsettings"];
		$coin1rpc = $this->my_w->coins[$this->coins_names[1]]["rpcsettings"];
		$coin2rpc = $this->my_w->coins[$this->coins_names[2]]["rpcsettings"];
		
		$this->coins[$this->coins_names[0]]["rpcsettings"]["user"]=$coin0rpc["user"];
		$this->coins[$this->coins_names[0]]["rpcsettings"]["pass"]=$coin0rpc["pass"];
		$this->coins[$this->coins_names[0]]["rpcsettings"]["host"]=$coin0rpc["host"];
		$this->coins[$this->coins_names[0]]["rpcsettings"]["port"]=$coin0rpc["port"];
		$this->coins[$this->coins_names[0]]["rpcsettings"]["walletpassphrase"]=$coin0rpc["walletpassphrase"];
		$this->coins[$this->coins_names[0]]["rpcsettings"]["walletpassphrase_timeout"]=$coin0rpc["walletpassphrase_timeout"];

		$this->coins[$this->coins_names[1]]["rpcsettings"]["user"]=$coin1rpc["user"];
		$this->coins[$this->coins_names[1]]["rpcsettings"]["pass"]=$coin1rpc["pass"];
		$this->coins[$this->coins_names[1]]["rpcsettings"]["host"]=$coin1rpc["host"];
		$this->coins[$this->coins_names[1]]["rpcsettings"]["port"]=$coin1rpc["port"];
		$this->coins[$this->coins_names[1]]["rpcsettings"]["walletpassphrase"]=$coin1rpc["walletpassphrase"];
		$this->coins[$this->coins_names[1]]["rpcsettings"]["walletpassphrase_timeout"]=$coin1rpc["walletpassphrase_timeout"];

		$this->coins[$this->coins_names[2]]["rpcsettings"]["user"]=$coin2rpc["user"];
		$this->coins[$this->coins_names[2]]["rpcsettings"]["pass"]=$coin2rpc["pass"];
		$this->coins[$this->coins_names[2]]["rpcsettings"]["host"]=$coin2rpc["host"];
		$this->coins[$this->coins_names[2]]["rpcsettings"]["port"]=$coin2rpc["port"];
		$this->coins[$this->coins_names[2]]["rpcsettings"]["walletpassphrase"]=$coin2rpc["walletpassphrase"];
		$this->coins[$this->coins_names[2]]["rpcsettings"]["walletpassphrase_timeout"]=$coin2rpc["walletpassphrase_timeout"];
		
		$this->enabled_coins=array();
		
		$this->enabled_coins[0]=$this->coins_names[0];
		$this->enabled_coins[1]=$this->coins_names[1];
		$this->enabled_coins[2]=$this->coins_names[2];

		$this->is_enabled_coins=false;
		$this->is_enabled_default_coins=false;

		$this->current_trade_coin_names = array();
		
		$this->current_trade_coin_names[0]=$this->coins_names[0];
		$this->current_trade_coin_names[1]=$this->coins_names[1];

		$this->current_trade_coin_names_prefix = array();
		
		$this->current_trade_coin_names_prefix[0]=$this->coins_names_prefix[0];
		$this->current_trade_coin_names_prefix[1]=$this->coins_names_prefix[1];

		$this->current_trade_from_coin_prefix=$this->coins_names_prefix[0];
		$this->current_trade_from_coin_name=$this->coins_names[0];
		$this->current_trade_to_coin_prefix=$this->coins_names_prefix[1];
		$this->current_trade_to_coin_name=$this->coins_names[1];
		
		$this->trade_coins=array();
		$this->trade_coins["BTCRY"] = array();
		$this->trade_coins["BTCRY"]["BTC"]= $this->current_trade_from_coin_prefix;
		$this->trade_coins["BTCRY"]["BTCRYX"]= $this->current_trade_to_coin_prefix;
		$this->trade_coins["BTCRY"]["BTCS"]= $this->current_trade_from_coin_name;
		$this->trade_coins["BTCRY"]["BTCRYXS"]= $this->current_trade_to_coin_name;
		$this->trade_coins["BTCRYX"] = array();
		$this->trade_coins["BTCRYX"]["BTC"]= $this->current_trade_from_coin_prefix;
		$this->trade_coins["BTCRYX"]["BTCRYX"]= $this->current_trade_to_coin_prefix;
		$this->trade_coins["BTCRYX"]["BTCS"]= $this->current_trade_from_coin_name;
		$this->trade_coins["BTCRYX"]["BTCRYXS"]= $this->current_trade_to_coin_name;
		
		for($i = 3; $i < $this->coins_count; $i+=3)
		{
			$this->my_w = $this->w_coins_settings[floor($i/3)];
			$this->coins_names[0+$i]=$this->my_w->coins["coin_name_1"];
			$this->coins_names[1+$i]=$this->my_w->coins["coin_name_2"];
			$this->coins_names[2+$i]=$this->my_w->coins["coin_name_3"];

			$this->coins_names_prefix[0+$i]=$this->my_w->coins["coin_prefix_1"];
			$this->coins_names_prefix[1+$i]=$this->my_w->coins["coin_prefix_2"];
			$this->coins_names_prefix[2+$i]=$this->my_w->coins["coin_prefix_3"];
		
			$this->coins[$this->coins_names[0+$i]] = array();
			$this->coins[$this->coins_names[0+$i]]["enabled"]=false;
			$this->coins[$this->coins_names[0+$i]]["daemon"]=false;
			$this->coins[$this->coins_names[0+$i]]["rpcsettings"]=array();
			$this->coins[$this->coins_names[0+$i]]["fee"]=$this->my_w->coins["coin_fee_1"];
			$this->coins[$this->coins_names[0+$i]]["FEEBEE"]=$this->my_w->coins["coin_feebee_1"];
			$this->coins[$this->coins_names[0+$i]]["buy_fee"]=$this->my_w->coins["coin_buy_fee_1"];
			$this->coins[$this->coins_names[0+$i]]["sell_fee"]=$this->my_w->coins["coin_sell_fee_1"];
		
			$this->coins[$this->coins_names[1+$i]] = array();
			$this->coins[$this->coins_names[1+$i]]["enabled"]=false;
			$this->coins[$this->coins_names[1+$i]]["daemon"]=false;
			$this->coins[$this->coins_names[1+$i]]["rpcsettings"]=array();
			$this->coins[$this->coins_names[1+$i]]["fee"]=$this->my_w->coins["coin_fee_2"];
			$this->coins[$this->coins_names[1+$i]]["FEEBEE"]=$this->my_w->coins["coin_feebee_2"];
			$this->coins[$this->coins_names[1+$i]]["buy_fee"]=$this->my_w->coins["coin_buy_fee_2"];
			$this->coins[$this->coins_names[1+$i]]["sell_fee"]=$this->my_w->coins["coin_sell_fee_2"];
		
			$this->coins[$this->coins_names[2+$i]] = array();
			$this->coins[$this->coins_names[2+$i]]["enabled"]=false;
			$this->coins[$this->coins_names[2+$i]]["daemon"]=false;
			$this->coins[$this->coins_names[2+$i]]["rpcsettings"]=array();
			$this->coins[$this->coins_names[2+$i]]["fee"]=$this->my_w->coins["coin_fee_3"];
			$this->coins[$this->coins_names[2+$i]]["FEEBEE"]=$this->my_w->coins["coin_feebee_3"];
			$this->coins[$this->coins_names[2+$i]]["buy_fee"]=$this->my_w->coins["coin_buy_fee_3"];
			$this->coins[$this->coins_names[2+$i]]["sell_fee"]=$this->my_w->coins["coin_sell_fee_3"];
		
			$coin0rpc = $this->my_w->coins[$this->coins_names[0+$i]]["rpcsettings"];
			$coin1rpc = $this->my_w->coins[$this->coins_names[1+$i]]["rpcsettings"];
			$coin2rpc = $this->my_w->coins[$this->coins_names[2+$i]]["rpcsettings"];
		
			$this->coins[$this->coins_names[0+$i]]["rpcsettings"]["user"]=$coin0rpc["user"];
			$this->coins[$this->coins_names[0+$i]]["rpcsettings"]["pass"]=$coin0rpc["pass"];
			$this->coins[$this->coins_names[0+$i]]["rpcsettings"]["host"]=$coin0rpc["host"];
			$this->coins[$this->coins_names[0+$i]]["rpcsettings"]["port"]=$coin0rpc["port"];
			$this->coins[$this->coins_names[0+$i]]["rpcsettings"]["walletpassphrase"]=$coin0rpc["walletpassphrase"];
			$this->coins[$this->coins_names[0+$i]]["rpcsettings"]["walletpassphrase_timeout"]=$coin0rpc["walletpassphrase_timeout"];

			$this->coins[$this->coins_names[1+$i]]["rpcsettings"]["user"]=$coin1rpc["user"];
			$this->coins[$this->coins_names[1+$i]]["rpcsettings"]["pass"]=$coin1rpc["pass"];
			$this->coins[$this->coins_names[1+$i]]["rpcsettings"]["host"]=$coin1rpc["host"];
			$this->coins[$this->coins_names[1+$i]]["rpcsettings"]["port"]=$coin1rpc["port"];
			$this->coins[$this->coins_names[1+$i]]["rpcsettings"]["walletpassphrase"]=$coin1rpc["walletpassphrase"];
			$this->coins[$this->coins_names[1+$i]]["rpcsettings"]["walletpassphrase_timeout"]=$coin1rpc["walletpassphrase_timeout"];

			$this->coins[$this->coins_names[2+$i]]["rpcsettings"]["user"]=$coin2rpc["user"];
			$this->coins[$this->coins_names[2+$i]]["rpcsettings"]["pass"]=$coin2rpc["pass"];
			$this->coins[$this->coins_names[2+$i]]["rpcsettings"]["host"]=$coin2rpc["host"];
			$this->coins[$this->coins_names[2+$i]]["rpcsettings"]["port"]=$coin2rpc["port"];
			$this->coins[$this->coins_names[2+$i]]["rpcsettings"]["walletpassphrase"]=$coin2rpc["walletpassphrase"];
			$this->coins[$this->coins_names[2+$i]]["rpcsettings"]["walletpassphrase_timeout"]=$coin2rpc["walletpassphrase_timeout"];
			
			$this->enabled_coins[0+$i]=$this->coins_names[0+$i];
			$this->enabled_coins[1+$i]=$this->coins_names[1+$i];
			$this->enabled_coins[2+$i]=$this->coins_names[2+$i];
		}

		$this->my_w = $this->w_coins_settings[0];
		$this->enabled_coins_count = count($this->enabled_coins);
		$this->enable_default_coins();
	}
	
	public function set_current_from_trade_coin_prefix_and_name($prefix, $name)
	{
		$this->current_trade_from_coin_prefix=$prefix;
		$this->current_trade_from_coin_name=$name;
		$this->trade_coins["BTCRY"]["BTC"]= $this->current_trade_from_coin_prefix;
		$this->trade_coins["BTCRY"]["BTCS"]= $this->current_trade_from_coin_name;
		$this->trade_coins["BTCRYX"]["BTC"]= $this->current_trade_from_coin_prefix;
		$this->trade_coins["BTCRYX"]["BTCS"]= $this->current_trade_from_coin_name;
	}

	public function set_current_to_trade_coin_prefix_and_name($prefix, $name)
	{
		$this->current_trade_to_coin_prefix=$prefix;
		$this->current_trade_to_coin_name=$name;
		$this->trade_coins["BTCRY"]["BTCRYX"]= $this->current_trade_to_coin_prefix;
		$this->trade_coins["BTCRY"]["BTCRYXS"]= $this->current_trade_to_coin_name;
		$this->trade_coins["BTCRYX"]["BTCRYX"]= $this->current_trade_to_coin_prefix;
		$this->trade_coins["BTCRYX"]["BTCRYXS"]= $this->current_trade_to_coin_name;
	}

	public function get_current_from_trade_coin_prefix_and_name(&$prefix, &$name)
	{
		$prefix=$this->current_from_trade_coin_prefix;
		$name=$this->current_from_trade_coin_name;
	}

	public function get_current_to_trade_coin_prefix_and_name(&$prefix, &$name)
	{
		$prefix=$this->current_to_trade_coin_prefix;
		$name=$this->current_to_trade_coin_name;
	}

	public function get_coins_prefix_of_name($name)
	{
		for($i=0; $i < $this->coins_count; $i++)
		{
			if($this->coins_names[$i]==$name)
			{
				return $this->coins_names_prefix[$i];
			}
		}
		return "unknown";
	}

	public function get_coins_name_of_prefix($prefix)
	{
		for($i=0; $i < $this->coins_count; $i++)
		{
			if($this->coins_names_prefix[$i]==$prefix)
			{
				return $this->coins_names[$i];
			}
		}
		return "unknown";
	}
	
	public function get_coins_walletpassphrase_of_name($name)
	{
		if($this->coins[$name]["rpcsettings"]["walletpassphrase"]=="")
			return "";
		return $this->coins[$name]["rpcsettings"]["walletpassphrase"];	
	}
	
	public function get_coins_walletpassphrase_timeout_of_name($name)
	{
		return $this->coins[$name]["rpcsettings"]["walletpassphrase_timeout"];
	}

	public function set_coins_daemon($name, $rpc_user, $rpc_pass, $rpc_host, $rpc_port)
	{
		if($this->coins[$name]["enabled"]==false)
		{
			return false;
		} else {
			if($this->coins[$name]["daemon"]==false)
			{
				$url = "http://".$rpc_user.":".$rpc_pass."@".$rpc_host.":".$rpc_port."/";
				$this->coins[$name]["daemon"]=new jsonRPCClient($url);
			}
			$walletpassphrase=$this->get_coins_walletpassphrase_of_name($name);
			if($walletpassphrase!="")
			{
				$walletpassphrase_timeout=$this->get_coins_walletpassphrase_timeout_of_name($name);
				$this->coins[$name]["daemon"]->walletpassphrase($walletpassphrase, $walletpassphrase_timeout);
			}
			return true;
		}	
	}

	public function get_coins_daemon_safe($name, $rpc_user, $rpc_pass, $rpc_host, $rpc_port)
	{
		$rv=set_coins_daemon($name, $rpc_user, $rpc_pass, $rpc_host, $rpc_port);
		if($rv==true)
		{
			$walletpassphrase=$this->get_coins_walletpassphrase_of_name($name);
			if($walletpassphrase!="")
			{
				$walletpassphrase_timeout=$this->get_coins_walletpassphrase_timeout_of_name($name);
				$this->coins[$name]["daemon"]->walletpassphrase($walletpassphrase, $walletpassphrase_timeout);
			}
			return $this->coins[$name]["daemon"];
		}
	}

	public function get_coins_daemon($name)
	{
		if($this->coins[$name]["enabled"] == false || $this->coins[$name]["daemon"] == false)
		{
			return false;
		} else {
			$walletpassphrase=$this->get_coins_walletpassphrase_of_name($name);
			if($walletpassphrase!="")
			{
				$walletpassphrase_timeout=$this->get_coins_walletpassphrase_timeout_of_name($name);
				$this->coins[$name]["daemon"]->walletpassphrase($walletpassphrase, $walletpassphrase_timeout);
			}
			return $this->coins[$name]["daemon"];
		}
	}

	public function get_coins_balance_old($name, $user_session)
	{
		if($this->coins[$name]["enabled"]==false)
			return "";
		return userbalance($user_session,$this->get_coins_prefix_of_name($name));
	}
	
	public function get_coins_balance($name, $account, $confirmations)
	{
		if($confirmations<=0)
		{
			return false;
		}
		$daemon = $this->get_coins_daemon($name);
		$transactions = $daemon->listtransactions($account);
		$money = 0;
		foreach($transactions as $transaction) {
			if($transaction['category']=="receive") {
				if($confirmations<=$transaction['confirmations']) {
					$amount = abs($transaction['amount']);
					$money+=$amount;
				} else {
					return false;
				}
			} else if ($transaction['category']=="send") {
				if($confirmations<=$transaction['confirmations']) {
					$amount = abs($transaction['amount']);
					$money -= $amount;
				} else {
					return false;
				}
			}
         }
		 return $money;
	}
	
	public function set_coins_balance($name, $account, $account2, $amount)
	{
		$balance=$this->get_coins_balance($name, $account, 6);
		$daemon = $this->get_coins_daemon($name);
		if($balance==false)
		{
			return false;
		}
		if($balance<0)
		{
			return false;
		}
		if($amount<0)
		{
			return false;		
		}
		$diff=$amount-$balance;
		if($diff==0)
		{
			return $balance;
		}
		if($diff<0)
		{
			$daemon->sendtoaddress($daemon->getaccountaddress($account2), abs($diff));
			
			$balance = $this->get_coins_balance($name, $account, 6);
			while($balance == false)
			{
				$balance = $this->get_coins_balance($name, $account, 6);
			}
			return $balance;
		}
		else
		{
			$daemon->sendtoaddress($daemon->getaccountaddress($account), $diff);
			$balance = $this->get_coins_balance($name, $account, 6);
			while($balance == false)
			{
				$balance = $this->get_coins_balance($name, $account, 6);
			}
			return $balance;
		}
		return $balance;
	}

	public function get_coins_balance_from_prefix($prefix, $user_session)
	{
		if($this->coins[$this->get_coins_name_of_prefix($prefix)]["enabled"]==false)
			return "";
		return userbalance($user_session,$prefix);
	}

	public function get_coins_address($name, $wallet_id)
	{
		if($this->coins[$name]["enabled"]==false)
			return "";
		$daemon = $this->get_coins_daemon($name);
		if($daemon==false)
		{
			return "";
		}
		return $daemon->getaccountaddress($wallet_id);
	}

	public function get_coins_address_from_prefix($prefix, $wallet_id)
	{
		$name=$this->get_coins_name_of_prefix($prefix);
		if($this->coins[$name]["enabled"]==false)
			return "";
		$daemon = $this->get_coins_daemon($name);
		if($daemon==false)
		{
				return "";
		}
		return $daemon->getaccountaddress($wallet_id);
	}


	private function enable_coins()
	{
		if($this->is_enabled_coins==true)
			return;

		for($i=0; $i < $this->enabled_coins_count; $i++)
		{
			for($j = 0; $j < $this->coins_count; $j++)
			{
				if($this->coins_names[$j]==$this->enabled_coins[$i])
				{
					$name = $this->coins_names[$j];
					$rpc_user = $this->coins[$name]["rpcsettings"]["user"];
					$rpc_pass = $this->coins[$name]["rpcsettings"]["pass"];
					$rpc_host = $this->coins[$name]["rpcsettings"]["host"];
					$rpc_port = $this->coins[$name]["rpcsettings"]["port"];
					$this->coins[$name]["enabled"]=true;
					$this->set_coins_daemon($name, $rpc_user, $rpc_pass, $rpc_host, $rpc_port);
					$j = $this->coins_count;
				}
			}
		}
		$this->is_enabled_coins=true;
	}

	private function enable_default_coins()
	{
		if($this->is_enabled_default_coins==true)
			return;

		for($i = 0; $i < $this->coins_count; $i++)
		{
			$name = $this->coins_names[$i];
			$rpc_user = $this->coins[$name]["rpcsettings"]["user"];
			$rpc_pass = $this->coins[$name]["rpcsettings"]["pass"];
			$rpc_host = $this->coins[$name]["rpcsettings"]["host"];
			$rpc_port = $this->coins[$name]["rpcsettings"]["port"];
			$this->coins[$name]["enabled"]=true;
			$this->set_coins_daemon($name, $rpc_user, $rpc_pass, $rpc_host, $rpc_port);
		}
		$this->is_enabled_default_coins=true;
	}
	
	public function outputCoinButtonLinks($website = NULL)
	{
		if($website==NULL)
			$website = basename($_SERVER['PHP_SELF']);
		$iid="";
		if($this->coins_count>3)
		$set="0";
		else
		$set="20";
		for($i=0;$i < $this->coins_count; $i+=3)
		{
			/*if(floor($i/3)>0)
					$iid = '&iid=' . $this->getId($i);
				else
					$iid = "";*/
			echo "<li style='float:left;width:auto;paddin-left:0;list-style-type:none;'><div class=\"coin-button\"><a href=\"".$website."?c=".$this->coins_names_prefix[0+$i]."_".$this->coins_names_prefix[1+$i].$iid."\" class=\"coin-link\">".$this->coins_names_prefix[1+$i]."/".$this->coins_names_prefix[0+$i]."</a></div></li>";
            echo "<li style='float:left;width:auto;paddin-left:0';list-style-type:none;><div class=\"coin-button\"><a href=\"".$website."?c=".$this->coins_names_prefix[2+$i]."_".$this->coins_names_prefix[1+$i].$iid."\" class=\"coin-link\">".$this->coins_names_prefix[1+$i]."/".$this->coins_names_prefix[2+$i]."</a></div></li>";
            //echo "<li style=\"padding-left: ".$set."px;\"><div class=\"coin-button\"><a href=\"".$website."?c=".$this->coins_names_prefix[2+$i]."_".$this->coins_names_prefix[1+$i].$iid."\" class=\"coin-link\">".$this->coins_names_prefix[1+$i]."/".$this->coins_names_prefix[2+$i]."</a></div></li>";
		}
	}
	
	public function coinSelecter($coin_selecter)
	{
		if(!$coin_selecter)
			return false;
		$trade_coin = false;
		
		if($coin_selecter==$this->coins_names_prefix[0]) 
		{ 
			$trade_coin = $this->coins_names_prefix[0];
			$this->setInstanceId(0);
			return $trade_coin;
		}
		
		for($i=0;$i < $this->coins_count; $i+=3)
		{
			if($coin_selecter==$this->coins_names_prefix[0+$i]."_".$this->coins_names_prefix[1+$i]) 
			{ 
				$trade_coin = $this->coins_names_prefix[0+$i]."_".$this->coins_names_prefix[1+$i];
				$this->setInstanceId($i);
				return $trade_coin;
			}
			if($coin_selecter==$this->coins_names_prefix[2+$i]."_".$this->coins_names_prefix[1+$i]) 
			{ 
				$trade_coin = $this->coins_names_prefix[2+$i]."_".$this->coins_names_prefix[1+$i];
				$this->setInstanceId($i);
				return $trade_coin;
			}
				
		}
		return $trade_coin;
	}
	
	public function coinSelecterSelect($coin_selecter)
	{
		$trade_coin = $this->coinSelecter($coin_selecter);
		if(!$trade_coin)
			return false;
			
		if($trade_coin == $this->coins_names_prefix[0])
		{
			$this->set_current_from_trade_coin_prefix_and_name($this->coins_names_prefix[0], $this->coins_names[0]);
			$this->set_current_to_trade_coin_prefix_and_name($this->coins_names_prefix[1], $this->coins_names[1]);
			return $trade_coin;
		}
		
		for($i=0;$i < $this->coins_count; $i+=3)
		{
			if($trade_coin==$this->coins_names_prefix[0+$i]."_".$this->coins_names_prefix[1+$i]) 
			{ 
				$this->set_current_from_trade_coin_prefix_and_name($this->coins_names_prefix[0+$i], $this->coins_names[0+$i]);
				$this->set_current_to_trade_coin_prefix_and_name($this->coins_names_prefix[1+$i], $this->coins_names[1+$i]);
			}
			if($trade_coin==$this->coins_names_prefix[2+$i]."_".$this->coins_names_prefix[1+$i]) 
			{ 
				$this->set_current_from_trade_coin_prefix_and_name($this->coins_names_prefix[2+$i], $this->coins_names[2+$i]);
				$this->set_current_to_trade_coin_prefix_and_name($this->coins_names_prefix[1+$i], $this->coins_names[1+$i]);
			}	
		}
		return $trade_coin;
	}
	
	public function outputBalancesOld($user_session)
	{
		if(!$user_session) {
			echo '<b>Finances:</b><p></p>
			<table style="width: 100%;">
			<tr>';
			for($i=0;$i < $this->coins_count; $i+=3)
			{
				if(floor($i/3)>0)
					$iid = '?iid=' . $this->getId($i);
				else
					$iid = "";
				echo'
					<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtc.php'.$iid.'">'.$this->coins_names_prefix[0+$i].'</a></td>
					<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btc">?</span></td>
					<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtcry.php'.$iid.'">'.$this->coins_names_prefix[1+$i].'</a></td>
					</tr><tr>
						<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btcry">?</span></td>
					</tr><tr>
						<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtcryx.php'.$iid.'">'.$this->coins_names_prefix[2+$i].'</a></td>
						<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btcryx">?</span></td>
					';
			}
			echo'</tr>
				</table>';
		} else {
			echo '<b>Finances:</b><p></p>
				<table style="width: 100%;">
				<tr>';
			for($i=0;$i < $this->coins_count; $i+=3)
			{
				echo'
					<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtc.php'.$iid.'">'.$this->coins_names_prefix[0+$i].'</a></td>
					<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btc">'.userbalance($user_session,$this->coins_names_prefix[0+$i]).'</span></td>
					</tr><tr>
						<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtcry.php'.$iid.'">'.$this->coins_names_prefix[1+$i].'</a></td>
						<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btcry">'.userbalance($user_session,$this->coins_names_prefix[1+$i]).'</span></td>
					</tr><tr>
						<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtcryx.php'.$iid.'">'.$this->coins_names_prefix[2+$i].'</a></td>
						<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btcryx">'.userbalance($user_session,$this->coins_names_prefix[2+$i]).'</span></td>
					';
			}
				echo'
				</tr>
				</table>
				';
		}
	}
	
	public function outputBalances($user_session)
	{
		$id=$this->getInstanceId();
		$i=$this->getCoinsInstanceId();
		//$this->setSelectInstanceId($id);
		if(!$user_session) {
			echo '<b>Finances:</b><p></p>
			<table style="width: 100%;">
			<tr>';
				echo'
					<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtc.php">'.$this->coins_names_prefix[0+$i].'</a></td>
					<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btc">?</span></td>
					<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtcry.php">'.$this->coins_names_prefix[1+$i].'</a></td>
					</tr><tr>
						<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btcry">?</span></td>
					</tr><tr>
						<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtcryx.php">'.$this->coins_names_prefix[2+$i].'</a></td>
						<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btcryx">?</span></td>
					';
			echo'</tr>
				</table>';
		} else {
			echo '<b>Finances:</b><p></p>
				<table style="width: 100%;">
				<tr>';
				echo'
					<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtc.php">'.$this->coins_names_prefix[0+$i].'</a></td>
					<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btc">'.userbalance($user_session,$this->coins_names_prefix[0+$i]).'</span></td>
					</tr><tr>
						<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtcry.php">'.$this->coins_names_prefix[1+$i].'</a></td>
						<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btcry">'.userbalance($user_session,$this->coins_names_prefix[1+$i]).'</span></td>
					</tr><tr>
						<td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtcryx.php">'.$this->coins_names_prefix[2+$i].'</a></td>
						<td align="right" style="padding-left: 5px;" nowrap><span id="balance-btcryx">'.userbalance($user_session,$this->coins_names_prefix[2+$i]).'</span></td>
					';
				echo'
				</tr>
				</table>
				';
		}
	}
	
	public function outputFooter($website = NULL)
	{
		if($website==NULL)
			$website = basename($_SERVER['PHP_SELF']);
		if($website!="home.php" && $website!="index.php" && $website!="market.php")
		{
			$website="home.php";
		}
		echo'<b>Trade Sections:</b> ';
		$link = "";
		$iid = "";
		for($i=0;$i < $this->coins_count; $i+=3)
		{
			if($i!=0)
				echo', ';
			/*if(floor($i/3)>0)
					$iid = '&iid=' . $this->getId($i);
				else
					$iid = "";*/
			echo'<a href="'.$website.'?c='.$this->coins_names_prefix[0+$i]."_".$this->coins_names_prefix[1+$i].$iid.'">'.$this->coins_names_prefix[1+$i].'/'.$this->coins_names_prefix[0+$i].'</a>, <a href="'.$website.'?c='.$this->coins_names_prefix[2+$i]."_".$this->coins_names_prefix[1+$i].$iid.'">'.$this->coins_names_prefix[1+$i].'/'.$this->coins_names_prefix[2+$i].'</a>';
		}
	}
	
	public function getInstanceId()
	{
		return floor($this->instance_id/3);
	}
	
	public function setInstanceId($id)
	{
		$this->instance_id=$id;
	}
	
	
	public function getCoinsInstanceId()
	{
		return $this->instance_id;
	}
	
	public function getId($id)
	{
		return $id;
	}
	
	public function getSelectInstanceId()
	{
		return floor($this->select_instance_id/3);
	}
	
	public function setSelectInstanceId($id)
	{
		$this->select_instance_id=$id;
	}
	
	public function getCoinsSelectInstanceId()
	{
		return $this->select_instance_id;
	}
	
	public function getBitcoindDaemons()
	{
		$array = array();
		$count = 0;
		for($i=0,$j=0;$i < $this->coins_count; $i++)
		{
			$r = $i%3;
			if($r==0||$r==3)
			{
				$array[$j] = array();
				$array[$j]["name"] = $this->coins_names[$i];
				$array[$j]["prefix"] = $this->coins_names_prefix[$i];
				$array[$j]["daemon"] = $this->coins[$this->coins_names[$i]]["daemon"];
				$array[$j]["cid"] = $i;
				$j++;
			}
		}
		$count = count($array);
		return array($array, $count);
	}
	
	public function getBitcrystaldDaemons()
	{
		$array = array();
		$count = 0;
		for($i=0,$j=0;$i < $this->coins_count; $i++)
		{
			$r = $i%3;
			if($r==1)
			{
				$array[$j] = array();
				$array[$j]["name"] = $this->coins_names[$i];
				$array[$j]["prefix"] = $this->coins_names_prefix[$i];
				$array[$j]["daemon"] = $this->coins[$this->coins_names[$i]]["daemon"];
				$array[$j]["cid"] = $i;
				$j++;
			}
		}
		$count = count($array);
		return array($array, $count);
	}
	
	public function getBitcrystalxdDaemons()
	{
		$array = array();
		$count = 0;
		for($i=0,$j=0;$i < $this->coins_count; $i++)
		{
			$r = $i%3;
			if($r==2)
			{
				$array[$j] = array();
				$array[$j]["name"] = $this->coins_names[$i];
				$array[$j]["prefix"] = $this->coins_names_prefix[$i];
				$array[$j]["daemon"] = $this->coins[$this->coins_names[$i]]["daemon"];
				$array[$j]["cid"] = $i;
				$j++;
			}
		}
		$count = count($array);
		return array($array, $count);
	}
	
	public function getTradeIdAccount()
	{
		//$cid=$this->getCoinsInstanceId();
		$cid=0;
		return $this->coins_names_prefix[0+$cid]."_".$this->coins_names_prefix[1+$cid]."_".$this->coins_names_prefix[2+$cid];
	}
	
	public function getTradeId()
	{
		$cid=$this->getCoinsInstanceId();
		return $this->coins_names_prefix[0+$cid]."_".$this->coins_names_prefix[1+$cid]."_".$this->coins_names_prefix[2+$cid];
	}
	
	
	public function getWalletId($user_session, $cid)
	{
		$iid = floor($cid/3);
		if($iid==0)
			$wallet_id = "zellesExchange(".$user_session.")";
		else
			$wallet_id = "zellesExchange(".$user_session.",".$cid.")";
		return $wallet_id;
	}
	
	public function outputDepositWithdrawLink()
	{
		$id = $this->getInstanceId();
		//$this->setSelectInstanceId($id);
		$cid = $this->getCoinsInstanceId();
		$link = 
 '<td colspan="2" align="right" valign="top" style="font-weight: bold; padding: 5px;">Deposit/Withdraw (<a href="fundsbtc.php">'.$this->coins_names_prefix[0+$cid].'</a>/<a href="fundsbtcry.php">'.$this->coins_names_prefix[1+$cid].'</a>/<a href="fundsbtcryx.php">'.$this->coins_names_prefix[2+$cid].'</a>)</td>';
		echo $link;
	}
	
	public function getFeeBeeAccount()
	{
		return $this->my_all_coins->getFeeBeeAccount();
	}
	
	public function setFeeBeeAccount($set)
	{
		$this->my_all_coins->setFeeBeeAccount($set);
	}
	
	public static function get($add_coins = false, $init_feebee_account = false)
	{
		if(self::$SINGLETON == NULL)
			self::$SINGLETON=new w_coins($add_coins,$init_feebee_account);
		return self::$SINGLETON;
	}
}
?>