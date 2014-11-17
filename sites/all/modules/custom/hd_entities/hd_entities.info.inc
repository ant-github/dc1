<?php

/**
 * @file
 * Provides Entity metadata integration.
 */

/**
 * Extend the defaults.
 */
class HDWishlistMetadataController extends EntityDefaultMetadataController {
  public function entityPropertyInfo() {
    $info = parent::entityPropertyInfo();
    $properties = &$info[$this->type]['properties'];

    $properties['name'] = array(
      'label' => t('Name'),
      'description' => t('The wishlist name.'),
      'setter callback' => 'entity_property_verbatim_set',
      'setter permission' => 'administer profiles',
      'schema field' => 'name',
    );

    unset($properties['uid']);

    $properties['user'] = array(
      'label' => t('Author'),
      'type' => 'user',
      'description' => t("The owner of the wishlist."),
      'getter callback' => 'entity_property_getter_method',
      'setter callback' => 'entity_property_setter_method',
      'setter permission' => 'administer profiles',
      'required' => TRUE,
      'schema field' => 'uid',
    );

    $properties['created'] = array(
      'label' => t('Date created'),
      'type' => 'date',
      'description' => t("The date the wishlist was created."),
      'setter callback' => 'entity_property_verbatim_set',
      'setter permission' => 'administer profiles',
      'schema field' => 'created',
    );
    $properties['changed'] = array(
      'label' => t('Date changed'),
      'type' => 'date',
      'schema field' => 'changed',
      'description' => t("The date the wishlist was most recently updated."),
    );

    return $info;
  }
}

/**
 * Extend the defaults.
 */
class HDWishlistItemMetadataController extends EntityDefaultMetadataController {
  public function entityPropertyInfo() {
    $info = parent::entityPropertyInfo();
    $properties = &$info[$this->type]['properties'];

    $properties['name'] = array(
      'label' => t('Name'),
      'description' => t('The wishlist name.'),
      'setter callback' => 'entity_property_verbatim_set',
      'setter permission' => 'administer profiles',
      'schema field' => 'name',
    );

    unset($properties['uid']);

    $properties['user'] = array(
      'label' => t('Author'),
      'type' => 'user',
      'description' => t("The owner of the wishlist."),
      'getter callback' => 'entity_property_getter_method',
      'setter callback' => 'entity_property_setter_method',
      'setter permission' => 'administer profiles',
      'required' => TRUE,
      'schema field' => 'uid',
    );

    $properties['created'] = array(
      'label' => t('Date created'),
      'type' => 'date',
      'description' => t("The date the wishlist was created."),
      'setter callback' => 'entity_property_verbatim_set',
      'setter permission' => 'administer profiles',
      'schema field' => 'created',
    );
    $properties['changed'] = array(
      'label' => t('Date changed'),
      'type' => 'date',
      'schema field' => 'changed',
      'description' => t("The date the wishlist was most recently updated."),
    );

    return $info;
  }
}

// /**
//  * Extend the defaults.
//  */
// class HDProductMetadataController extends EntityDefaultMetadataController {
//   public function entityPropertyInfo() {
//     $info = parent::entityPropertyInfo();
//     $properties = &$info[$this->type]['properties'];

//     $properties['name'] = array(
//       'label' => t('Name'),
//       'description' => t('The wishlist name.'),
//       'setter callback' => 'entity_property_verbatim_set',
//       'setter permission' => 'administer profiles',
//       'schema field' => 'name',
//     );

//     $properties['created'] = array(
//       'label' => t('Date created'),
//       'type' => 'date',
//       'description' => t("The date the wishlist was created."),
//       'setter callback' => 'entity_property_verbatim_set',
//       'setter permission' => 'administer profiles',
//       'schema field' => 'created',
//     );
//     $properties['changed'] = array(
//       'label' => t('Date changed'),
//       'type' => 'date',
//       'schema field' => 'changed',
//       'description' => t("The date the wishlist was most recently updated."),
//     );

//     return $info;
//   }
// }

/**
 * Extend the defaults.
 */
class HDStoreMetadataController extends EntityDefaultMetadataController {
  public function entityPropertyInfo() {
    $info = parent::entityPropertyInfo();
    $properties = &$info[$this->type]['properties'];

    $properties['name'] = array(
      'label' => t('Name'),
      'description' => t('The store name.'),
      'setter callback' => 'entity_property_verbatim_set',
      'setter permission' => 'administer profiles',
      'schema field' => 'name',
    );

    $properties['created'] = array(
      'label' => t('Date created'),
      'type' => 'date',
      'description' => t("The date the store was created."),
      'setter callback' => 'entity_property_verbatim_set',
      'setter permission' => 'administer profiles',
      'schema field' => 'created',
    );
    $properties['changed'] = array(
      'label' => t('Date changed'),
      'type' => 'date',
      'schema field' => 'changed',
      'description' => t("The date the store was most recently updated."),
    );

    return $info;
  }
}

/**
 * Extend the defaults.
 */
class HDPurchaseMetadataController extends EntityDefaultMetadataController {
  public function entityPropertyInfo() {
    $info = parent::entityPropertyInfo();
    $properties = &$info[$this->type]['properties'];

    unset($properties['uid']);

    $properties['user'] = array(
      'label' => t('Author'),
      'type' => 'user',
      'description' => t("The owner of the purchase."),
      'getter callback' => 'entity_property_getter_method',
      'setter callback' => 'entity_property_setter_method',
      'setter permission' => 'administer profiles',
      'required' => TRUE,
      'schema field' => 'uid',
    );

    $properties['created'] = array(
      'label' => t('Date created'),
      'type' => 'date',
      'description' => t("The date the purchase was created."),
      'setter callback' => 'entity_property_verbatim_set',
      'setter permission' => 'administer profiles',
      'schema field' => 'created',
    );
    $properties['changed'] = array(
      'label' => t('Date changed'),
      'type' => 'date',
      'schema field' => 'changed',
      'description' => t("The date the purchase was most recently updated."),
    );

    return $info;
  }
}

