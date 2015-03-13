<?php

/**
 * @file
 *
 * Contains FAPI functions for the CCBill settings form.
 */
function ccbill_settings_admin_form($form, $form_state) {

  $form = array();

  $form['description'] = array(
    '#type' => 'item',
    '#description' => t('If this module is activated, any requests sent to this site from the CCBill servers will be processed and made available for other modules through a hook: hook_ccbill($op, $ccbill). Options for $op are \'add\' for new subscriptions and \'expire\' for expired subscriptions. $ccbill provides the subscription information in an array.'),
  );

  $form['testmode'] = array(
    '#type' => 'fieldset',
    '#title' => t('CCBill Integration Test Mode'),
    '#description' => t('Test your CCBill DataLink integration. This requires an active CCBill account number and valid DataLink credentials set below.'),
  );

  if (variable_get('ccbill_datalink_account_number', '') && variable_get('ccbill_datalink_username', '') && variable_get('ccbill_datalink_password', '')) {
    $form['testmode']['run_test'] = array(
      '#type' => 'submit',
      '#value' => 'Test DataLink Integration',
      '#submit' => array('ccbill_datalink_testmode_submit'),
    );
  }

  $form['datalink'] = array(
    '#type' => 'fieldset',
    '#title' => t('CCBill DataLink Credentials'),
    '#description' => t('To activate the CCBill DataLink integration, you need a valid CCBill account number and valid DataLink credentials.'),
  );

  $form['datalink']['ccbill_datalink_active'] = array(
    '#type' => 'checkbox',
    '#default_value' => variable_get('ccbill_datalink_active', ''),
    '#title' => t('Activate DataLink integration'),
    '#description' => t('Automatically process CCBill expirations using the CCBill DataLink API. Expirations will be processed once daily, on 6AM server time.'),
  );

  $form['datalink']['ccbill_datalink_account_number'] = array(
    '#type' => 'textfield',
    '#default_value' => variable_get('ccbill_datalink_account_number', ''),
    '#title' => t('CCBill Account Number'),
    '#description' => t('Account number used for DataLink integration'),
  );

  $form['datalink']['ccbill_datalink_username'] = array(
    '#type' => 'textfield',
    '#default_value' => variable_get('ccbill_datalink_username', ''),
    '#title' => t('DataLink Username'),
    '#description' => t('DataLink Username (set on https://webadmin.ccbill.com)'),
  );

  $form['datalink']['ccbill_datalink_password'] = array(
    '#type' => 'textfield',
    '#default_value' => variable_get('ccbill_datalink_password', ''),
    '#title' => t('DataLink Password'),
    '#description' => t('DataLink Password (set on https://webadmin.ccbill.com)'),
  );

  return system_settings_form($form);

}



/**
 * Validate the values from the CCBill settings form
 */
function ccbill_settings_admin_form_validate($form, &$form_state) {

  // If the DataLink integration is active,
  // the account number, username and password need to be entered
  if ($form_state['values']['ccbill_datalink_active'] == 1) {
    if (!$form_state['values']['ccbill_datalink_account_number']) {
      form_set_error('ccbill_datalink_account_number', t('The account number is required for DataLink.'));
    }
    if (!$form_state['values']['ccbill_datalink_username']) {
      form_set_error('ccbill_datalink_username', t('The DataLink username is required for DataLink.'));
    }
    if (!$form_state['values']['ccbill_datalink_password']) {
      form_set_error('ccbill_datalink_password', t('The DataLink password is required for DataLink.'));
    }
    if (!is_numeric($form_state['values']['ccbill_datalink_account_number'])) {
      form_set_error('ccbill_datalink_account_number', t('The CCBill Account Number can only be numbers'));
    }
  }

}
