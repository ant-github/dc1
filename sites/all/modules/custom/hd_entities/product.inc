<?php

/**
 * Page title callback for Product pages.
 */
function hd_product_title($entity) {
  return $entity->name;
}

/**
 * Page callback to create a product.
 */
function hd_product_page_add() {
  // Create a product entity structure to be used and passed to the validation
  // and submission functions.
  $entity = entity_get_controller('product')->create();
  return drupal_get_form('hd_product_form', $entity);
}

/**
 * Form function to create a product entity.
 *
 * The pattern is:
 * - Set up the form for the data that is specific to your
 *   entity: the columns of your base table.
 * - Call on the Field API to pull in the form elements
 *   for fields attached to the entity.
 */
function hd_product_form($form, &$form_state, $entity) {

  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#required' => TRUE,
    '#default_value' => $entity->name,
  );

  $form['product'] = array(
    '#type' => 'value',
    '#value' => $entity,
  );

  field_attach_form('product', $entity, $form, $form_state);

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 100,
  );
  if (!isset($entity->is_new)) {
    $form['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#submit' => array('hd_product_form_delete'),
      '#weight' => 200,
    );
  }
  return $form;
}

/**
 * Validation handler for hd_product_form form.
 * We pass things straight through to the Field API to handle validation
 * of the attached fields.
 */
function hd_product_form_validate($form, &$form_state) {
  field_attach_form_validate('product', $form_state['values']['product'], $form, $form_state);
}


/**
 * Form submit handler: submits hd_product_form information
 */
function hd_product_form_submit($form, &$form_state) {
  $entity = $form_state['values']['product'];
  $entity->name = $form_state['values']['name'];
  field_attach_submit('product', $entity, $form, $form_state);
  dpm($entity);
  hd_product_entity_save($entity);
  $form_state['redirect'] = 'product/' . $entity->product_id;
}

function hd_product_form_delete($form, $form_state) {
  $entity = $form_state['values']['product'];
  hd_product_entity_delete($entity);
  $form_state['redirect'] = 'admin/structure/product/manage';
}

/**
 * We save the entity by calling the controller.
 */
function hd_product_entity_save(&$entity) {
  return entity_get_controller('product')->save($entity);
}

/**
 * Use the controller to delete the entity.
 */
function hd_product_entity_delete($entity) {
  entity_get_controller('product')->delete(array($entity->identifier()));
}

/**
 * Fetch a product object.
 *
 * @param $product_id
 *   Integer specifying the product entity id.
 * @param $reset
 *   A boolean indicating that the internal cache should be reset.
 * @return
 *   A fully-loaded $product object or FALSE if it cannot be loaded.
 */
function hd_product_load($product_id = NULL, $reset = FALSE) {
  $product = hd_product_load_multiple(array($product_id), array(), $reset);
  return reset($product);
}

/**
 * Loads multiple product entities.
 *
 * We only need to pass this request along to entity_load(), which
 * will in turn call the load() method of our entity controller class.
 */
function hd_product_load_multiple($product_ids = FALSE, $conditions = array(), $reset = FALSE) {
  return entity_load('product', $product_ids, $conditions, $reset);
}

/**
 * Determines whether the given user has access to a product.
 *
 * @param $op
 *   The operation being performed. One of 'view', 'update', 'create', 'delete'
 *   or just 'edit' (being the same as 'create' or 'update').
 * @param $product
 *   Optionally a product to check access for. If nothing is
 *   given, access for all product is determined.
 * @param $account
 *   The user to check for. Leave it to NULL to check for the global user.
 * @return boolean
 *   Whether access is allowed or not.
 */
function hd_product_access($op, $product = NULL, $account = NULL) {
  switch ($op) {
    case 'view':
      if (user_access("view any product entity", $account)) {
        return TRUE;
      }
    break;
    case 'create':
      if (user_access("create product entities", $account)) {
        return TRUE;
      }
    break;
    case 'delete':
    case 'update':
      if (isset($product) && isset($account) && $product->uid == $account->uid) {
        return TRUE;
      }
    break;
  }
  return FALSE;
}

/**
 * Menu callback to display an entity.
 */
function hd_product_view($entity, $view_mode = 'full') {
  return entity_view('product', array(entity_id('product', $entity) => $entity), $view_mode);
}

/**
 * The class used for product entities.
 */
class Product extends Entity {

  /**
   * The product id.
   *
   * @var integer
   */
  public $product_id;

  /**
   * The product name.
   *
   * @var string
   */
  public $name;

  /**
   * The Unix timestamp when the Product was created.
   *
   * @var integer
   */
  public $created;

  /**
   * The Unix timestamp when the Product was most recently saved.
   *
   * @var integer
   */
  public $changed;


  public function __construct($values = array()) {
    parent::__construct($values, 'product');
  }

  /**
   * Returns the full url() for the Product.
   */
  public function url() {
    $uri = $this->uri();
    return url($uri['path'], $uri);
  }

  /**
   * Returns the drupal path to this Product.
   */
  public function path() {
    $uri = $this->uri();
    return $uri['path'];
  }

  public function defaultUri() {
    return array('path' => 'product/' . $this->identifier());
  }

  public function defaultLabel() {
    return $this->name;
  }
}
