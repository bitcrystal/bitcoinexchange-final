<?php
require_once('my_coins/my_coin.php');
require_once('w_coins_settings.php');
class my_all_coins
{
	private $coins_names;
	private $coins_names_prefix;
	private $coins;
	private $count_coins_packages;
	private $cp;
	private $w_coins_settings;
	private $create_feebee_account;
	private static $SINGLETON = NULL;
	public function __construct() {
		$this->w_coins_settings=false;
		$this->create_feebee_account=false;
		$this->coins = array();
		$this->coins_names = array();
		$this->coins_names_prefix = array();
		$this->count_coins_packages = 0;
		$this->cp = 0;
	}
	
	public function add($name)
	{
		$this->coins_names[$this->cp]=$name;
		$this->cp++;
	}
	
	public function getCoinsNames()
	{
		$build = false;
		if($this->count_coins_packages==0)
		{
			$build = $this->build();
		} else {
			$build = true;
		}
		if(!$build)
		{
			return false;
		}
		return array($this->coins_names);
	}
	
	public function getCoinsNamesPrefix()
	{
		$build = false;
		if($this->count_coins_packages==0)
		{
			$build = $this->build();
		} else {
			$build = true;
		}
		if(!$build)
		{
			return false;
		}
		return array($this->coins_names);
	}
	
	public function build()
	{
		$count = count($this->coins_names);
		if($count%3!=0)
		{
			return 0;
		}
		$this->coins=array();
		for($i = 0; $i < $count; $i++)
		{
			include("my_coins/".$this->coins_names[$i].".php");
			$this->coins[$i]=array();
			$this->coins[$i]["name"]=$coin->getName();
			$this->coins[$i]["prefix"]=$coin->getPrefix();
			$this->coins[$i]["fee"]=$coin->getFee();
			$this->coins[$i]["feebee"]=$coin->getFeeBee();
			$this->coins[$i]["buy_fee"]=$coin->getBuyFee();
			$this->coins[$i]["sell_fee"]=$coin->getSellFee();
			$this->coins[$i]["rpcsettings"]=array();
			$this->coins[$i]["rpcsettings"]["user"]=$coin->getRpcUser();
			$this->coins[$i]["rpcsettings"]["pass"]=$coin->getRpcPass();
			$this->coins[$i]["rpcsettings"]["host"]=$coin->getRpcHost();
			$this->coins[$i]["rpcsettings"]["port"]=$coin->getRpcPort();
			$this->coins[$i]["rpcsettings"]["walletpassphrase"]=$coin->getRpcWalletpassphrase();
			$this->coins[$i]["rpcsettings"]["walletpassphrase_timeout"]=$coin->getRpcWalletpassphraseTimeout();
			$this->coins_names[$i]=$coin->getName();
			$this->coins_names_prefix[$i]=$coin->getPrefix();
		}
		$this->count_coins_packages = floor($count/3);
		return true;
	}
	
	
	public function getCoins()
	{
		$build = false;
		if($this->count_coins_packages==0)
		{
			$build = $this->build();
		} else {
			$build = true;
		}
		if(!$build)
		{
			return false;
		}
		return array($this->coins);
	}
	
	public function get_w_coins_settings($startoffset = 0)
	{
		$build = false;
		if($this->count_coins_packages==0)
		{
			$build = $this->build();
		} else {
			$build = true;
		}
		if(!$build)
		{
			return false;
		}
		$w_coins_settings = array();
		$count_p = $this->count_coins_packages;
		for($i = $startoffset; $i < $count_p; $i++)
		{
			$offset = $i*3;
			$w_coins_settings[$i]=new w_coins_settings();
			$w_coins_settings[$i]->set_names($this->coins_names[0+$offset],$this->coins_names[1+$offset],$this->coins_names[2+$offset]);
			$w_coins_settings[$i]->set_prefixes($this->coins_names_prefix[0+$offset],$this->coins_names_prefix[1+$offset],$this->coins_names_prefix[2+$offset]);
			$w_coins_settings[$i]->set_fees($this->coins[0+$offset]["fee"],$this->coins[1+$offset]["fee"],$this->coins[2+$offset]["fee"]);
			$w_coins_settings[$i]->set_feebees($this->coins[0+$offset]["feebee"],$this->coins[1+$offset]["feebee"],$this->coins[2+$offset]["feebee"]);
			$w_coins_settings[$i]->set_buy_fees($this->coins[0+$offset]["buy_fee"],$this->coins[1+$offset]["buy_fee"],$this->coins[2+$offset]["buy_fee"]);
			$w_coins_settings[$i]->set_sell_fees($this->coins[0+$offset]["sell_fee"],$this->coins[1+$offset]["sell_fee"],$this->coins[2+$offset]["sell_fee"]);
			$w_coins_settings[$i]->set_rpc_settings_coin_1($this->coins[0+$offset]["rpcsettings"]["user"],$this->coins[0+$offset]["rpcsettings"]["pass"],$this->coins[0+$offset]["rpcsettings"]["host"],$this->coins[0+$offset]["rpcsettings"]["port"],$this->coins[0+$offset]["rpcsettings"]["walletpassphrase"],$this->coins[0+$offset]["rpcsettings"]["walletpassphrase_timeout"]);
			$w_coins_settings[$i]->set_rpc_settings_coin_2($this->coins[1+$offset]["rpcsettings"]["user"],$this->coins[1+$offset]["rpcsettings"]["pass"],$this->coins[1+$offset]["rpcsettings"]["host"],$this->coins[1+$offset]["rpcsettings"]["port"],$this->coins[1+$offset]["rpcsettings"]["walletpassphrase"],$this->coins[1+$offset]["rpcsettings"]["walletpassphrase_timeout"]);
			$w_coins_settings[$i]->set_rpc_settings_coin_3($this->coins[2+$offset]["rpcsettings"]["user"],$this->coins[2+$offset]["rpcsettings"]["pass"],$this->coins[2+$offset]["rpcsettings"]["host"],$this->coins[2+$offset]["rpcsettings"]["port"],$this->coins[2+$offset]["rpcsettings"]["walletpassphrase"],$this->coins[2+$offset]["rpcsettings"]["walletpassphrase_timeout"]);
		}
		
		$this->w_coins_settings=array($w_coins_settings);
		return array($w_coins_settings);
	}
	
	public function getFeeBeeAccount()
	{
		if($this->w_coins_settings==false)
		{
			$this->w_coins_settings=$this->get_last_w_coins_settings();
			$build = $this->count_coins_packages!=0;
		} else {
			$build = true;
		}
		if(!$build)
		{
			return false;
		}
		return $this->w_coins_settings[0]->coins["create_feebee_account"];
	}
	
	public function setFeeBeeAccount($set)
	{
		if($this->w_coins_settings==false)
		{
			$this->w_coins_settings=$this->get_last_w_coins_settings();
			$build = $this->count_coins_packages!=0;
		} else {
			$build = true;
		}
		if(!$build)
		{
			return false;
		}
		if($set!=true)
			$set=false;
		$this->w_coins_settings[0]->coins["create_feebee_account"]=$set;
	}
	
	public function get_last_w_coins_settings($startoffset = 0)
	{
		if($this->w_coins_settings==false) {
			$this->w_coins_settings=$this->get_w_coins_settings($startoffset);
		}
		return $this->w_coins_settings;
	}
	
	public static function get()
	{
		if(self::$SINGLETON==NULL)
			self::$SINGLETON = new my_all_coins();
		return self::$SINGLETON;
	}
}
?>