<?php

/**
 * Services callback to retrieve all stores.
 */
function _store_resource_retrieve() {
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'store');
  $result = $query->execute();
  if (isset($result['store'])) {
    $store_ids = array_keys($result['store']);
    $store_items = entity_load('store', $store_ids);
  }

  return $store_items;
}
