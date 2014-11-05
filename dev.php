<?php

// drupal
//define('DRUPAL_ROOT', '/Users/reecemarsland/Projects/sponsume/www_migrate');
define('DRUPAL_ROOT', getcwd());
//define('DRUPAL_ROOT', '/web/ubuntu/public_html/mydex.zoocha.com/public');
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);





menu_execute_active_handler();
