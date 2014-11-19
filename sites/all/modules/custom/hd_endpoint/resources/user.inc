<?php

function _hd_user_update_resource($uid, $account) {
  // Adds backwards compatability with regression fixed in #1083242
  $account = _services_arg_value($account, 'data');

  $account['uid'] = $uid;

  $account_loaded = user_load($uid);

  // Load the required includes for saving profile information
  // with drupal_form_submit().
  module_load_include('inc', 'user', 'user.pages');

  // If a profile category was passed in, use it. Otherwise default
  // to 'account' (for saving core user data.)
  $category = 'account';
  if (isset($account['category'])) {
    $category = $account['category'];
    unset($account['category']);
  }

  // Drop any passed in values into the $account var. Anything
  // unused by the form just gets ignored. We handle roles and
  // password separately.
  foreach ($account as $key => $value) {
    if ($key != 'pass' && $key != 'roles') {
      $form_state['values'][$key] = $value;
    }
  }

  // Prepare values of roles.
  if (!isset($account['roles'])) {
    $account['roles'] = $account_loaded->roles;
  }
  foreach ($account['roles'] as $key => $value) {
    if (!empty($value)) {
      $form_state['values']['roles'][$key] = $key;
    }
  }
  unset($form_state['values']['roles'][2]);

  // Prepare values for password.
  if (isset($account['pass'])) {
    $form_state['values']['pass']['pass1'] = $account['pass'];
    $form_state['values']['pass']['pass2'] = $account['pass'];
  }

  // If user is changing name, make sure they have permission.
  if (isset($account['name']) && $account['name'] != $account_loaded->name && !(user_access('change own username') || !user_access('administer users'))) {
    return services_error(t('You are not allowed to change your username.'), 406);
  }

  $form_state['values']['op'] = variable_get('services_user_save_button_resource_update', t('Save'));
  $form_state['values']['#user_category'] = $category;
  $form_state['values']['#account'] = $account_loaded;

  //Set the country of the loaded user object = the country of the received JSON object
  //for unknown reason the values would not update the country on the addressfield
  $account_loaded->field_delivery_address['und'][0]['country'] = $account['field_delivery_address']['und'][0]['country'];

  //Gender
  $account_loaded->field_gender['und'][0]['value'] = $account['field_gender'];

  $ret = drupal_form_submit('user_profile_form', $form_state, $account_loaded, $category);

  // Error if needed.
  if ($errors = form_get_errors()) {
    return services_error(implode(" ", $errors), 406, array('form_errors' => $errors));
  }
  else {
    services_remove_user_data($account);
    return $account;
  }
}

function _hd_user_resource_create($account) {
  // Adds backwards compatability with regression fixed in #1083242
  $account = _services_arg_value($account, 'data');

  //check if user exists and username not taken
  $mail = $account['mail'];
  $name = $account['name'];
  $user_mail_exists = user_load_by_mail($mail);
  $user_name_exists = user_load_by_name($name);

  if($user_mail_exists){
      $account['account_already_exists'] = 'YES';
    }elseif($user_name_exists) {
      $account['user_name_exists'] = 'YES';
    }else{
      //save user account
      user_save(NULL, $account);
      $account = user_load_by_mail($mail);
  }
  return $account;
}
