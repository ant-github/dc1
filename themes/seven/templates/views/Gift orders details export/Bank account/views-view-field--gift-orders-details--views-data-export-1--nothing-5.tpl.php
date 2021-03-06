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

if($row->field_field_model_redeemed_as[0]['raw']['value'] == 2){

    if($row->field_field_gift_currency[0]['raw']['value'] == 'gbp'){
        $currency_sign = '£';
        echo number_format(($row->field_field_gift_amount_transfer[0]['raw']['value']), 2);
    }elseif($row->field_field_gift_currency[0]['raw']['value'] == 'eur'){        
        if($row->field_field_gross_amount_currency[0]['raw']['value'] == 'gbp' || $row->field_field_gross_amount[0]['raw']['value'] != ''){
            $currency_sign = '£'; 
            echo number_format(($row->field_field_gift_amount_transfer[0]['raw']['value']), 2);
        }             
    }
}

?>