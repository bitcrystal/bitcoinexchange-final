<?php
class my_coin
{
	public $coin;
	public function __construct() {
		$coin = array();
		$this->init_name();
		$this->init_prefix();
		$this->init_rpc_settings_coin();
		$this->init_fee();
		$this->init_feebee(); // IMPORTANT, this are the accounts that fees will be paid to make sure to register it
		$this->init_buy_fee();
		$this->init_sell_fee();
		$this->set_buy_fee(false);
		$this->set_sell_fee(false);
	}
	
	private function init_name()
	{
		$coin_name = "Bitcoin";
		$this->coins["name"] = $coin_name;
	}
	
	private function init_prefix()
	{
		$coin_prefix="BTC";
		$this->coin["prefix"]=$coin_prefix;
	}
	
	private function init_fee()
	{
		$coin_fee = 0.01;
		$this->coin["fee"] = $coin_fee;
	}
	
	private function init_feebee()
	{
		$coin_feebee=$this->coin["name"];
		$this->coin["feebee"] = $coin_feebee;
	}
	
	private function init_buy_fee()
	{
		$coin_fee=true;
		$this->coin["buy_fee"] = $coin_fee;
	}
	
	private function init_sell_fee()
	{
		$coin_fee=true;
		$this->coin["sell_fee"] = $coin_fee;
	}
	
	public function set_name($coin_name)
	{
		$this->coin["name"] = $coin_name;
	}
	
	public function set_prefix($coin_prefix)
	{
		$this->coin["prefix"]=$coin_prefix;
	}
	
	public function set_fee($coin_fee)
	{
		$this->coin["fee"] = $coin_fee;
	}
	
	public function set_feebee($coin_feebee)
	{
		$this->coin["feebee"] = $coin_feebee;
	}
	
	public function set_buy_fee($coin_fee)
	{
		$this->coin["buy_fee"] = $coin_fee;
	}
	
	public function set_sell_fee($coin_fee)
	{
		$this->coin["sell_fee"] = $coin_fee;
	}
	
	private function init_rpc_settings_coin()
	{
		$this->coin["rpcsettings"] = array();
		
		$this->coin["rpcsettings"]["user"]="bitcoinrpc";
		$this->coin["rpcsettings"]["pass"]="fickdiehenneextended";
		$this->coin["rpcsettings"]["host"]="127.0.0.1";
		$this->coin["rpcsettings"]["port"]="8332";
		$this->coin["rpcsettings"]["walletpassphrase"]="";
		$this->coin["rpcsettings"]["walletpassphrase_timeout"]=99999999;
	}
	
	public function set_rpc_settings_coin($rpc_user, $rpc_pass, $rpc_host, $rpc_port, $rpc_walletpassphrase="", $rpc_walletpassphrase_timeout=99999999)
	{
		$this->coin["rpcsettings"]["user"]=$rpc_user;
		$this->coin["rpcsettings"]["pass"]=$rpc_pass;
		$this->coin["rpcsettings"]["host"]=$rpc_host;
		$this->coin["rpcsettings"]["port"]=$rpc_port;
		$this->coin["rpcsettings"]["walletpassphrase"]=$rpc_walletpassphrase;
		$this->coin["rpcsettings"]["walletpassphrase_timeout"]=$rpc_walletpassphrase_timeout;
	}
	
	public function getName()
	{
		return $this->coin["name"];
	}
	
	public function getPrefix()
	{
		return $this->coin["prefix"];
	}
	
	public function getFee()
	{
		return $this->coin["fee"];
	}
	
	public function getFeeBee()
	{
		return $this->coin["feebee"];
	}
	
	public function getBuyFee()
	{
		return $this->coin["buy_fee"];
	}
	
	public function getSellFee()
	{
		return $this->coin["sell_fee"];
	}
	
	public function getRpcUser()
	{
		return $this->coin["rpcsettings"]["user"];
	}
	
	public function getRpcPass()
	{
		return $this->coin["rpcsettings"]["pass"];
	}
	
	public function getRpcHost()
	{
		return $this->coin["rpcsettings"]["host"];
	}
	
	public function getRpcPort()
	{
		return $this->coin["rpcsettings"]["port"];
	}
	
	public function getRpcWalletpassphrase()
	{
		return $this->coin["rpcsettings"]["walletpassphrase"];
	}
	
	public function getRpcWalletpassphraseTimeout()
	{
		return $this->coin["rpcsettings"]["walletpassphrase_timeout"];
	}
}


?>