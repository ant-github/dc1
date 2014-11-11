<?php

/**
 * Page title callback for wishlist item pages.
 */
function hd_wishlist_item_title($entity) {
  return $entity->defaultLabel();
}

/**
 * Page callback to create a wishlist item.
 */
function hd_wishlist_item_page_add() {
  // Create a wishlist item entity structure to be used and passed to the validation
  // and submission functions.
  global $user;
  $values['user'] = $user->uid;
  $entity = entity_get_controller('wishlist_item')->create($values);
  return drupal_get_form('hd_wishlist_item_form', $entity);
}

/**
 * Form function to create a wishlist item entity.
 *
 * The pattern is:
 * - Set up the form for the data that is specific to your
 *   entity: the columns of your base table.
 * - Call on the Field API to pull in the form elements
 *   for fields attached to the entity.
 */
function hd_wishlist_item_form($form, &$form_state, $entity) {

  $form['wishlist_item'] = array(
    '#type' => 'value',
    '#value' => $entity,
  );

  field_attach_form('wishlist_item', $entity, $form, $form_state);

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 100,
  );
  if (!isset($entity->is_new)) {
    $form['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#submit' => array('hd_wishlist_item_delete'),
      '#limit_validation_errors' => array(),
      '#weight' => 200,
    );
  }
  return $form;
}

/**
 * Validation handler for hd_wishlist_item_form form.
 * We pass things straight through to the Field API to handle validation
 * of the attached fields.
 */
function hd_wishlist_item_form_validate($form, &$form_state) {
  field_attach_form_validate('wishlist_item', $form_state['values']['wishlist_item'], $form, $form_state);
}


/**
 * Form submit handler: submits hd_wishlist_item_form information
 */
function hd_wishlist_item_form_submit($form, &$form_state) {
  $entity = $form_state['values']['wishlist_item'];
  field_attach_submit('wishlist_item', $entity, $form, $form_state);
  // Set a unique item code for new wishlist items.
  if (isset($entity->is_new)) {
    $item_code = hd_wishlist_item_generate_item_code();
    $entity->field_item_code[LANGUAGE_NONE][0]['value'] = $item_code;
  }
  hd_wishlist_item_entity_save($entity);

  $form_state['redirect'] = 'wishlist/item/' . $entity->wishlist_item_id;
}

function hd_wishlist_item_delete($form, &$form_state) {
  $entity = $form['wishlist_item']['#value'];
  hd_wishlist_item_entity_delete($entity);
  $form_state['redirect'] = 'admin/structure/wishlist-item/manage';
}


/**
 * We save the entity by calling the controller.
 */
function hd_wishlist_item_entity_save(&$entity) {
  return entity_get_controller('wishlist_item')->save($entity);
}

/**
 * Use the controller to delete the entity.
 */
function hd_wishlist_item_entity_delete($entity) {
  entity_delete('wishlist_item', entity_id('wishlist_item', $entity));
}

/**
 * Fetch a wishlist item object.
 *
 * @param $wishlist_item_id
 *   Integer specifying the wishlist item entity id.
 * @param $reset
 *   A boolean indicating that the internal cache should be reset.
 * @return
 *   A fully-loaded $wishlist object or FALSE if it cannot be loaded.
 */
function hd_wishlist_item_load($wishlist_item_id = NULL, $reset = FALSE) {
  $wishlist_items = hd_wishlist_item_load_multiple(array($wishlist_item_id), array(), $reset);
  return reset($wishlist_items);
}

/**
 * Loads multiple wishlist item entities.
 *
 * We only need to pass this request along to entity_load(), which
 * will in turn call the load() method of our entity controller class.
 */
function hd_wishlist_item_load_multiple($wishlist_item_ids = FALSE, $conditions = array(), $reset = FALSE) {
  return entity_load('wishlist_item', $wishlist_item_ids, $conditions, $reset);
}

/**
 * Determines whether the given user has access to a wishlist item.
 *
 * @param $op
 *   The operation being performed. One of 'view', 'update', 'create', 'delete'
 *   or just 'edit' (being the same as 'create' or 'update').
 * @param $wishlist_item
 *   Optionally a wishlist item to check access for. If nothing is
 *   given, access for all wishlist is determined.
 * @param $account
 *   The user to check for. Leave it to NULL to check for the global user.
 * @return boolean
 *   Whether access is allowed or not.
 */
function hd_wishlist_item_access($op, $wishlist_item = NULL, $account = NULL) {
  switch ($op) {
    case 'view':
      if (user_access("view any wishlist item entity", $account)) {
        return TRUE;
      }
    break;
    case 'create':
      if (user_access("create wishlist item entities", $account)) {
        return TRUE;
      }
    break;
    case 'delete':
    case 'update':
      if (isset($wishlist_item) && isset($account) && $wishlist_item->uid == $account->uid) {
        return TRUE;
      }
    break;
  }
  return FALSE;
}

/**
 * Menu callback to display an entity.
 */
function hd_wishlist_item_view($entity, $view_mode = 'full') {
  return entity_view('wishlist_item', array(entity_id('wishlist_item', $entity) => $entity), $view_mode);
}

/**
 * Generate an item code, ensuring it is unique.
 */
function hd_wishlist_item_generate_item_code() {
  $item_code = strtoupper(substr(md5(rand()),0,20));
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'wishlist_item')
    ->fieldCondition('field_item_code', 'value', $item_code, '=')
    ->addMetaData('account', user_load(1)); // Run the query as user 1.
  $result = $query->execute();
  if (isset($result['wishlist_item'])) {
    return hd_wishlist_item_generate_item_code();
  }
  return $item_code;
}

function hd_entities_forms($form_id) {
  if (strpos($form_id, 'hd_wishlist_item_remove_form') !== FALSE) {
    return array(
      $form_id => array(
      'callback' => 'hd_wishlist_item_remove_form',
      ),
    );
  }
  if (strpos($form_id, 'hd_wishlist_item_reserve_form') !== FALSE) {
    return array(
      $form_id => array(
      'callback' => 'hd_wishlist_item_reserve_form',
      ),
    );
  }
}

/**
 * Form to remove wishlist items.
 */
function hd_wishlist_item_remove_form($form, &$form_state, $wishlist_item_id, $wishlist_id) {
  $form['wishlist_id'] = array(
    '#type' => 'value',
    '#value' => $wishlist_id,
  );
  $form['wishlist_item_id'] = array(
    '#type' => 'value',
    '#value' => $wishlist_item_id,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Remove') . '<span class="hd-glyphicon-double-chevron"></span>',
    '#attributes' => array(
      'data-toggle' => 'confirmation',
      'data-placement' => 'left',
      'class' => array('remove-item')
    ),
  );
  return $form;
}

function hd_wishlist_item_remove_form_submit($form, &$form_state) {
  // Load the wishlist and remove the reference.
  $wishlist = hd_wishlist_load($form_state['values']['wishlist_id']);
  $ww = entity_metadata_wrapper('wishlist', $wishlist);

  foreach ($ww->field_wishlist_items->getIterator() as $delta => $wiw) {
    if ($wiw->wishlist_item_id->value() == $form_state['values']['wishlist_item_id']) {
      // Delete the wishlist item.
      $wiw->delete();

      // Remove the reference.
      $ww->field_wishlist_items[$delta]->set(NULL);
      $ww->save();

    }
  }
  drupal_set_message(t('The item was removed from your wishlist.'));
}

/**
 * Form to reserve wishlist items.
 */
function hd_wishlist_item_reserve_form($form, &$form_state, $wishlist_item_id) {
  $wishlist_item = hd_wishlist_item_load($wishlist_item_id);
  $wrapper = entity_metadata_wrapper('wishlist_item', $wishlist_item);
  $reserving_user = $wrapper->field_reserving_user->raw();

  $form['wishlist_item_id'] = array(
    '#type' => 'hidden',
    '#value' => $wishlist_item_id,
    '#prefix' => "<div id='hd-wishlist-reserver-form-wrapper-$wishlist_item_id' class=\"hd-wishlist-reserve-form\">",
  );
  $form['reserving_user'] = array(
    '#type' => 'hidden',
    '#value' => $reserving_user,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => ($reserving_user === NULL) ? t('Reserve') : t('Unreserve'),

    '#suffix' => '</div>',
    '#ajax' => array(
      'submitter' => FALSE,
      'event' => 'click',
      'callback' => 'hd_wishlist_item_reserve_form_ajax_callback',
      'wrapper' => "hd-wishlist-reserver-form-wrapper-$wishlist_item_id",
      'method' => 'replace',
      'effect' => 'fade',
    ),

  );
  return $form;
}

/**
 * Ajax callback for reserve form.
 */
function hd_wishlist_item_reserve_form_ajax_callback($form, $form_state) {
  return array(
    $form['wishlist_item_id'], $form['reserving_user'], $form['submit'],
  );
}

/**
 * Submit handler for reserve form.
 */
function hd_wishlist_item_reserve_form_submit($form, &$form_state) {
  // Load the wishlist item and set/unset the reserving user field.
  $wishlist_item = hd_wishlist_item_load($form_state['values']['wishlist_item_id'], TRUE);
  $wiw = entity_metadata_wrapper('wishlist_item', $wishlist_item);
  $reserving_user = $form_state['values']['reserving_user'];
  global $user;

  // Unreserve.
  if ($reserving_user !== NULL) {
    $wiw->field_reserving_user = NULL;
    $wiw->field_status = 'available';
    drupal_set_message(t('The item has been unreserved.'));
  }
  // Reserve.
  else {
    $wiw->field_reserving_user = $user->uid;
    $wiw->field_status = 'reserved';
    drupal_set_message(t('The item has been set as reserved for 24 hours.'));
  }
  $wiw->save();
  $form_state['rebuild'] = TRUE;
}

/**
 * The class used for Wishlist item entities.
 */
class WishlistItem extends Entity {

  /**
   * The Wishlist item id.
   *
   * @var integer
   */
  public $wishlist_item_id;


  /**
   * The user id of the Wishlist owner.
   *
   * @var integer
   */
  public $uid;

  /**
   * The Unix timestamp when the Wishlist was created.
   *
   * @var integer
   */
  public $created;

  /**
   * The Unix timestamp when the Wishlist was most recently saved.
   *
   * @var integer
   */
  public $changed;


  public function __construct($values = array()) {
    if (isset($values['user'])) {
      $this->setUser($values['user']);
      unset($values['user']);
    }
    parent::__construct($values, 'wishlist_item');
  }

  /**
   * Returns the user owning this Wishlist.
   */
  public function user() {
    return user_load($this->uid);
  }

  /**
   * Sets a new user owning this Wishlist.
   *
   * @param $account
   *   The user account object or the user account id (uid).
   */
  public function setUser($account) {
    $this->uid = is_object($account) ? $account->uid : $account;
  }

  /**
   * Returns the full url() for the Wishlist.
   */
  public function url() {
    $uri = $this->uri();
    return url($uri['path'], $uri);
  }

  /**
   * Returns the drupal path to this Wishlist.
   */
  public function path() {
    $uri = $this->uri();
    return $uri['path'];
  }

  public function defaultUri() {
    return array('path' => 'wishlist/item/' . $this->identifier());
  }

  public function defaultLabel() {
    // Display the linked product name as the label for this wishlist item.
    $wrapper = entity_metadata_wrapper('wishlist_item', $this);
    return $wrapper->field_product->name->value();
  }
}
