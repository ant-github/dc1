<?php

define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';
require_once DRUPAL_ROOT . '/includes/unicode.inc';
require_once DRUPAL_ROOT . '/includes/file.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $user;
	if(isset($_POST['option']) && $_POST['option'] != ''){
		$currenyIn = $_POST['option'];
		$user_id = $user->uid;	
		$select_user_balace = db_query('SELECT field_gift_balance_usd_value FROM field_data_field_gift_balance_usd WHERE entity_id ='.$user_id);
		foreach($select_user_balace AS $res_balance){
			$usd_balance = $res_balance->field_gift_balance_usd_value;
		}
		
		$select_currency_rates_nid = db_query("SELECT nid FROM node WHERE type ='currency_exchange_rates_for_gift' ORDER BY nid ASC LIMIT 1");
		foreach($select_currency_rates_nid AS $res_currency_rates){
			$currency_rates_nid = $res_currency_rates->nid;
		} 
		if($currenyIn == 'usd'){
			
			echo "$". $usd_balance;
		
		}else if($currenyIn == 'gbp'){
			
			$usd_vs_gbp = db_query("SELECT field__1_gbp_value FROM field_data_field__1_gbp WHERE entity_id ='".$currency_rates_nid."'");
			foreach($usd_vs_gbp AS $res_usd_vs_gbp){
				$gbp_rate = $res_usd_vs_gbp->field__1_gbp_value;
			}	
			print $gbp = $usd_balance * $gbp_rate;
			echo "£".$gbp;
			
		}else if($currenyIn == 'eur'){

			$usd_vs_eur = db_query("SELECT field__1_eur_value FROM field_data_field__1_eur WHERE entity_id ='".$currency_rates_nid."'");
			foreach($usd_vs_eur AS $res_usd_vs_eur){
				$eur_rate = $res_usd_vs_eur->field__1_eur_value;
			}	
		print	$eur = $usd_balance * $eur_rate;
			echo $eur."€";
			
		}
	}
   
?>