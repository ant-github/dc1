<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see bootstrap_preprocess_page()
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see bootstrap_process_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>
<?php
global $base_url;
global $user;
/***  Changes made due to stripe custom payment method *******/
if($page['content']['system_main']['#form_id'] == 'commerce_checkout_form_payment' && $page['content']['system_main']['#id'] == 'commerce-stripe-redirect-form'){
    $order_id = arg(1);
    $settings = _commerce_stripe_load_settings();
    $order    = commerce_order_load($order_id);
    
    if($order->commerce_order_total['und'][0]['currency_code'] == 'USD'){
        $currency = '$';
    }else if($order->commerce_order_total['und'][0]['currency_code'] == 'EUR'){
        $currency = '€';     
    }else if($order->commerce_order_total['und'][0]['currency_code'] == 'GBP'){
        $currency = '£';     
    }
    $desc_amount = $currency.($order->commerce_order_total['und'][0]['amount']/100);

    $page['content']['system_main']['help']['#markup'] = 'Use the button below to complete your payment process.';
    $page['content']['system_main']['#suffix']= '<form action="'.$base_url.'/stripe/'.$order->order_id.'/payment_status" method="POST"><script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-email="'.$order->mail.'" '
      . 'data-key="'.$settings['public_key'].'" data-label="Pay with Stripe" '
      . 'data-image="'.$base_url.'/sites/all/themes/hiddendelivery/images/logo_dc.png" data-name="deliverycode.com" data-bitcoin="true"  '
      . 'data-description="('.$desc_amount.')" data-amount="'.$order->commerce_order_total['und'][0]['amount'].'" '
      . 'data-currency="'.$order->commerce_order_total['und'][0]['currency_code'].'"></script></form>';
}
if($page['content']['system_main']['#form_id'] == 'commerce_checkout_form_complete'){
    $order_id = arg(1);
    $order    = commerce_order_load($order_id);
    $page['content']['system_main']['checkout_completion_message']['message']['#markup'] = 'Your order has been completed. Your order number is '.$order_id.'. <a href="'.$base_url.'">Return to the front page</a>.';
}
/***** End of stripe custom ******/
$select_table_sizes = db_query('SELECT table_name AS "Tables", 
  round(((data_length + index_length) / 1024 / 1024), 2) "size_in_MB" 
  FROM information_schema.TABLES 
  ORDER BY (data_length + index_length) DESC');

foreach($select_table_sizes AS $res_tables_sizes){
    if($res_tables_sizes->Tables == 'cache_form'){
        if($res_tables_sizes->size_in_MB > 2000){
            db_query("DELETE FROM cache_form WHERE expire < NOW()");
            cache_clear_all(NULL, 'cache_form');
        }     
    }
}
/*
 * 
 * For event's special link on top of the page
 * 
 */
//echo $_COOKIE['topBarGreetings'];
$event_node_id = '';
if(!isset($_COOKIE['topBarGreetings']) && $_COOKIE['topBarGreetings'] != 1){
$select_event_node = db_query("SELECT nid FROM node WHERE type='top_bar_greetings' AND status=1 ORDER BY nid desc limit 1");
foreach($select_event_node AS $res_event_node){
    $event_node_id = $res_event_node->nid;
}

if($event_node_id != ''){
    $select_event_data = db_query("SELECT ed.field_event_date_value AS date, en.field_event_name_value AS name, te.field_text_after_event_name_value AS text FROM field_data_field_event_date AS ed LEFT JOIN field_data_field_event_name AS en ON en.entity_id = ed.entity_id LEFT JOIN field_data_field_text_after_event_name AS te ON te.entity_id = ed.entity_id WHERE ed.entity_id = $event_node_id");
    foreach($select_event_data AS $res_event_data){
        $event_date = date('Y-m-d', strtotime($res_event_data->date));
        $event_name = $res_event_data->name;
        $text_after_event_name = $res_event_data->text;
    }
    $current_date = date('Y-m-d');
    $start_ts = strtotime($current_date);
    $end_ts = strtotime($event_date);
    $diff = $end_ts - $start_ts;
    $days_left =  round($diff / 86400);
  if($days_left >= 0){
?>
    <div id="topBarGreetings">
        <div class="close-greetings">x</div>
        <a class="link-to-allproducts" href="<?php echo $base_url;?>/all-shop-products">
        <?php
        if($days_left > 1){
//            echo $_COOKIE['topBarGreetings'].'pp';
        ?>       
        <span class="event-date"><?php echo $days_left;?> DAYS</span> <span class="event-name"><?php echo $event_name;?></span> <span class="text-after-event-name"><?php echo $text_after_event_name;?></span>
        <?php
        }else if($days_left == 1){
        ?>
        <span class="event-date"><?php echo $days_left;?> DAY</span> <span class="event-name"><?php echo $event_name;?></span> <span class="text-after-event-name"><?php echo $text_after_event_name;?></span>
        <?php
        }else{
        ?>
        <span class="event-name"><?php echo $event_name;?></span> <span class="text-after-event-name"><?php echo $text_after_event_name;?></span>
        <?php
        }
        ?> 
        </a>
    </div>
<?php
  }
}
}
?>
<header id="navbar" role="banner" class="navbar navbar-default">
    <?php
        global $user;
        if($user->uid == 0){
    ?>
        <div class="homepage-top-headertext">
            <?php
                $block_7 = block_load('block', '7');
                $output_7 = drupal_render(_block_get_renderable_array(_block_render_blocks(array($block_7))));
                print $output_7;
            ?>
        </div>
    <?php
        }
    ?>
  <div class="container">
      <div class="header-three-columns">
          <div class="header-left-side">
            
              <div class="app-store-link">
              <?php
                $block_2 = block_load('block', '2');
                $output_2 = drupal_render(_block_get_renderable_array(_block_render_blocks(array($block_2))));
                print $output_2;
              ?>
              </div>
              <?php
                if(!empty($primary_nav)){ 
              ?>
              <div class="header-primary-menu">
  
                    <?php print render($primary_nav);?>
              </div>
              <?php
               }   
              ?>          
          </div>
          <div class="header-center-side">
                <div class="navbar-header">
                  <?php if ($logo): ?>
                  <a class="logo navbar-btn" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
                    <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
                  </a>
                  <?php endif; ?>

                  <?php if (!empty($site_name)): ?>
                  <a class="name navbar-brand" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"><?php print $site_name; ?></a>
                  <?php endif; ?>

                  <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
<!--                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>-->
                </div>              
          </div>
      
            <div class="header-right-side">
                <div class="header-search-block">
                    <?php
//                           $block = module_invoke('search', 'block_view');
//                           print render($block);  
                             $block_page_1 = module_invoke('views', 'block_view', '-exp-new_wishlists-page_1');
                             print render($block_page_1['content']);                    
                    ?>
                </div>
               <?php
                    if(!empty($secondary_nav)){  
                ?>
                <div class="home-secondary-nav">
                    <?php print render($secondary_nav);?>
                </div>
                <?php
                    }
                ?>    
            </div> 

      </div>    
      
    

    <?php if (!empty($page['navigation'])): ?>
      <div class="navbar-collapse collapse">
        <nav role="navigation" class="pull-right">
          <?php if (!empty($page['navigation'])): ?>
            <?php print render($page['navigation']); ?>
          <?php endif; ?>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</header>
<div class="page-title-wrapper">
  <?php print render($title_prefix); ?>
  <?php if (!empty($title)): ?>
    <h1 class="page-header"><?php print $title; ?></h1>
  <?php endif; ?>
  <?php if ((isset($variables['title2'])) && ((!empty($variables['title2'])))): ?>
      <div class="container">
        <div class="news-title"><?php print render($variables['title2']); ?></div>
      </div>
  <?php endif; ?>
</div>

<div class="main-container container">

  <header role="banner" id="page-header">
    <?php if (!empty($site_slogan)): ?>
      <p class="lead"><?php print $site_slogan; ?></p>
    <?php endif; ?>

    <?php print render($page['header']); ?>
  </header> <!-- /#page-header -->

  <div class="row">

    <?php if (!empty($page['highlighted'])): ?>
      <div class="highlighted jumbotron"><?php print render($page['highlighted']); ?></div>
    <?php endif; ?>

    <div class="row">
      <div class="breadcrumb-wrapper">
        <?php if (!empty($checkout_progress_block)): print $checkout_progress_block; endif;?>
      </div>
      <div class="user-info-panel col-md-3 col-sm-4">
        <?php // if ($user_info_panel): print $user_info_panel; endif; ?>
      </div>
    </div>

    <?php if (!empty($page['sidebar_first'])): ?>
      <aside class="col-sm-3" role="complementary">
        <?php print render($page['sidebar_first']); ?>
      </aside>  <!-- /#sidebar-first -->
    <?php endif; ?>

    <section<?php print $content_column_class; ?>>
      <a id="main-content"></a>
      <?php print render($title_suffix); ?>
      <?php print $messages; ?>
      <?php if (!empty($tabs)): ?>
        <?php print render($tabs); ?>
      <?php endif; ?>
      <?php if (!empty($page['help'])): ?>
        <?php print render($page['help']); ?>
      <?php endif; ?>
      <?php if (!empty($action_links)): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
    </section>

    <?php if (!empty($page['sidebar_second'])): ?>
      <aside class="col-sm-3" role="complementary">
        <?php print render($page['sidebar_second']); ?>
      </aside>  <!-- /#sidebar-second -->
    <?php endif; ?>

  </div>
</div>
<footer class="footer">
  <?php print render($page['footer']); ?>
</footer>
