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
  // curl "http://www.dc.local/api/rest/wishlist/add_product?uid=221&wishlist_id=46&product_url=http://www.currys.co.uk/gbuk/household-appliances/small-kitchen-appliances/toasters/russell-hobbs-heritage-18369-4-slice-toaster-cream-13118697-pdt.html#cat-0" -X POST --header "Content-type: application/json" --header "Accept: application/json"

  $token = 'ef3a18acadec1140bcca594c64361946';
  $api_endpoint = 'http://api.diffbot.com/v3/product';
  $geturl = $api_endpoint.'?token='.$token.'&url='.$product_url.'&fields=sku';
  $json = file_get_contents($geturl);
  $product_data = json_decode($json, TRUE);
  $title_original = $product_data['objects'][0]['title'];
  $title_stripped = str_replace(' ', '_', strtolower($title_original)); // Replace spaces with underscores
  $title_stripped = preg_replace('/[^A-Za-z0-9\-]_/', '', $title_stripped); // Removes special chars (except underscores);

  $sku = $product_data['objects'][0]['productId'].'_'.$title_stripped;
  $sku_exists = commerce_product_load_by_sku($sku);
  if(empty($sku_exists)){
    $price_original = $product_data['objects'][0]['offerPrice'];
    $price_stripped = preg_replace('/[^0-9\-.]/', '', $price_original); // Removes currency and * 100.
    // Build the product
    $product = commerce_product_new('product');
    $product->sku = $sku;
    $product->title = $product_data['objects'][0]['title'];
    $product->language = LANGUAGE_NONE;
    $product->uid = 1;

    // system_retrieve_file($product_data['objects'][0]['images'][0]['url'],'public://diffbot-images/'.$product_data['objects'][0]['productId'].'_'.$title_stripped.'.jpg');

    // Save the image - maybe revise this at some point, as we're hardcoding .jpg
    if(isset($product_data['objects'][0]['images'][0])){
      $filename = $product_data['objects'][0]['productId'].'_'.$title_stripped.'.jpg'; // sku . jpg
      $image = file_get_contents($product_data['objects'][0]['images'][0]['url']);
      $file = file_save_data($image, 'public://' . $filename, FILE_EXISTS_RENAME);
      $product->field_product_image[LANGUAGE_NONE][0] = array(
        'fid' => $file->fid,
        'filename' => $file->filename,
        'filemime' => $file->filemime,
        'uid' => 1,
        'uri' => $file->uri,
        'status' => 1,
        'display' => 1
      );
    }

    // Check the price is numeric - if not skip this for now? :/
    if(is_numeric($price_stripped)){
      $product->commerce_price[LANGUAGE_NONE][0] = array(
        'amount' => (int) $price_stripped * 100,
        'currency_code' => "GBP",
      );
    } // else - default price?

    $product->field_product_url[LANGUAGE_NONE][0]['value'] = $product_data['objects'][0]['pageUrl'];
    commerce_product_save($product);

    // ------ Now set up the users wishlists!
    $wlw = entity_metadata_wrapper('wishlist', $wishlist_id);

    // Create the wishlist item.
    $values['user'] = $uid;
    $wishlist_item = entity_get_controller('wishlist_item')->create($values);

    $wiw = entity_metadata_wrapper('wishlist_item', $wishlist_item);
    $wiw->field_commerce_produc_ref = $product->product_id;
    $wiw->field_item_code = hd_wishlist_item_generate_item_code();
    $wiw->field_status = 'available';
    $wiw->save();

    // Add our wishlist item reference to the existing references.
    $current_items = $wlw->field_wishlist_items->value();
    if (!$current_items) {
      $current_items = array();
    }
    $current_items[] = $wiw->value();
    $wlw->field_wishlist_items = $current_items;
    $wlw->save();
  }
  return $product_data;
}
