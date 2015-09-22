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
?>
<div class="<?php print $classes . ' ' . $status_class; ?> clearfix row"<?php print $attributes; ?>>
    <div class="product-info col-md-9">
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
        if (!isset($user->roles[5])) {
            $product_price_unit = $content['product:commerce_price']['#object']->commerce_price['und'][0]['currency_code'];
            if ($product_price_unit != 'EUR' && $product_price_unit != 'GBP' && $product_price_unit != 'USD'):
                ?>  
                <div class="reserved-message"><p><?php print t('Admin Approval Required To Purchase'); ?></p></div>	
                <?php
            endif;
        }
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
        <?php if ($is_owner): ?>
            <?php //if ($status == 'available'): ?>
                <div class="field" style="z-index: 100; margin-top: 15px; position: relative;">                                       
                    <?php
                    $check_status = db_query("SELECT field_my_widget_value_value FROM field_data_field_my_widget_value WHERE entity_id=" . $content['field_commerce_produc_ref']['#object']->wishlist_item_id);
                    
                    foreach ($check_status AS $status) {
                        $status_exist = $status->field_my_widget_value_value;
                    }
                    if (isset($status_exist) && $status_exist == 1):
                        ?>                                        
                        <input checked="checked" type="checkbox" name="addToDcWidgets" class="addToDcWidgets" value="<?php print render($content['field_commerce_produc_ref']['#object']->wishlist_item_id); ?>"  style="position: relative; top: -2px; float: left; margin-right: 10px;"/>
                    <?php endif; ?>
                    <?php if (isset($status_exist) && $status_exist == 0): ?> 
                        <input type="checkbox" name="addToDcWidgets" class="addToDcWidgets" value="<?php print render($content['field_commerce_produc_ref']['#object']->wishlist_item_id); ?>"  style="position: relative; top: -2px; float: left; margin-right: 10px;"/>                                         
                    <?php endif; ?>
                    <?php if (!isset($status_exist)): ?> 
                        <input type="checkbox" name="addToDcWidgets" class="addToDcWidgets" value="<?php print render($content['field_commerce_produc_ref']['#object']->wishlist_item_id); ?>"  style="position: relative; top: -2px; float: left; margin-right: 10px;"/>
                    <?php endif; ?>
                        <div class="field-label">Add to widget</div> 
            </div>                        
                <?php //endif; ?>
        <?php endif; ?>



    </div>

    <div class="actions col-md-3">
        <div class="actions col-md-10">
            <?php if (!$is_owner): ?>
                <?php print render($content['field_commerce_produc_ref']); ?>
                <?php
                global $user;
                global $base_url;
                if (isset($user->roles[5])) {
                    print "<div class='admin-edit-link-product'><a href='" . $base_url . "/admin/commerce/products/" . render($content['field_commerce_produc_ref']['#items'][0]['product_id']) . "/edit' target='_blank'>Edit</a></div>";
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
                <?php print render($remove_button); ?>
                <div class="buy-button-with-remove">
                    <?php print render($content['field_commerce_produc_ref']); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="purchase-info">
            <?php print render($purchase_info); ?>
        </div>
    </div>

</div>
