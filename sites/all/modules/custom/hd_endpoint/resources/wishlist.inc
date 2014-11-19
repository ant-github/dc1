<?php


// /**
//  * Services callback to create a user wishlist.
//  */
// function _wishlist_resource_create($wishlist_name) {
//   global $user;
//   $values['user'] = $user->uid;
//   $entity = entity_get_controller('wishlist')->create($values);
//   $entity->name = $wishlist_name;
//   hd_wishlist_entity_save($entity);

//   return $entity;
// }

/**
 * Services callback to retrieve a users wishlists.
 */
function _wishlist_resource_view($uid) {
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'wishlist')
    ->propertyCondition('uid', $uid);
  $result = $query->execute();
  if (isset($result['wishlist'])) {
    $wishlist_ids = array_keys($result['wishlist']);
    $wishlist_items = entity_load('wishlist', $wishlist_ids);
  }

  return $wishlist_items;
}

/**
 * Services callback to add a product to a users wishlist.
 * @todo Validate access before?
 */
function _wishlist_resource_add_product($uid,$wishlist_id,$product_url) {
  //http://dc.local.com/api/rest/wishlist/add_product?uid=25&product_url=http://www.com.com&wishlist_id=888
  $token = 'ef3a18acadec1140bcca594c64361946';
  $api_endpoint = 'http://api.diffbot.com/v3/product';
  $geturl = $api_endpoint.'?token='.$token.'&url='.$product_url;
  $json = file_get_contents($geturl);
  $product_data = json_decode($json, TRUE);

//return $uid;
//return $wishlist_id;
return $product_data;

}
