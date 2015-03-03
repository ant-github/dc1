<?php
$currdir=getcwd();
chdir('/opt/lampp/htdocs/delivery_code');
define('DRUPAL_ROOT', '/opt/lampp/htdocs/delivery_code');
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';
require_once DRUPAL_ROOT . '/includes/unicode.inc';
require_once DRUPAL_ROOT . '/includes/file.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

	if(isset($_POST['option']) && $_POST['option'] != ''){
		$username = $_POST['name'];
		$currenyIn = $_POST['option'];
		$select_user_id = db_query("SELECT uid FROM users WHERE name ='".$username."'");
		foreach($select_user_id AS $res_id){
			$user_id = $res_id->uid;
		}
				
		$select_user_balace = db_query('SELECT field_gift_balance_usd_value FROM field_data_field_gift_balance_usd WHERE entity_id ='.$user_id);
		foreach($select_user_balace AS $res_balance){
			$usd_balance = $res_balance->field_gift_balance_usd_value;
		}
		if($currenyIn == 'usd'){
			
			echo "$".$usd_balance;
		
		}else if($currenyIn == 'gbp'){
			
			$usd_vs_gbp = db_query('SELECT field__1_gbp_value FROM field_data_field__1_gbp WHERE entity_id =58');
			foreach($usd_vs_gbp AS $res_usd_vs_gbp){
				$gbp_rate = $res_usd_vs_gbp->field__1_gbp_value;
			}	
			$gbp = $usd_balance * $gbp_rate;
			echo "£".$gbp;
			
		}else if($currenyIn == 'eur'){

			$usd_vs_eur = db_query('SELECT field__1_eur_value FROM field_data_field__1_eur WHERE entity_id =58');
			foreach($usd_vs_eur AS $res_usd_vs_eur){
				$eur_rate = $res_usd_vs_eur->field__1_eur_value;
			}	
			$eur = $usd_balance * $eur_rate;
			echo $eur."€";
			
		}
	}

?>