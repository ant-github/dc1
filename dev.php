<?php

// drupal
//define('DRUPAL_ROOT', '/Users/reecemarsland/Projects/sponsume/www_migrate');
define('DRUPAL_ROOT', getcwd());
//define('DRUPAL_ROOT', '/web/ubuntu/public_html/mydex.zoocha.com/public');
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


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
create();


menu_execute_active_handler();
