<?php

/**
 * @file
 * Services hook for the definition of custom services.
 */

function hd_entities_services_resources() {
  $resource['wishlist']['actions'] = array(
    'retrieve' => array(
      'help' => 'Retrieve wishlists for a user.',
      'file' => array('type' => 'inc', 'module' => 'hd_entities', 'name' => 'resources/wishlist'),
      'callback' => '_wishlist_resource_view',
      'access callback' => 'services_access_menu',
      //'access arguments' => array('update'),
      //'access arguments append' => TRUE,
      'args' => array(
        array(
          'name' => 'uid',
          'optional' => FALSE,
          'source' => array('data' => 'uid'),
          'type' => 'int',
          'description' => 'The wishlist owners uid.',
        ),
      ),
    ),
    'create' => array(
      'help' => 'Create a wishlist for a user.',
      'file' => array('type' => 'inc', 'module' => 'hd_entities', 'name' => 'resources/wishlist'),
      'callback' => '_wishlist_resource_create',
      'access callback' => 'services_access_menu',
      //'access arguments' => array('update'),
      //'access arguments append' => TRUE,
      'args' => array(
        array(
          'name' => 'wishlist_name',
          'optional' => FALSE,
          'source' => array('data' => 'wishlist_name'),
          'type' => 'string',
          'description' => 'The wishlist name.',
        ),
      ),
    ),
    'add_product' => array(
      'help' => 'Add a product to a users wishlist.',
      'file' => array('type' => 'inc', 'module' => 'hd_entities', 'name' => 'resources/wishlist'),
      'callback' => '_wishlist_resource_add_product',
      'access callback' => 'services_access_menu',
      //'access arguments' => array('update'),
      //'access arguments append' => TRUE,
      'args' => array(
        array(
          'name' => 'wishlist_id',
          'optional' => FALSE,
          'source' => array('data' => 'wishlist_id'),
          'type' => 'int',
          'description' => 'The wishlist id.',
        ),
        array(
          'name' => 'product_url',
          'optional' => FALSE,
          'source' => array('data' => 'product_url'),
          'type' => 'string',
          'description' => 'The URL of the product to add the wishlist.',
        ),
      ),
    ),
  );
  $resource['store']['actions'] = array(
    'retrieve' => array(
      'help' => 'Retrieve all stores.',
      'file' => array('type' => 'inc', 'module' => 'hd_entities', 'name' => 'resources/store'),
      'callback' => '_store_resource_retrieve',
      'access callback' => 'services_access_menu',
    ),
  );
  $resource['user']['actions'] = array(
    'dc_login' => array(
      'help' => 'Login a user for a new session',
      'callback' => '_hd_user_resource_login',
      'file' => array('type' => 'inc', 'module' => 'hd_entities', 'name' => 'resources/user'),
      'access callback' => 'services_access_menu',
      'args' => array(
        array(
          'name' => 'username',
          'type' => 'string',
          'description' => 'A valid username',
          'source' => array('data' => 'username'),
          'optional' => FALSE,
        ),
        array(
          'name' => 'password',
          'type' => 'string',
          'description' => 'A valid password',
          'source' => array('data' => 'password'),
          'optional' => FALSE,
        ),
      ),
    ),
  );
  return $resource;
}
