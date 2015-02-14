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
  // curl "http://www.dc.local/api/rest/wishlist/add_product?uid=221&wishlist_id=46&product_url=http://shop.ee.co.uk/mobile-phones/pay-monthly/iphone-6-16gb-space-grey/details?WT.mc_id=ON_MEC_S_google_G_shoppingcampaigns&WT.srch=1" -X POST --header "Content-type: application/json" --header "Accept: application/json"

  // curl "http://www.dc.local/api/rest/wishlist/add_product?uid=221&wishlist_id=46&product_url=http://www.lovell-rugby.co.uk/Rugby-Shirts/Under-Armour/Wales-2013-or-15-Home-Replica-Rugby-Shirt-Red-or-White/size_XXL?utm_source=googleshopping&utm_medium=organic&gclid=CjwKEAiAy8ujBRCY6c-hveijhFASJAAcyGicbHWRz1PEnxcWqYnbkL-rxP58mVlXqvPIpODLAcQvQhoCTBTw_wcB" -X POST --header "Content-type: application/json" --header "Accept: application/json"
  $token = '3680574cf5ef2770f93937f4ad4350d8';
  $api_endpoint = 'http://api.diffbot.com/v3/product';
  $encoded_url = rawurlencode($product_url);
  $geturl = $api_endpoint.'?token='.$token.'&url='.$encoded_url;
  $opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"X-Forward-Accept-language: en-gb\r\n"
    )
  );
  $context = stream_context_create($opts);
  // Open the file using the HTTP headers set above
  $json = file_get_contents($geturl, false, $context);
  $product_data = json_decode($json, TRUE);

  // Let's check to see if there is a price available - if not, this won't work, so bail out.
  if(isset($product_data['objects'][0]['offerPrice'])){
    // Strip and build the title..
    $title_original = $product_data['objects'][0]['title'];
    $title_stripped = str_replace(' ', '_', strtolower($title_original)); // Replace spaces with underscores
    $title_stripped = preg_replace('/[^A-Za-z0-9\-_]/', '', $title_stripped); // Removes special chars (except underscores);

    // Build the sku..
    $sku = isset($product_data['objects'][0]['productId']) ? $product_data['objects'][0]['productId'].'_'.$title_stripped : $title_stripped;
    $product = commerce_product_load_by_sku($sku);

    if(empty($product)){
      // Build the product
      $product = commerce_product_new('product');
      $product->sku = $sku;
      $product->title = $product_data['objects'][0]['title'];
      $product->language = LANGUAGE_NONE;
      $product->uid = 1;

      // image stuff - check to see if it does exists..
      $i = 0;

      if(isset($product_data['objects'][0]['images'][$i])){
        // Check if the first image - is actually a valid image.  Sometimes with diffbot, it just give a plain url - no image..
        $product_url = $product_data['objects'][0]['images'][$i]['url'];
        $http_exists = strpos($product_url,'http:');
        //review this!!!
        if($http_exists===0){
          $image_data = file_get_contents($product_url);
        }else{
          $image_data = '';
        }
        if(!empty($image_data)){
          // Check size of the array - looks like we need another image..
          if(count($product_data['objects'][0]['images']) > 1){
            // Break when we have a valid image
            for ($i=1; $i <= count($product_data['objects'][0]['images']) ; $i++) {
              $image_data = getimagesize($product_data['objects'][0]['images'][$i]['url']);
              if($image_data) break;
            }
          }
        }
        // If there is actually an image available, proceed to add this to the product - else, add a default? Place holder?
        if($image_data){
          $img = file_get_contents($product_url);
          if(isset($image_data['mime'])){
            $img_type = explode('/', $image_data['mime']); // [1] will give us the mime type
            $img_name = $sku.'.'.$img_type[1];
          }else{
            $img_name = $sku.'jpg';
          }
          $file = file_save_data($img, 'public://' . $img_name, FILE_EXISTS_RENAME);
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
      }
      $price = $product_data['objects'][0]['offerPrice'];
      //price with currency codes in the end
      //ISO currency codes have 3 letters so we check for the last 3 characters
      $price_ending_currency_code = substr($price, -3);
      //strip currency code from string
      $price_stripped = _get_amount($price);
      //multiple the unit price
      $final_price = $price_stripped*100;

      //check wich currency and set its code by symbol
      //todo refactor this code!!!!
      if(!is_string($price_ending_currency_code)){
        $currency_unit =  mb_substr($price, 0, 1, "utf-8");
      }elseif (mb_substr($price, 0, 1, "utf-8")=='£'||mb_substr($price, 0, 3, "utf-8")=='GBP') {
        $currency_unit = '£';
      }elseif (mb_substr($price, 0, 1, "utf-8")=='$'||mb_substr($price, 0, 3, "utf-8")=='USD') {
        $currency_unit = '$';
      }elseif($price_ending_currency_code == '€'||mb_substr($price, 0, 3, "utf-8")=='EUR'){
        $currency_unit = 'EUR';
      }else{
        $currency_unit = $price_ending_currency_code;
      }
      switch ($currency_unit) {
        case '$':
          $code = 'USD';
        break;
        case '£':
          $code = 'GBP';
        break;
        case is_numeric($currency_unit);
          $code = 'EUR';
        break;
        default:
          $code = $currency_unit;
        break;
      }
      // Check the price is numeric - if not skip this for now? :/
      if(is_numeric($final_price)){
        $product->commerce_price[LANGUAGE_NONE][0] = array(
          'amount' => $final_price,
          'currency_code' => $code,
        );
      } // else - default price?
      $product->field_product_url[LANGUAGE_NONE][0]['value'] = $product_data['objects'][0]['pageUrl'];
      commerce_product_save($product);
    }

    // Load the users wishlist
    $wlw = entity_metadata_wrapper('wishlist', $wishlist_id);

    // Create the wishlist item.
    $values['user'] = $uid;
    $wishlist_item = entity_get_controller('wishlist_item')->create($values);

    $wiw = entity_metadata_wrapper('wishlist_item', $wishlist_item);
    $wiw->field_commerce_produc_ref = $product->product_id;
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

    //add site url
    $site_url=parse_url($product_url);
    $country_code = explode('/',$site_url['path']);
    $country_code = $country_code[1];
    //check country_code lengh to see if has a country in it. e.g gb
    if(strlen($country_code) == 2 ){
      $store_name = 'http://'.$site_url['host'].'/'.$country_code;
        }else{
          $store_name = 'http://'.$site_url['host'];
          }
    //check if store exists to get the id
    $query = new EntityFieldQuery();
    $query->entityCondition('entity_type', 'store')
    ->propertyCondition('name', $store_name, '=')
    ->addMetaData('account', user_load(1)); // Run the query as user 1.
    $result = $query->execute();
    //create a new store if it does not exist
    if(empty($result)){
      //create store entity and save its reference to the new $wishlist_item
      $store = entity_create('store', array('type' =>'store'));
      $store->name = $store_name;
      $store->field_store_url = array(LANGUAGE_NONE => array(0 => array('value' => $store_name )));
      //load wishlist user object to get its country
      $wishlist_user = user_load($uid);
      $wishlist_user_country =
        $wishlist_user->field_delivery_address[LANGUAGE_NONE][0]['country'];
      $store->field_store_country = array(LANGUAGE_NONE => array(0 => array('value' => $wishlist_user_country)));
      //save store
      $store->save();
      //save store ref id into the wishlist item
      $wiw->field_store_ref = $store->store_id;
      $wiw->save();
    }else{
      //set store id to existing store
      $store_id = key($result['store']);
      $wiw->field_store_ref = $store_id;
      $wiw->save();
    }
  return $product_data;
  } else {
    return FALSE;
  }
}
//clean the product price removing curruncy symbols and separators
function _get_amount($price){
    $cleanString = preg_replace('/([^0-9\.,])/i', '', $price);
    $onlyNumbersString = preg_replace('/([^0-9])/i', '', $price);

    $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

    $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
    $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

    return (float) str_replace(',', '.', $removedThousendSeparator);
}
