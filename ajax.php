<?php
session_start();
error_reporting(0);
require_once'jsonRPCClient.php';
require_once'auth.php';
$ajax_id = security($_GET['id']);
if(!$user_session) {
   $do = "nothing";
} else {
   if($ajax_id=="pending-deposits") { require'ajax/pending-deposits.php'; }
   if($ajax_id=="balances") { require'ajax/balances.php'; }
}
if($ajax_id=="buyorders") { require'ajax/buyorders.php'; }
if($ajax_id=="sellorders") { require'ajax/sellorders.php'; }
if($ajax_id=="orderspast") { require'ajax/orderspast.php'; }
if($ajax_id=="orderscancel") { require'ajax/orderscancel.php'; }

if($ajax_id=="stats") { require'ajax/stats.php'; }
?>