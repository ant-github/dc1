<?php

/**
 * Page title callback for purchase pages.
 */
function hd_purchase_title($entity) {
  return $entity->defaultLabel();
}

/**
 * Page callback to create a purchase.
 */
function hd_purchase_page_add() {
  // Create a purchase entity structure to be used and passed to the validation
  // and submission functions.
  global $user;
  $values['user'] = $user->uid;
  $entity = entity_get_controller('purchase')->create($values);
  return drupal_get_form('hd_purchase_form', $entity);
}

/**
 * Form function to create a purchase entity.
 *
 * The pattern is:
 * - Set up the form for the data that is specific to your
 *   entity: the columns of your base table.
 * - Call on the Field API to pull in the form elements
 *   for fields attached to the entity.
 */
function hd_purchase_form($form, &$form_state, $entity) {

  $form['purchase'] = array(
    '#type' => 'value',
    '#value' => $entity,
  );

  field_attach_form('purchase', $entity, $form, $form_state);

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 100,
  );
  if (!isset($entity->is_new)) {
    $form['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#submit' => array('hd_purchase_delete'),
      '#limit_validation_errors' => array(),
      '#weight' => 200,
    );
  }
  dpm($form);
  return $form;
}

/**
 * Validation handler for hd_purchase_form form.
 * We pass things straight through to the Field API to handle validation
 * of the attached fields.
 */
function hd_purchase_form_validate($form, &$form_state) {
  field_attach_form_validate('purchase', $form_state['values']['purchase'], $form, $form_state);
}


/**
 * Form submit handler: submits hd_purchase_form information
 */
function hd_purchase_form_submit($form, &$form_state) {
  $entity = $form_state['values']['purchase'];
  field_attach_submit('purchase', $entity, $form, $form_state);
  hd_purchase_entity_save($entity);
  $form_state['redirect'] = 'purchase/' . $entity->purchase_id;
}

function hd_purchase_delete($form, &$form_state) {
  $entity = $form['purchase']['#value'];
  hd_purchase_entity_delete($entity);
  $form_state['redirect'] = 'admin/structure/purchase/manage';
}


/**
 * We save the entity by calling the controller.
 */
function hd_purchase_entity_save(&$entity) {
  return entity_get_controller('purchase')->save($entity);
}

/**
 * Use the controller to delete the entity.
 */
function hd_purchase_entity_delete($entity) {
  entity_delete('purchase', entity_id('purchase', $entity));
}

/**
 * Fetch a purchase object.
 *
 * @param $purchase_id
 *   Integer specifying the purchase entity id.
 * @param $reset
 *   A boolean indicating that the internal cache should be reset.
 * @return
 *   A fully-loaded $purchase object or FALSE if it cannot be loaded.
 */
function hd_purchase_load($purchase_id = NULL, $reset = FALSE) {
  $purchase_ids = (isset($purchase_id) ? array($purchase_id) : array());
  $purchase = hd_purchase_load_multiple($purchase_ids, array(), $reset);
  return $purchase ? reset($purchase) : FALSE;
}

/**
 * Loads multiple purchase entities.
 *
 * We only need to pass this request along to entity_load(), which
 * will in turn call the load() method of our entity controller class.
 */
function hd_purchase_load_multiple($purchase_ids = FALSE, $conditions = array(), $reset = FALSE) {
  return entity_load('purchase', $purchase_ids, $conditions, $reset);
}

/**
 * Determines whether the given user has access to a purchase.
 *
 * @param $op
 *   The operation being performed. One of 'view', 'update', 'create', 'delete'
 *   or just 'edit' (being the same as 'create' or 'update').
 * @param $purchase
 *   Optionally a purchase to check access for. If nothing is
 *   given, access for all purchase is determined.
 * @param $account
 *   The user to check for. Leave it to NULL to check for the global user.
 * @return boolean
 *   Whether access is allowed or not.
 */
function hd_purchase_access($op, $purchase = NULL, $account = NULL) {
  switch ($op) {
    case 'view':
      if (user_access("view any purchase entity", $account)) {
        return TRUE;
      }
    break;
    case 'create':
      if (user_access("create purchase entities", $account)) {
        return TRUE;
      }
    break;
    case 'delete':
    case 'update':
      if (isset($purchase) && isset($account) && $purchase->uid == $account->uid) {
        return TRUE;
      }
    break;
  }
  return FALSE;
}

/**
 * Menu callback to display an entity.
 */
function hd_purchase_view($entity, $view_mode = 'full') {
  return entity_view('purchase', array(entity_id('purchase', $entity) => $entity), $view_mode);
}

/**
 * Retreive a purchase entity
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function hd_purchase_get_purchase_by_wishlist_item_id($id) {
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'purchase')
    ->fieldCondition('field_wishlist_item', 'target_id', $id, '=')
    ->addMetaData('account', user_load(1)); // Run the query as user 1.
  $result = $query->execute();
  if (isset($result['purchase'])) {
    $purchase_id = reset($result['purchase']);
    return hd_purchase_load($purchase_id->purchase_id);
  }
  else {
    return FALSE;
  }
}


/**
 * The class used for Purchase item entities.
 */
class Purchase extends Entity {

  /**
   * The Purchase item id.
   *
   * @var integer
   */
  public $purchase_id;


  /**
   * The user id of the Purchase owner.
   *
   * @var integer
   */
  public $uid;

  /**
   * The Unix timestamp when the Purchase was created.
   *
   * @var integer
   */
  public $created;

  /**
   * The Unix timestamp when the Purchase was most recently saved.
   *
   * @var integer
   */
  public $changed;


  public function __construct($values = array()) {
    if (isset($values['user'])) {
      $this->setUser($values['user']);
      unset($values['user']);
    }
    parent::__construct($values, 'purchase');
  }

  /**
   * Returns the user owning this Purchase.
   */
  public function user() {
    return user_load($this->uid);
  }

  /**
   * Sets a new user owning this Purchase.
   *
   * @param $account
   *   The user account object or the user account id (uid).
   */
  public function setUser($account) {
    $this->uid = is_object($account) ? $account->uid : $account;
  }

  /**
   * Returns the full url() for the Purchase.
   */
  public function url() {
    $uri = $this->uri();
    return url($uri['path'], $uri);
  }

  /**
   * Returns the drupal path to this Purchase.
   */
  public function path() {
    $uri = $this->uri();
    return $uri['path'];
  }

  public function defaultUri() {
    return array('path' => 'purchase/' . $this->identifier());
  }

  public function defaultLabel() {
    // Display the linked wishlist item code as the label for this purchase entity.
    $wrapper = entity_metadata_wrapper('purchase', $this);
    //return $wrapper->field_wishlist_item->field_item_code->value();
  }
}
