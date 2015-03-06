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
		if($currenyIn == 'usd'){
			
			echo "$".number_format($usd_balance, 2);
		
		}else if($currenyIn == 'gbp'){
			
			$usd_vs_gbp = db_query('SELECT field__1_gbp_value FROM field_data_field__1_gbp WHERE entity_id =19');
			foreach($usd_vs_gbp AS $res_usd_vs_gbp){
				$gbp_rate = $res_usd_vs_gbp->field__1_gbp_value;
			}	
			$gbp = number_format(($usd_balance * $gbp_rate), 2);
			echo "£".$gbp;
			
		}else if($currenyIn == 'eur'){

			$usd_vs_eur = db_query('SELECT field__1_eur_value FROM field_data_field__1_eur WHERE entity_id =19');
			foreach($usd_vs_eur AS $res_usd_vs_eur){
				$eur_rate = $res_usd_vs_eur->field__1_eur_value;
			}	
			$eur = number_format(($usd_balance * $eur_rate), 2);
			echo $eur."€";
			
		}
	}

?>