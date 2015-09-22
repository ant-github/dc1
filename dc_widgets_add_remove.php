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
        if(isset($_POST['widget_status']) && $_POST['widget_status'] != '' && isset($_POST['wishlist_item_id']) && $_POST['wishlist_item_id'] != ''){
		$widget_status = $_POST['widget_status'];
		$wishlist_item_id = $_POST['wishlist_item_id'];
		$user_id = $user->uid;
                if($widget_status == '1'){
$check_total_widgets = db_query("SELECT count(wid.field_my_widget_value_value) AS total FROM wishlist_item AS wish LEFT JOIN field_data_field_my_widget_value AS wid ON wid.entity_id = wish.wishlist_item_id WHERE wid.field_my_widget_value_value = 1 AND wish.uid=".$user->uid);
                    foreach($check_total_widgets AS $count_total_widgets){
                        $total = $count_total_widgets->total;
                    }
                if($total < 5){                        
                        $check_status = db_query("SELECT field_my_widget_value_value FROM field_data_field_my_widget_value WHERE entity_id=".$wishlist_item_id);
                        foreach($check_status AS $status){
                            $status_exist = $status->field_my_widget_value_value;
                        }
                       if(isset($status_exist)){
                           db_query("UPDATE field_data_field_my_widget_value SET field_my_widget_value_value=1 WHERE entity_id=".$wishlist_item_id);
                           db_query("UPDATE field_revision_field_my_widget_value SET field_my_widget_value_value=1 WHERE entity_id=".$wishlist_item_id);
                       }else{
                            db_query("INSERT INTO field_data_field_my_widget_value (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_my_widget_value_value) VALUES ('wishlist_item', 'wishlist_item', '0', '".$wishlist_item_id."', '".$wishlist_item_id."', 'und', '0', '1')");

                            db_query("INSERT INTO field_revision_field_my_widget_value (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_my_widget_value_value) VALUES ('wishlist_item', 'wishlist_item', '0', '".$wishlist_item_id."', '".$wishlist_item_id."', 'und', '0', '1')");                       
                       }                                       
                    echo "added";
                }else{
                   echo "nomore";  
                }
                
                }else if($widget_status == '0'){
                    db_query("UPDATE field_data_field_my_widget_value SET field_my_widget_value_value=0 WHERE entity_id=".$wishlist_item_id);
                    db_query("UPDATE field_revision_field_my_widget_value SET field_my_widget_value_value=0 WHERE entity_id=".$wishlist_item_id);
                    echo "removed";                    
                }else{
                    echo 0;
                }
	}else{
            echo 0;
        }

?>