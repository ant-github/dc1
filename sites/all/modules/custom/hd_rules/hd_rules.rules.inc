<?php
/**
 * Implements hook_rules_action_info()
 * Compare wishlist owner address to store address to implement the right
 * shipping service
 */

function hd_rules_rules_condition_info(){
  $conditions = $array = array(
    'hd_rules_check_wishlist_owner_address'=>array(
      'label' => t('Compare wishlist owner country address'),
      'group' => t('hd rules'),
      'parameter'=>array(
        'commerce_order' => array(
            'type' => 'commerce_order',
            'label' => t('Order')
        ),
      ),
     ),
    );
  return $conditions;
}

function hd_rules_check_wishlist_owner_address($commerce_order){
  //load line_tem from the order
  $line_item = commerce_line_item_load(
    $commerce_order->commerce_line_items[LANGUAGE_NONE]['0']['line_item_id']);
  //check if line item is a product
  if($line_item->type == 'product'){
    //load wishlist_owner
    $wish_list_owner = user_load(
      $line_item->field_wish_list_owner_ref[LANGUAGE_NONE]['0']['target_id']);
    //get wishlist_owner country
    $wish_list_owner_country =
      $wish_list_owner->field_delivery_address[LANGUAGE_NONE]['0']['country'];
    //****todo check store country
    if($wish_list_owner_country == 'GB'){
      return;
    }
  }
}

