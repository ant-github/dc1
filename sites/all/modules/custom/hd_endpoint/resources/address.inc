<?php

/**
 * Services callback to retrieve address fields.
 */
function _hd_get_address_resource($country) {

  //include address.inc file from the addressfield module
  //to use a its functions
  require_once DRUPAL_ROOT . '/sites/all/modules/contrib/addressfield/plugins/format/address.inc';

  $format = array();

  $address = array('country' => $country);
  //use function from the addressfield module
  addressfield_format_address_generate($format, $address, array());
  //get fields fom the street block form
  $keys = array_keys($format['street_block']);

  foreach ($keys as $key => $value) {
    if (strpos($value, '#') === FALSE ) {
      $street_block[$value][] = $format['street_block'][$value];
    }
  }
  //get fields fom the locality block
  $keys = array_keys($format['locality_block']);

  foreach ($keys as $key => $value) {
    if (strpos($value, '#') === FALSE ) {
      $locality_block[$value] = $format['locality_block'][$value];
    }
  }

  $address = array_merge($street_block, $locality_block);

  //clean adress values for better JSON output
  foreach ($address as $key => $value) {
    unset($value[0]['#tag']);
    unset($value[0]['#attributes']);
    unset($value[0]['#size']);
    unset($value[0]['#prefix']);
    unset($value[0]['#weight']);
    unset($value[0]['#render_option_value']);
    unset($value['#tag']);
    unset($value['#render_option_value']);
    unset($value['#attributes']);
    unset($value['#size']);
    unset($value['#prefix']);
    unset($value['#weight']);
    unset($value['#suffix']);
    $clean_address[$key] = $value;
  }
  return $clean_address;
}
