<?php

// drupal
//define('DRUPAL_ROOT', '/Users/reecemarsland/Projects/sponsume/www_migrate');
define('DRUPAL_ROOT', getcwd());
//define('DRUPAL_ROOT', '/web/ubuntu/public_html/mydex.zoocha.com/public');
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


function create_product_endpoint(){
  $json = '{"request":{"pageUrl":"http://www.countryside.co.uk/north-face/mens-kichatna-jacket-0","api":"product","version":3,"fields":"sku"},"objects":[{"text":"The Kichatna is made with premier GORE-TEX Pro to make it fully waterproof with maximum durability and high levels of breathability. The burly design of the jacket makes it a perfect work horse for high out put activities in the roughest conditions that the mountains can provide.\nThe jacket itself is packed full of features including a helmet compatible hood with a wire brim to customize the shape. The access to the pockets are pack and harness friendly. The jacket has Pit zips so that you can dump heat quickly to get comfortable as fast as possible. A removable powder skirt makes the jacket more comfortable when you aren\'t using it in snowy conditions.\nFeatures:\nWaterproof, windproof and breathable\nGORE-TEX Pro Shell technology\nAdjustable hood with wire brim\nHarness and pack friendly pockets\nInvisible PU coating on the zips\nPU coated pit zips\n2 chest pockets\nRemovable powder skirt with gripper elastic\nhidden hem cinch-cord at center front zip\nNon abrasive cuff tabs","pageUrl":"http://www.countryside.co.uk/north-face/mens-kichatna-jacket-0","humanLanguage":"en","type":"product","sku":"035898","productId":"035898","breadcrumb":[{"name":"Home","link":"http://www.countryside.co.uk"},{"name":"Category","link":"http://www.countryside.co.uk/products"},{"name":"Outdoor Clothing","link":"http://www.countryside.co.uk/outdoor-clothing"}],"title":"The North Face - Men\'s Kichatna Jacket","diffbotUri":"product|3|-94870789","offerPrice":"\u00a3325.00","brand":"The North Face","images":[{"height":307,"diffbotUri":"image|3|990022274","naturalHeight":307,"width":307,"primary":true,"naturalWidth":307,"url":"http://www.countryside.co.uk/sites/default/files/styles/product_main/public/images/products/035898-BGR.jpg?itok=lPaSJiQx","xpath":"/html[1]/body[1]/div[4]/div[2]/div[1]/div[1]/div[1]/section[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/div[1]/article[1]/div[1]/div[1]/div[2]/div[1]/div[1]/div[1]/div[1]/a[1]/img[1]"}],"availability":true}]}';

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


menu_execute_active_handler();
