<?php
/**
 * @file
 * Default theme implementation for entities.
 *
 * Available variables:
 * - $content: An array of comment items. Use render($content) to print them all, or
 *   print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $title: The (sanitized) entity label.
 * - $url: Direct url of the current entity if specified.
 * - $page: Flag for the full page state.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. By default the following classes are available, where
 *   the parts enclosed by {} are replaced by the appropriate values:
 *   - entity-{ENTITY_TYPE}
 *   - {ENTITY_TYPE}-{BUNDLE}
 *
 * Other variables:
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 *
 * @see template_preprocess()
 * @see template_preprocess_entity()
 * @see template_process()
 */
global $user;
?>
<div class="<?php print $classes . ' ' . $status_class; ?> clearfix row"<?php print $attributes; ?>>
         <?php if ($user->uid !=0){ ?>
        <div class="product-info col-md-9">
         <?php }else{
         ?>
        <div class="product-info col-md-10">
        <?php }?>    
    
        <?php if ($status == 'reserved'): ?>
            <div class="reserved-message"><p><?php print t('Product Purchased'); ?></p></div>
        <?php endif; ?>

        <?php if ($status == 'purchased'): ?>
            <div class="purchased-message"><p><?php print t('Product Shipped'); ?></p></div>
        <?php endif; ?>
        <?php
        //print "<pre>"; print_r($content); die();
        global $user;
        global $base_url;
        ?>
        <?php print render($content['product:field_product_image']); ?>
        <?php print render($content['product:title']); ?>
        <?php print render($content['product:field_info']); ?>
        <?php print render($content['product:commerce_price']); ?>
        <?php if (isset($disclaimer)): ?>
            <?php print render($disclaimer); ?>
        <?php endif; ?>
        <?php if (isset($note)): ?>

            <?php print render($note); ?>
        <?php endif; ?>
      
    </div>
         <?php if ($user->uid !=0){ ?>
        <div class="actions col-md-3">
         <?php }else{
         ?>
        <div class="actions col-md-2">
        <?php }?> 
    
        <div class="actions col-md-10">
            <?php if (!$is_owner): ?>
                <?php 
            $product_price_unit = $content['product:commerce_price']['#object']->commerce_price['und'][0]['currency_code'];
            $product_price = $content['product:commerce_price']['#object']->commerce_price['und'][0]['amount'];
            $product_price_approve_status = $content['product:commerce_price']['#object']->field_approve_price['und'][0]['value'];
            $prince_length = strlen($product_price);
//                    print "<pre>"; print_r($product_price); die();            
                    if (($product_price_unit != 'EUR' && $product_price_unit != 'GBP' && $product_price_unit != 'USD') || ($prince_length >= 8 && $product_price_approve_status == '')){
                
                    }else{
                            print render($content['field_commerce_produc_ref']);  
                    } 
                ?>
                <?php
                global $user;
                global $base_url;
                if (isset($user->roles[5])) {
                    print "<div class='admin-edit-link-product'><a href='" . $base_url . "/admin/commerce/products/" . render($content['field_commerce_produc_ref']['#items'][0]['product_id']) . "/edit' target='_blank'>Edit</a></div><br/><br/>";
                   print render($remove_button); 
                }
                ?>

                <?php print render($wishlist_item_popup); ?>
            <?php endif; ?>

            <?php if ($is_owner): ?>
                <div class="social-links">
                    <?php print $share_links['facebook']; ?>
                    <?php print $share_links['twitter']; ?>
                    <?php print $share_links['email']; ?>
                </div>
                <?php print render($remove_button); 
            
            $product_price_unit = $content['product:commerce_price']['#object']->commerce_price['und'][0]['currency_code'];
            $product_price = $content['product:commerce_price']['#object']->commerce_price['und'][0]['amount'];
            $product_price_approve_status = $content['product:commerce_price']['#object']->field_approve_price['und'][0]['value'];
            $prince_length = strlen($product_price);
//                    print "<pre>"; print_r($product_price); die();            
                    if (($product_price_unit != 'EUR' && $product_price_unit != 'GBP' && $product_price_unit != 'USD') || ($prince_length >= 8 && $product_price_approve_status == '')){
                           // print "<pre>"; print_r($content); die();
            }else{
            ?>
                <div class="buy-button-with-remove">
                    <?php print render($content['field_commerce_produc_ref']); ?>
                </div>                
            <?php    
            }
        ?>

            <?php endif; ?>
        </div>
        <div class="purchase-info">
            <?php print render($purchase_info); ?>
        </div>
    </div>

</div>
