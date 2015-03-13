<?php

/**
 * @file
 * Rules integration with the CCBill module
 */



/**
 * Implements hook_rules_event_info().
 * @ingroup rules
 */
function ccbill_rules_event_info() {
  return array(
    'ccbill_add' => array(
      'label' => t('User\'s CCBill subscription started'),
      'module' => 'CCBill',
      'arguments' => array(
        'account' => array(
          'type' => 'user',
          'label' => t('The user that just signed up via CCBill'),
        ),
      ),
    ),
    'ccbill_expire' => array(
      'label' => t('User\'s CCBill subscription expired'),
      'module' => 'CCBill',
      'arguments' => array(
        'account' => array(
          'type' => 'user',
          'label' => t('The user that has an active subscription with CCBill'),
        ),
      ),
    ),
  );
}
