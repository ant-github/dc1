<?php

// drupal
//define('DRUPAL_ROOT', '/Users/reecemarsland/Projects/sponsume/www_migrate');
define('DRUPAL_ROOT', getcwd());
//define('DRUPAL_ROOT', '/web/ubuntu/public_html/mydex.zoocha.com/public');
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

function fix_broken_entity(){
  $wiw = entity_metadata_wrapper('wishlist_item', 180);
  $wiw->field_commerce_produc_ref = 8;
  $wiw->field_item_code = hd_wishlist_item_generate_item_code();
  $wiw->field_status = 'available';
  $wiw->save();
}

function create_product_endpoint(){
  $json = '{"request":{"pageUrl":"http://deals.ebay.com/5001687323_Lenovo_ThinkServer_TS140_Tower_Server_System_Intel_Xeon_E3_1225_v3?_trksid=p2050601.m1256","api":"product","version":3,"fields":"sku"},"objects":[{"text":"7 pc Outdoor Patio PE Rattan Wicker Sofa Sectional Furnit...","pageUrl":"http://deals.ebay.com/5001687323_Lenovo_ThinkServer_TS140_Tower_Server_System_Intel_Xeon_E3_1225_v3?_trksid=p2050601.m1256","humanLanguage":"en","type":"product","sku":"RB2132","productId":"RB2132","title":"Lenovo ThinkServer TS140 Tower Server System Intel Xeon E3-1225 v3","diffbotUri":"product|3|1504844218","offerPrice":"$319.99","brand":"Lenovo","images":[],"regularPrice":"$599.99","availability":true}]}';

  $product_data = json_decode($json, TRUE);
  dpm($product_data);

  // Strip and build the title..
  $title_original = $product_data['objects'][0]['title'];
  $title_stripped = str_replace(' ', '_', strtolower($title_original)); // Replace spaces with underscores
  $title_stripped = preg_replace('/[^A-Za-z0-9\-_]/', '', $title_stripped); // Removes special chars (except underscores);

  // Build the sku..
  $sku = isset($product_data['objects'][0]['productId']) ? $product_data['objects'][0]['productId'].'_'.$title_stripped : $title_stripped;
  $sku = $sku.rand();
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
      $image_data = getimagesize($product_data['objects'][0]['images'][$i]['url']);
      if(!$image_data){
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
        $img = file_get_contents($product_data['objects'][0]['images'][$i]['url']);
        $img_type = explode('/', $image_data['mime']); // [1] will give us the mime type
        $img_name = $sku.'.'.$img_type[1];
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
    $price_original = $product_data['objects'][0]['offerPrice'];
    $price_stripped = preg_replace('/[^0-9\-.]/', '', $price_original); // Removes currency and * 100.
    $currency_unit = substr($price_original, 0, 1);
    switch ($currency_unit) {
      case '$':
        $code = 'USD';
      break;
      case '£':
        $code = 'GBP';
      break;
      default:
        $code = 'EUR';
      break;
    }
    // Check the price is numeric - if not skip this for now? :/
    if(is_numeric($price_stripped)){
      $product->commerce_price[LANGUAGE_NONE][0] = array(
        'amount' => (int) $price_stripped * 100,
        'currency_code' => $code,
      );
    } // else - default price?

    $product->field_product_url[LANGUAGE_NONE][0]['value'] = $product_data['objects'][0]['pageUrl'];
    commerce_product_save($product);
    dpm($product,'product');
  }

  dpm($product);

  // Now set up the users wishlists!
  $user_id = 221;
  $wishlist_id = 46;

  // // Load the users wishlist
  $wlw = entity_metadata_wrapper('wishlist', $wishlist_id);

  // Create the wishlist item.
  $values['user'] = $user_id;
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

function address(){
  require_once DRUPAL_ROOT . '/sites/all/modules/contrib/addressfield/plugins/format/address.inc';
  $format = array();
  $country = 'GB';
  $address = array('country' => $country);
  addressfield_format_address_generate($format, $address, array());

  //dpm($format, 'format');

  $keys = array_keys($format['street_block']);

  //dpm($keys, 'keys');

  foreach ($keys as $key => $value) {
    if (strpos($value, '#') === FALSE ) {
      $street_block[$value][] = $format['street_block'][$value];
    }
  }


  $keys = array_keys($format['locality_block']);

  foreach ($keys as $key => $value) {

    if (strpos($value, '#') === FALSE ) {
      $locality_block[$value] = $format['locality_block'][$value];
    }
  }

  $address = array_merge($street_block, $locality_block);

    //clean values for better output
  foreach ($address as $key => $value) {
    unset($value[0]['#tag']);
    unset($value[0]['#title']);
    unset($value[0]['#attributes']);
    unset($value[0]['#size']);
    unset($value[0]['#prefix']);
    unset($value[0]['#weight']);
    unset($value['#tag']);
    unset($value['#title']);
    unset($value['#attributes']);
    unset($value['#size']);
    unset($value['#prefix']);
    unset($value['#weight']);
    unset($value['#suffix']);
    $clean_address[$key] = $value;
  }
}

//address();
function create(){
  // Adds backwards compatability with regression fixed in #1083242
  $account = array();
  $account['name'] = 'fuckmemeemem';
  $account['mail'] = '45asasa34@testte.com';
  $account['pass'] = 'tet';
  $account['status'] = '1';
  $account['field_delivery_address']['und'][0] = array(
                            'element_key' => 'user|user|field_delivery_address|und|0',
                            'thoroughfare' => 'test',
                            'premise' => 'tes',
                            'locality' => 'test',
                            'administrative_area' => 'test',
                            'postal_code' => 'tby3000',
                            'country' => 'VA',
                    );
  $account['field_name']['und'][0]['value'] = 'fuckser';
  dpm($account);
  user_save(NULL, $account);
  // Load the required includes for saving profile information
  // with drupal_form_submit().
  module_load_include('inc', 'user', 'user.pages');

  // register a new user
  $form_state['values'] = $account;
  $form_state['values']['pass'] = array(
    'pass1' => $account['pass'],
    'pass2' => $account['pass'],
  );
  $form_state['values']['op'] = t('Create new account');
  dpm($form_state);
  // execute the register form
  //drupal_form_submit('user_register_form', $form_state);
  // find and store the new user into the form_state
  if(isset($form_state['values']['uid'])) {
    $form_state['user'] = user_load($form_state['values']['uid']);
  }

  // // Error if needed.
  // if ($errors = form_get_errors()) {
  //   return services_error(implode(" ", $errors), 406, array('form_errors' => $errors));
  // }
  // else {
  //   $user = array('uid' => $form_state['user']->uid);
  //   if ($uri = services_resource_uri(array('user', $user['uid']))) {
  //     $user['uri'] = $uri;
  //   }
  // }
  //   //return $user;
}
function user_fields(){
  global $user;
  $user_fields = user_load($user->uid);
  $user_fields = drupal_json_output($user_fields);
  return $user_fields;
}
// user_fields();
//
// create_product_endpoint();
// fix_broken_entity();

menu_execute_active_handler();
