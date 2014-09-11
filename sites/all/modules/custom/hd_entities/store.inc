<?php

/**
 * Page title callback for store item pages.
 */
function hd_store_title($entity) {
  return $entity->name;
}

/**
 * Page callback to create a store item.
 */
function hd_store_page_add() {
  // Create a store item entity structure to be used and passed to the validation
  // and submission functions.
  $entity = entity_get_controller('store')->create();
  return drupal_get_form('hd_store_form', $entity);
}

/**
 * Form function to create a store item entity.
 *
 * The pattern is:
 * - Set up the form for the data that is specific to your
 *   entity: the columns of your base table.
 * - Call on the Field API to pull in the form elements
 *   for fields attached to the entity.
 */
function hd_store_form($form, &$form_state, $entity) {

  $form['store'] = array(
    '#type' => 'value',
    '#value' => $entity,
  );

  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#required' => TRUE,
    '#default_value' => $entity->name,
  );

  field_attach_form('store', $entity, $form, $form_state);

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 100,
  );
  if (!isset($entity->is_new)) {
    $form['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#submit' => array('hd_store_delete'),
      '#limit_validation_errors' => array(),
      '#weight' => 200,
    );
  }
  //dpm($form);
  return $form;
}

/**
 * Validation handler for hd_store_form form.
 * We pass things straight through to the Field API to handle validation
 * of the attached fields.
 */
function hd_store_form_validate($form, &$form_state) {
  field_attach_form_validate('store', $form_state['values']['store'], $form, $form_state);
}


/**
 * Form submit handler: submits hd_store_form information
 */
function hd_store_form_submit($form, &$form_state) {
  $entity = $form_state['values']['store'];
  $entity->name = $form_state['values']['name'];
  field_attach_submit('store', $entity, $form, $form_state);
  hd_store_entity_save($entity);
  $form_state['redirect'] = 'store/' . $entity->store_id;
}

function hd_store_delete($form, &$form_state) {
  $entity = $form['store']['#value'];
  hd_store_entity_delete($entity);
  $form_state['redirect'] = 'admin/structure/store/manage';
}


/**
 * We save the entity by calling the controller.
 */
function hd_store_entity_save(&$entity) {
  return entity_get_controller('store')->save($entity);
}

/**
 * Use the controller to delete the entity.
 */
function hd_store_entity_delete($entity) {
  entity_delete('store', entity_id('store', $entity));
}

/**
 * Fetch a store item object.
 *
 * @param $store_id
 *   Integer specifying the store item entity id.
 * @param $reset
 *   A boolean indicating that the internal cache should be reset.
 * @return
 *   A fully-loaded $store object or FALSE if it cannot be loaded.
 */
function hd_store_load($store_id = NULL, $reset = FALSE) {
  $store_ids = (isset($store_id) ? array($store_id) : array());
  $store = hd_store_load_multiple($store_ids, array(), $reset);
  return $store ? reset($store) : FALSE;
}

/**
 * Loads multiple store item entities.
 *
 * We only need to pass this request along to entity_load(), which
 * will in turn call the load() method of our entity controller class.
 */
function hd_store_load_multiple($store_ids = FALSE, $conditions = array(), $reset = FALSE) {
  return entity_load('store', $store_ids, $conditions, $reset);
}

/**
 * Determines whether the given user has access to a store item.
 *
 * @param $op
 *   The operation being performed. One of 'view', 'update', 'create', 'delete'
 *   or just 'edit' (being the same as 'create' or 'update').
 * @param $store
 *   Optionally a store item to check access for. If nothing is
 *   given, access for all store is determined.
 * @param $account
 *   The user to check for. Leave it to NULL to check for the global user.
 * @return boolean
 *   Whether access is allowed or not.
 */
function hd_store_access($op, $store = NULL, $account = NULL) {
  switch ($op) {
    case 'view':
      if (user_access("view any store entity", $account)) {
        return TRUE;
      }
    break;
    case 'create':
      if (user_access("create store entities", $account)) {
        return TRUE;
      }
    break;
    case 'delete':
    case 'update':
      if (isset($store) && isset($account) && $store->uid == $account->uid) {
        return TRUE;
      }
    break;
  }
  return FALSE;
}

/**
 * Menu callback to display an entity.
 */
function hd_store_view($entity, $view_mode = 'full') {
  return entity_view('store', array(entity_id('store', $entity) => $entity), $view_mode);
}

/**
 * The class used for Store item entities.
 */
class Store extends Entity {

  /**
   * The Store item id.
   *
   * @var integer
   */
  public $store_id;

  /**
   * The store name.
   *
   * @var string
   */
  public $name;


  /**
   * The Unix timestamp when the Store was created.
   *
   * @var integer
   */
  public $created;

  /**
   * The Unix timestamp when the Store was most recently saved.
   *
   * @var integer
   */
  public $changed;


  public function __construct($values = array()) {
    parent::__construct($values, 'store');
  }

  /**
   * Returns the full url() for the Store.
   */
  public function url() {
    $uri = $this->uri();
    return url($uri['path'], $uri);
  }

  /**
   * Returns the drupal path to this Store.
   */
  public function path() {
    $uri = $this->uri();
    return $uri['path'];
  }

  public function defaultUri() {
    return array('path' => 'store/' . $this->identifier());
  }

  public function defaultLabel() {
    return $this->name;
  }
}
