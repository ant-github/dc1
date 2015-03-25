<?php

/**
 * Page title callback for wishlist pages.
 * @param  [type] $entity [description]
 * @return [type]         [description]
 */
function hd_wishlist_title($entity) {
  return $entity->name;
}

/**
 * Page callback to create a wishlist.
 */
function hd_wishlist_page_add() {
  // Create a wishlist entity structure to be used and passed to the validation
  // and submission functions.
  global $user;
  $values['user'] = $user->uid;
  $entity = entity_get_controller('wishlist')->create($values);
  return drupal_get_form('hd_wishlist_form', $entity);
}

/**
 * Form function to create a wishlist entity.
 *
 * The pattern is:
 * - Set up the form for the data that is specific to your
 *   entity: the columns of your base table.
 * - Call on the Field API to pull in the form elements
 *   for fields attached to the entity.
 */
function hd_wishlist_form($form, &$form_state, $entity) {
  $form_message_html = '<div class="alert alert-warning">';
  $form_message_html .= '<h3>Rename your wishlist</h3>';
  $form_message_html .= '<p>Give your wishlist a name and click "Save"</p>';
  $form_message_html .= '</div>';

  $form['message'] = array(
    '#markup' => $form_message_html
  );
  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#required' => TRUE,
    '#default_value' => $entity->name,
  );

 // $form['wishlist'] = array(
 //   '#type' => 'value',
 //   '#value' => $entity,
 // );
  //dpm($entity);
  field_attach_form('wishlist', $entity, $form, $form_state);

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 100,
  );

  if (!isset($entity->is_new)) {
    $form['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#submit' => array('hd_wishlist_form_delete'),
      '#weight' => 200,
    );
  }
  return $form;
}

/**
 * Validation handler for hd_wishlist_form form.
 * We pass things straight through to the Field API to handle validation
 * of the attached fields.
 */
function hd_wishlist_form_validate($form, &$form_state) {
  field_attach_form_validate('wishlist', $form_state['values']['wishlist'], $form, $form_state);
}


/**
 * Form submit handler: submits hd_wishlist_form information
 */
function hd_wishlist_form_submit($form, &$form_state) {
  $entity = $form_state['values']['wishlist'];
  $entity->name = $form_state['values']['name'];
  field_attach_submit('wishlist', $entity, $form, $form_state);
  hd_wishlist_entity_save($entity);
  $form_state['redirect'] = 'wishlist/' . $entity->wishlist_id;
}

function hd_wishlist_form_delete($form, &$form_state) {
  $entity = $form['wishlist']['#value'];
  hd_wishlist_entity_delete($entity);
  $form_state['redirect'] = 'admin/structure/wishlist/manage';
}


/**
 * We save the entity by calling the controller.
 */
function hd_wishlist_entity_save(&$entity) {
  return entity_get_controller('wishlist')->save($entity);
}

/**
 * Use the controller to delete the entity.
 */
function hd_wishlist_entity_delete($entity) {
  entity_delete('wishlist', entity_id('wishlist', $entity));
}

/**
 * Fetch a wishlist object.
 *
 * @param $wishlist_id
 *   Integer specifying the wishlist entity id.
 * @param $reset
 *   A boolean indicating that the internal cache should be reset.
 * @return
 *   A fully-loaded $wishlist object or FALSE if it cannot be loaded.
 */
function hd_wishlist_load($wishlist_id = NULL, $reset = FALSE) {
  $wishlist = hd_wishlist_load_multiple(array($wishlist_id), array(), $reset);
  return reset($wishlist);
}

/**
 * Loads multiple wishlist entities.
 *
 * We only need to pass this request along to entity_load(), which
 * will in turn call the load() method of our entity controller class.
 */
function hd_wishlist_load_multiple($wishlist_ids = FALSE, $conditions = array(), $reset = FALSE) {
  return entity_load('wishlist', $wishlist_ids, $conditions, $reset);
}

/**
 * Determines whether the given user has access to a wishlist.
 *
 * @param $op
 *   The operation being performed. One of 'view', 'update', 'create', 'delete'
 *   or just 'edit' (being the same as 'create' or 'update').
 * @param $wishlist
 *   Optionally a wishlist to check access for. If nothing is
 *   given, access for all wishlist is determined.
 * @param $account
 *   The user to check for. Leave it to NULL to check for the global user.
 * @return boolean
 *   Whether access is allowed or not.
 */
function hd_wishlist_access($op, $wishlist = NULL, $account = NULL) {
  switch ($op) {
    case 'view':
      if (user_access("view any wishlist entity", $account)) {
        return TRUE;
      }
    break;
    case 'create':
      if (user_access("create wishlist entities", $account)) {
        return TRUE;
      }
    break;
    case 'delete':
    case 'update':
      if (isset($wishlist) && isset($account) && $wishlist->uid == $account->uid) {
        return TRUE;
      }
    break;
  }
  return FALSE;
}

/**
 * Menu callback to display an entity.
 */
function hd_wishlist_view($entity, $view_mode = 'full') {
  return entity_view('wishlist', array(entity_id('wishlist', $entity) => $entity), $view_mode);
}

/**
 * The class used for Wishlist entities.
 */
class Wishlist extends Entity {

  /**
   * The Wishlist id.
   *
   * @var integer
   */
  public $wishlist_id;

  /**
   * The wishlist name.
   *
   * @var string
   */
  public $name;

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
    parent::__construct($values, 'wishlist');
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
    return array('path' => 'wishlist/' . $this->identifier());
  }

  public function defaultLabel() {
    return $this->name;
  }
}
