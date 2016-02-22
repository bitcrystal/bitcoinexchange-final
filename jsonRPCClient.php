<?php
/*
					COPYRIGHT

Copyright 2007 Sergio Vaccaro <sergio@inservibile.org>

This file is part of JSON-RPC PHP.

JSON-RPC PHP is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

JSON-RPC PHP is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with JSON-RPC PHP; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The object of this class are generic jsonRPC 1.0 clients
 * http://json-rpc.org/wiki/specification
 *
 * @author sergio <jsonrpcphp@inservibile.org>
 */
class jsonRPCClient {
	
	/**
	 * Debug state
	 *
	 * @var boolean
	 */
	private $debug;
	
	/**
	 * The server URL
	 *
	 * @var string
	 */
	private $url;
	/**
	 * The request id
	 *
	 * @var integer
	 */
	private $id;

	private $saves_array;
	/**
	 * If true, notifications are performed instead of requests
	 *
	 * @var boolean
	 */
	private $notification = false;

	private $use_multisignature_support;

	private $count_of_used_addresses_for_multisignature_support;



	
	/**
	 * Takes the connection parameters
	 *
	 * @param string $url
	 * @param boolean $debug
	 */
	public function __construct($url,$use_multisignature_support = false, $count_of_used_addresses_for_multisignature_support = 0, $debug = false) {
		$this->saves_array = array();

		$this->use_multisignature_support = $use_multisignature_support;

		$this->count_of_used_addresses_for_multisignature_support = $count_of_used_addresses_for_multisignature_support;

		// server URL
		$this->url = $url;
		// proxy
		empty($proxy) ? $this->proxy = '' : $this->proxy = $proxy;
		// debug state
		empty($debug) ? $this->debug = false : $this->debug = true;
		// message id
		$this->id = 1;
	}
	
	/**
	 * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
	 *
	 * @param boolean $notification
	 */
	public function setRPCNotification($notification) {
		empty($notification) ?
							$this->notification = false
							:
							$this->notification = true;
	}
	
	/**
	 * Performs a jsonRCP request and gets the results as an array
	 *
	 * @param string $method
	 * @param array $params
	 * @return array
	 */

	private function getmaccountaddress_handler($method,$params)
	{
		$method = 'getaccountaddress';
                $account_address = $this->__call($method,$params);
                $set = $this->saves_array[$account_address];
		$nmrequired = $this->count_of_used_addresses_for_multisignature_support;
		if($set != '')
		{
			return $set;
		} else {
			$param_value = '';
			$addresses = '';
			$addresses_length = '';
			$nrequired = $nmrequired;
			$allgood = false;
			$temp = '';
			$npubkeys = '';
			try
			{
				if(is_array($params)) {
					$params = array_values($params);
					if(count($params) > 0)
					{
						$param_value = $params[0];
					}
				}
				if($param_value!='')
				{
					$addresses = $this->getaddressesbyaccount($param_value);
				} else {
					$set = $account_address;
					$this->saves_array[$account_address] = $set;
					return $set;
				}
				if(is_array($addresses))
				{
					$addresses_length = count($addresses);
					$npubkeys = array();
					for($i = 0; $i < $addresses_length; $i++)
					{
						$temp = $this->validate_address($addresses[$i]);
						if($temp['isscript']==true)
						{
							$addresses[$i]=$this->getnewaddress();
							$temp = $this->validate_address($addresses[$i]);
							$npubkeys[$i] = $temp['pubkey'];
							$this->setaccount($addresses[$i],$param_value);
						} else {
							$npubkeys[$i] = $temp['pubkey'];
						}
					}
				  } else {
					$npubkeys = array();
					$addresses = array();
					$addresses_length = 0;
				  }
				  for($i = $addresses_length; $i < $nrequired; $i++)
                                  {
                                                $temp = $this->getnewaddress();
                                                if($temp=='')
                                                {
                                                        $i--;
                                                        continue;
                                                }
                                                $this->setaccount($temp,$param_value);
                                                $addresses[$i]=$temp;
                                                $npubkeys[$i] = $this->validateaddress($temp);
                                                $npubkeys[$i] = $npubkeys[$i]['pubkey'];
                   				$temp = '';

				   }
				   $addresselength = count($addresses);
				   if($addresses_length>=$nrequired)
				   {
					$allgood = true;
				   } else {
					$allgood = false;
				   }
			} catch (Exception $ex) {
				$allgood = false;
			}
		}
		if($allgood==true)
		{
			$pubkeys=$npubkeys;
			$cp = '';
			$nrequired = $nmrequired;
			try {
				$maddress = $this->createmultisig($nrequired,$pubkeys);
				$mreedem = $maddress['redeemScript'];
				$maddress = $maddress['address'];
				$maddress2 = $this->addmultisigaddress($nrequired,$pubkeys,$param_value);
				if($maddress==$maddress2)
				{
					$set = $maddress;
				} else {
					$set = $account_address;
				}
				$this->saves_array[$account_address]=$set;
				echo $set;
			} catch (Exception $ex) {
				echo $ex;
				$set = $account_address;
				$this->saves_array[$account_address]=$set;
			}
		} else {
			$params_count = count($params);
			$account = '';
			$nrequired = $nmrequired;
			if($params_count>0)
			{
				$params = array_values($params);
				$account = $params[0];
			}
			try
			{
				$pubkey = $this->validateaddress($account_address);
				$pubkey = $pubkey['pubkey'];
				$multisigarg = array();
				for($i = 0; $i < $nrequired; $i++)
				{
					$multisigarg[$i]=$pubkey;
				}
				$maddress = $this->createmultisig($nrequired,$multisigarg);
				$mreedem = $maddress['redeemScript'];
				$maddress = $maddress['address'];
				$maddress2 = $this->addmultisigaddress($nrequired,$multisigarg,$account);
				if($maddress==$maddress2)
				{
					$set = $maddress;
				} else {
					$set = $account_address;
				}
				$this->saves_array[$account_address]=$set;
			} catch (Exception $ex) {
				$set = $account_address;
				$this->saves_array[$account_address]=$set;
			}
		}
		return $set;
	}

	public function __call($method,$params) {
		if($method == 'getmaccountaddress')
                {
			if($this->use_multisignature_support)
			{
                        	return $this->getmaccountaddress_handler($method,$params);
			} else {
				$method = 'getaccountaddress';
			}
                }

		// check
		if (!is_scalar($method)) {
			throw new Exception('Method name has no scalar value');
		}
		// check
		if (is_array($params)) {
			// no keys
			$params = array_values($params);
		} else {
			throw new Exception('Params must be given as array');
		}
		

		// sets notification or request task
		if ($this->notification) {
			$currentId = NULL;
		} else {
			$currentId = $this->id;
		}
		
		// prepares the request
		$request = array(
						'method' => $method,
						'params' => $params,
						'id' => $currentId
						);
		$request = json_encode($request);
		// echo "<br>".$request."<br>";
		$this->debug && $this->debug.='***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";
		
		// performs the HTTP POST
		$opts = array ('http' => array (
							'method'  => 'POST',
							'header'  => 'Content-type: application/json',
							'content' => $request
							));
		$context  = stream_context_create($opts);
		if ($fp = fopen($this->url, 'r', false, $context)) {
			$response = '';
			while($row = fgets($fp)) {
				$response.= trim($row)."\n";
			}
			$this->debug && $this->debug.='***** Server response *****'."\n".$response.'***** End of server response *****'."\n";
			$response = json_decode($response,true);
		} else {
			throw new Exception('Unable to connect to '.$this->url);
		}
		
		// debug output
		if ($this->debug) {
			echo nl2br($debug);
		}
		
		// final checks and return
		if (!$this->notification) {
			// check
			if ($response['id'] != $currentId) {
				throw new Exception('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')');
			}
			if (!is_null($response['error'])) {
				throw new Exception('Request error: '.$response['error']);
			}
			
			return $response['result'];
			
		} else {
			return true;
		}
	}
}
?>
