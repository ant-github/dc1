<?php
/**
 * Implementation of hook_views_handlers().
 */
function hd_jeditable_views_handlers() {
  return array(
    'info' => array(
      'path' => drupal_get_path('module', 'hd_jeditable') . '/includes',
    ),
    'handlers' => array(
      'views_handler_field_hd_jeditable' => array(
        'parent' => 'views_handler_field',
      ),
    ),
  );
}


/**
 * Implements hook_views_data()
 */
function hd_jeditable_views_data() {
  // published status
  $data['node']['status-hd_jeditable'] = array(
    'title' => t('Published').' - hd_jeditable',
    'help' => t('Whether or not the content is published.'),
    'field' => array(
      'field' => 'status',
      'handler' => 'views_handler_field_hd_jeditable',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );
  $data['node']['sticky-hd_jeditable'] = array(
    'title' => t('Sticky').' - hd_jeditable', // The item it appears as on the UI,
    'help' => t('Whether or not the content is sticky.'), // The help that appears on the UI,
    'field' => array(
      'field' => 'sticky',
      'handler' => 'views_handler_field_hd_jeditable',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );
  $data['node']['promote-hd_jeditable'] = array(
    'title' => t('Promoted to front page').' - hd_jeditable',
    'help' => t('Whether or not the content is promoted to the front page.'),
    'field' => array(
      'field' => 'promote',
      'handler' => 'views_handler_field_hd_jeditable',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );
  return $data;

}

