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
  $json = '{"request":{"pageUrl":"http://www.lovell-rugby.co.uk/Rugby-Shirts/Under-Armour/Wales-2013-or-15-Home-Replica-Rugby-Shirt-Red-or-White/size_XXL?utm_source=googleshopping","api":"product","version":3,"fields":"sku"},"objects":[{"text":"The 2013 Six Nations Champions and home to a number of the winning British & Irish Lions team, you can almost feel the mighty roar as you proudly pull on this rugby shirt.\nMade by Under Armour, from 100% polyester, this Wales 2013/15 Home Replica S/S Rugby Shirt offers a regular loose fit and is a replica of the version as sported by the players themselves.\nFor supreme comfort, elasticated panels off the shoulders and over the chest allow for plenty of freedom of movement so there are no restrictions when it comes to cheering on the team. For a truly professional look and feel, a rubber grip decorates the chest and has even been styled in the form of scales for a truly imposing dragon-like approach.\nThe inclusion of Under Armour\'s Heatgear fabric means that you feel dry and light so that you can be assured of keeping your cool as the action heats up. Ventilated panels beneath the sleeves allow for added breathability which as any rugby fan will tell you, is very much appreciated.\nRed is the proud and distinguished colour of Wales and you certainly don\'t mess with tradition when it comes to their rugby shirt. For an added look, this season sees the arrival of a black to white fade striking down the sides but this doesn\'t distract from the proud WRU badge taking centre stage atop the chest.","pageUrl":"http://www.lovell-rugby.co.uk/Rugby-Shirts/Under-Armour/Wales-2013-or-15-Home-Replica-Rugby-Shirt-Red-or-White/size_XXL?utm_source=googleshopping","humanLanguage":"en","type":"product","sku":"18885","breadcrumb":[{"name":"Home","link":"http://www.lovell-rugby.co.uk/home"},{"name":"Rugby Shirts","link":"http://www.lovell-rugby.co.uk/Rugby-Shirts/LR/"}],"productId":"18885","title":"Wales 2013/15 Home Replica Rugby Shirt Red/White","diffbotUri":"product|3|-77562108","offerPrice":"\u00a349.99","brand":"Under Armour","images":[{"title":"Wales 2013/15 Home Replica Rugby Shirt Red/White","height":333,"diffbotUri":"image|3|106585158","naturalHeight":333,"width":500,"primary":true,"naturalWidth":500,"url":"http://www.lovell-rugby.co.uk/products/products_new/18885.jpg","xpath":"/html[1]/body[1]/div[8]/div[2]/table[3]/tbody[1]/tr[2]/td[2]/div[1]/div[2]/table[1]/tbody[1]/tr[1]/td[1]/div[1]/img[1]"}],"regularPrice":"\u00a355.99","availability":true,"specs":{"sizes_s-3xl_georgia_201415_ss_home_replica_rugby_shirt_blackred_5499":"Sizes: XSB,SB Wales 2013/15 Home Kids S/S Rugby Shirt Red/White Zero VAT \u00a339.99 RRP: \u00a345.99","sizes_s-4xl_wales_201315_home_test_players_rugby_shirt_redwhite_8499":"Sizes: 1-2YRS,2-3YRS,3-4YRS Wales 2013/15 Home Infant S/S Rugby Shirt Red/White Zero VAT \u00a337.99","colour_2":"White","under_armour_product_code":"1237049 600","product_code":"18885","sizes_s-3xl_wales_201415_alternate_replica_rugby_shirt_graphite_4999_rrp_5599":"Sizes: XSB-XLB Wales 2013/15 Alternate Kids S/S Rugby Shirt Graphite/White Zero VAT \u00a339.99 RRP: \u00a345.99","xs_wales_201314_home_ladies_rugby_shirt_redwhite_4999_rrp_5599":"Sizes: 0-6,6-12,12-18,18-24 Wales 2013/14 Home Infant Kit Red/White Zero VAT \u00a332.99 RRP: \u00a336.99","type":"Rugby Shirt","material":"Polyester"}}]}';

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
    $price_original = (string) $product_data['objects'][0]['offerPrice'];
    $price_stripped = preg_replace('/[^0-9\-.]/', '', $price_original); // Removes currency and * 100.
    $currency_unit =  mb_substr($price_original, 0, 1, "utf-8");
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
    //dpm($product,'product');
  }

  //dpm($product);

  // Now set up the users wishlists!
  $user_id = 1;
  $wishlist_id = 44;

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

  $product_url = "http://www.costores.com/gb/";
  //create store entity and save its reference to the new $wishlist_item
  $store = entity_create('store', array('type' =>'store'));
  //add site url
  $site_url=parse_url($product_url);
  $country_code = explode('/',$site_url['path']);
  $country_code = $country_code[1];
  //check country_code lengh to see if has a country in it. e.g gb
  if(strlen($country_code) == 2 ){
      $store->field_store_url = array(LANGUAGE_NONE => array(0 => array('value' => 'http://'.$site_url['host'].'/'.$country_code)));
      }else{
        $store->field_store_url = array(LANGUAGE_NONE => array(0 => array('value' => 'http://'.$site_url['host'])));
  }
  $store->name = $site_url['host'];
  //load wishlist user object to get its country
  $wishlist_user = user_load('1');
  $wishlist_user_country = $wishlist_user->field_delivery_address[LANGUAGE_NONE][0]['country'];
  $store->field_store_country = array(LANGUAGE_NONE => array(0 => array('value' => $wishlist_user_country)));
  //save store
  $store->save();
  dpm($store->store_id);
  //save store ref id into the wishlist item
  $wiw->field_store_ref = $store->store_id;
  $wiw->save();
  //$test = entity_load_single('store', '13');
  dpm($wiw->);

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
//create_product_endpoint();
// fix_broken_entity();

menu_execute_active_handler();
