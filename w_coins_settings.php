<?php
class w_coins_settings
{
	public $coins;
	public function __construct() {
		$coins = array();
		$this->init_create_feebee_account();
		$this->init_names();
		$this->init_prefixes();
		$this->init_rpc_settings_coin_1();
		$this->init_rpc_settings_coin_2();
		$this->init_rpc_settings_coin_3();
		$this->init_fees();
		$this->init_feebees(); // IMPORTANT, this are the accounts that fees will be paid to make sure to register it
		$this->init_buy_fees();
		$this->init_sell_fees();
		$this->set_buy_fees(false, false, false);
		$this->set_sell_fees(false, false, false);
	}
	
	private function init_create_feebee_account()
	{
		$create_feebee_account=false;
		$this->coins["create_feebee_account"]=$create_feebee_account;
	}
	
	private function init_names()
	{
		$coin_name_1="Bitcoin";
		$coin_name_2="Bitcrystal";
		$coin_name_3="Bitcrystalx";
		$this->coins["coin_name_1"] = $coin_name_1;
		$this->coins["coin_name_2"] = $coin_name_2;
		$this->coins["coin_name_3"] = $coin_name_3;
		$this->coins[$coin_name_1] = array();
		$this->coins[$coin_name_2] = array();
		$this->coins[$coin_name_3] = array();
	}
	
	private function init_prefixes()
	{
		$coin_prefix_1="BTC";
		$coin_prefix_2="BTCRY";
		$coin_prefix_3="BTCRYX";
		$this->coins["coin_prefix_1"] = $coin_prefix_1;
		$this->coins["coin_prefix_2"] = $coin_prefix_2;
		$this->coins["coin_prefix_3"] = $coin_prefix_3;
	}
	
	private function init_fees()
	{
		$coin_fee_1=0.01;
		$coin_fee_2=0.01;
		$coin_fee_3=0.01;
		$this->coins["coin_fee_1"] = $coin_fee_1;
		$this->coins["coin_fee_2"] = $coin_fee_2;
		$this->coins["coin_fee_3"] = $coin_fee_3;
	}
	
	private function init_feebees()
	{
		$coin_feebee_1=$this->coins["coin_name_1"];
		$coin_feebee_2=$this->coins["coin_name_2"];
		$coin_feebee_3=$this->coins["coin_name_3"];
		$this->coins["coin_feebee_1"] = $coin_feebee_1;
		$this->coins["coin_feebee_2"] = $coin_feebee_2;
		$this->coins["coin_feebee_3"] = $coin_feebee_3;
	}
	
	private function init_buy_fees()
	{
		$coin_fee_1=true;
		$coin_fee_2=true;
		$coin_fee_3=true;
		$this->coins["coin_buy_fee_1"] = $coin_fee_1;
		$this->coins["coin_buy_fee_2"] = $coin_fee_2;
		$this->coins["coin_buy_fee_3"] = $coin_fee_3;
	}
	
	private function init_sell_fees()
	{
		$coin_fee_1=true;
		$coin_fee_2=true;
		$coin_fee_3=true;
		$this->coins["coin_sell_fee_1"] = $coin_fee_1;
		$this->coins["coin_sell_fee_2"] = $coin_fee_2;
		$this->coins["coin_sell_fee_3"] = $coin_fee_3;
	}
	
	public function set_create_feebee_account($create_feebee_account)
	{
		$this->coins["create_feebee_account"]=$create_feebee_account;
	}
	
	public function set_names($coin_name_1, $coin_name_2, $coin_name_3)
	{
		$this->coins["coin_name_1"] = $coin_name_1;
		$this->coins["coin_name_2"] = $coin_name_2;
		$this->coins["coin_name_3"] = $coin_name_3;
	}
	
	public function set_prefixes($coin_prefix_1, $coin_prefix_2, $coin_prefix_3)
	{
		$this->coins["coin_prefix_1"] = $coin_prefix_1;
		$this->coins["coin_prefix_2"] = $coin_prefix_2;
		$this->coins["coin_prefix_3"] = $coin_prefix_3;
	}
	
	public function set_fees($coin_fee_1, $coin_fee_2, $coin_fee_3)
	{
		$this->coins["coin_fee_1"] = $coin_fee_1;
		$this->coins["coin_fee_2"] = $coin_fee_2;
		$this->coins["coin_fee_3"] = $coin_fee_3;
	}
	
	public function set_feebees($coin_feebee_1, $coin_feebee_2, $coin_feebee_3)
	{
		$this->coins["coin_feebee_1"] = $coin_feebee_1;
		$this->coins["coin_feebee_2"] = $coin_feebee_2;
		$this->coins["coin_feebee_3"] = $coin_feebee_3;
	}
	
	public function set_buy_fees($coin_fee_1, $coin_fee_2, $coin_fee_3)
	{
		$this->coins["coin_buy_fee_1"] = $coin_fee_1;
		$this->coins["coin_buy_fee_2"] = $coin_fee_2;
		$this->coins["coin_buy_fee_3"] = $coin_fee_3;
	}
	
	public function set_sell_fees($coin_fee_1, $coin_fee_2, $coin_fee_3)
	{
		$this->coins["coin_sell_fee_1"] = $coin_fee_1;
		$this->coins["coin_sell_fee_2"] = $coin_fee_2;
		$this->coins["coin_sell_fee_3"] = $coin_fee_3;
	}
	
	private function init_rpc_settings_coin_1()
	{
		$coins_name = $this->coins["coin_name_1"];
		$this->coins[$coins_name]["rpcsettings"] = array();
		
		$this->coins[$coins_name]["rpcsettings"]["user"]="bitcoinrpc";
		$this->coins[$coins_name]["rpcsettings"]["pass"]="fickdiehenneextended";
		$this->coins[$coins_name]["rpcsettings"]["host"]="127.0.0.1";
		$this->coins[$coins_name]["rpcsettings"]["port"]="8332";
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase"]="";
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase_timeout"]=99999999;
	}
	
	private function init_rpc_settings_coin_2()
	{
		$coins_name = $this->coins["coin_name_2"];
		$this->coins[$coins_name]["rpcsettings"] = array();
		
		$this->coins[$coins_name]["rpcsettings"]["user"]="WernerChainer";
		$this->coins[$coins_name]["rpcsettings"]["pass"]="fickdiehenne";
		$this->coins[$coins_name]["rpcsettings"]["host"]="127.0.0.1";
		$this->coins[$coins_name]["rpcsettings"]["port"]="19332";
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase"]="";
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase_timeout"]=99999999;
	}
	
	private function init_rpc_settings_coin_3()
	{
		$coins_name = $this->coins["coin_name_3"];
		$this->coins[$coins_name]["rpcsettings"] = array();
		
		$this->coins[$coins_name]["rpcsettings"]["user"]="WernerChainer";
		$this->coins[$coins_name]["rpcsettings"]["pass"]="fickdiehenneextended";
		$this->coins[$coins_name]["rpcsettings"]["host"]="127.0.0.1";
		$this->coins[$coins_name]["rpcsettings"]["port"]="19333";
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase"]="";
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase_timeout"]=99999999;
	}
	
	public function set_rpc_settings_coin_1($rpc_user, $rpc_pass, $rpc_host, $rpc_port, $rpc_walletpassphrase="", $rpc_walletpassphrase_timeout=99999999)
	{
		$coins_name = $this->coins["coin_name_1"];
		
		$this->coins[$coins_name]["rpcsettings"]["user"]=$rpc_user;
		$this->coins[$coins_name]["rpcsettings"]["pass"]=$rpc_pass;
		$this->coins[$coins_name]["rpcsettings"]["host"]=$rpc_host;
		$this->coins[$coins_name]["rpcsettings"]["port"]=$rpc_port;
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase"]=$rpc_walletpassphrase;
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase_timeout"]=$rpc_walletpassphrase_timeout;
	}
	
	public function set_rpc_settings_coin_2($rpc_user, $rpc_pass, $rpc_host, $rpc_port, $rpc_walletpassphrase="", $rpc_walletpassphrase_timeout=99999999)
	{
		$coins_name = $this->coins["coin_name_2"];
		
		$this->coins[$coins_name]["rpcsettings"]["user"]=$rpc_user;
		$this->coins[$coins_name]["rpcsettings"]["pass"]=$rpc_pass;
		$this->coins[$coins_name]["rpcsettings"]["host"]=$rpc_host;
		$this->coins[$coins_name]["rpcsettings"]["port"]=$rpc_port;
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase"]=$rpc_walletpassphrase;
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase_timeout"]=$rpc_walletpassphrase_timeout;
	}
	
	public function set_rpc_settings_coin_3($rpc_user, $rpc_pass, $rpc_host, $rpc_port, $rpc_walletpassphrase="", $rpc_walletpassphrase_timeout=99999999)
	{
		$coins_name = $this->coins["coin_name_3"];
		
		$this->coins[$coins_name]["rpcsettings"]["user"]=$rpc_user;
		$this->coins[$coins_name]["rpcsettings"]["pass"]=$rpc_pass;
		$this->coins[$coins_name]["rpcsettings"]["host"]=$rpc_host;
		$this->coins[$coins_name]["rpcsettings"]["port"]=$rpc_port;
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase"]=$rpc_walletpassphrase;
		$this->coins[$coins_name]["rpcsettings"]["walletpassphrase_timeout"]=$rpc_walletpassphrase_timeout;
	}
}


?>