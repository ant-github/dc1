<?php

class HDWishlistController extends EntityAPIController {

  public function save($entity, DatabaseTransaction $transaction = NULL) {
    // Care about setting created and changed values. But do not automatically
    // set a created values for already existing wishlists.
    if (empty($entity->created) && (!empty($entity->is_new) || !$entity->wishlist_id)) {
      $entity->created = REQUEST_TIME;
    }
    $entity->changed = REQUEST_TIME;
    return parent::save($entity, $transaction);
  }
}

class HDWishlistItemController extends EntityAPIController {

  public function save($entity, DatabaseTransaction $transaction = NULL) {
    // Care about setting created and changed values. But do not automatically
    // set a created values for already existing wishlists.
    if (empty($entity->created) && (!empty($entity->is_new) || !$entity->wishlist_item_id)) {
      $entity->created = REQUEST_TIME;
    }
    $entity->changed = REQUEST_TIME;
    return parent::save($entity, $transaction);
  }
}

class HDProductController extends EntityAPIController {

  public function save($entity, DatabaseTransaction $transaction = NULL) {
    // Care about setting created and changed values. But do not automatically
    // set a created values for already existing wishlists.
    if (empty($entity->created) && (!empty($entity->is_new) || !$entity->product_id)) {
      $entity->created = REQUEST_TIME;
    }
    $entity->changed = REQUEST_TIME;
    return parent::save($entity, $transaction);
  }

  public function buildContent($entity, $view_mode = 'full', $langcode = NULL, $content = array()) {
    $content['name'] = array(
      '#type' => 'item',
      '#title' => t('Name'),
      '#markup' => $entity->name,
    );
    return parent::buildContent($entity, $view_mode, $langcode, $content);
  }
}

class HDStoreController extends EntityAPIController {

  public function save($entity, DatabaseTransaction $transaction = NULL) {
    // Care about setting created and changed values. But do not automatically
    // set a created values for already existing wishlists.
    if (empty($entity->created) && (!empty($entity->is_new) || !$entity->store_id)) {
      $entity->created = REQUEST_TIME;
    }
    $entity->changed = REQUEST_TIME;
    return parent::save($entity, $transaction);
  }

  public function buildContent($entity, $view_mode = 'full', $langcode = NULL, $content = array()) {
    $content['name'] = array(
      '#type' => 'item',
      '#title' => t('Name'),
      '#markup' => $entity->name,
    );
    return parent::buildContent($entity, $view_mode, $langcode, $content);
  }
}

class HDPurchaseController extends EntityAPIController {

  public function save($entity, DatabaseTransaction $transaction = NULL) {
    // Care about setting created and changed values. But do not automatically
    // set a created values for already existing wishlists.
    if (empty($entity->created) && (!empty($entity->is_new) || !$entity->purchase_id)) {
      $entity->created = REQUEST_TIME;
    }
    $entity->changed = REQUEST_TIME;
    return parent::save($entity, $transaction);
  }
}
