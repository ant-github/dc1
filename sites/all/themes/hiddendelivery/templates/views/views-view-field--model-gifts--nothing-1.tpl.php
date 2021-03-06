<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */
?>
<?php 
if((isset($row->_field_data['nid']['entity']->field_hold_gift['und'][0]['value']) && $row->_field_data['nid']['entity']->field_hold_gift['und'][0]['value'] == 'yes') || (isset($row->_field_data['nid']['entity']->field_manually_hold_gift['und'][0]['value']) && $row->_field_data['nid']['entity']->field_manually_hold_gift['und'][0]['value'] == 'yes')){
    print "<p style='width:156px;'>Admin verification, please allow 24-48hrs for this to clear.</p>";
}else{
    if($row->field_field_model_redeem[0]['raw']['value'] == 0){
        print $output;
    }else{
       print $row->field_field_gift_message[0]['raw']['value']; 
    }
}
?>