<?php
function dc_gift_vouchers_admin_currency_page($form, &$form_state){
 $form['usd_price'] = array(
    '#type' => 'textfield', //you can find a list of available types in the form api
    '#title' => '$1 = $',
    '#size' => 10,
    '#maxlength' => 10,
    '#required' => TRUE, //make this field required
  );

  $form['gbp_price'] = array(
    '#type' => 'textfield', //you can find a list of available types in the form api
    '#title' => '$1 = £',
    '#size' => 10,
    '#maxlength' => 10,
    '#required' => TRUE, //make this field required
  );
    $form['eur_price'] = array(
    '#type' => 'textfield', //you can find a list of available types in the form api
    '#title' => '$1 = €',
    '#size' => 10,
    '#maxlength' => 10,
    '#required' => TRUE, //make this field required
  );

 
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 100,
  );
 
  return $form;
    
}
?>
