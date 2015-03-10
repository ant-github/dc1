<?php

/**
 * @file
 * Default theme implementation to present all user profile data.
 *
 * This template is used when viewing a registered member's profile page,
 * e.g., example.com/user/123. 123 being the users ID.
 *
 * Use render($user_profile) to print all profile items, or print a subset
 * such as render($user_profile['user_picture']). Always call
 * render($user_profile) at the end in order to print all remaining items. If
 * the item is a category, it will contain all its profile items. By default,
 * $user_profile['summary'] is provided, which contains data on the user's
 * history. Other data can be included by modules. $user_profile['user_picture']
 * is available for showing the account picture.
 *
 * Available variables:
 *   - $user_profile: An array of profile items. Use render() to print them.
 *   - Field variables: for each field instance attached to the user a
 *     corresponding variable is defined; e.g., $account->field_example has a
 *     variable $field_example defined. When needing to access a field's raw
 *     values, developers/themers are strongly encouraged to use these
 *     variables. Otherwise they will have to explicitly specify the desired
 *     language negotiation rule that was previously applied.
 *     field language, e.g. $account->field_example['en'], thus overriding any
 *
 * @see user-profile-category.tpl.php
 *   Where the html is handled for the group.
 * @see user-profile-item.tpl.php
 *   Where the html is handled for each item in the group.
 * @see template_preprocess_user_profile()
 *
 * @ingroup themeable
 */
?>

<div class="profile"<?php print $attributes; ?>>
<div class="row">
<div class="col-md-6">
<?php print render($user_profile['group_personal_information']);?>
</div>
<div class="col-md-6">
    <h3 class="profile-header">Gift Balance</h3> 
    <div class="gift-balance-user">
    	
    <?php
      if(isset($user_profile['group_personal_information']['field_name']['#object']->field_gift_balance_usd['und'][0]['value']) && $user_profile['group_personal_information']['field_name']['#object']->field_gift_balance_usd['und'][0]['value'] != ''){
    			echo '<div class="change-currency-user"><select id="userProfileCurrency"><option value="usd" selected="selected">USD</option><option value="gbp">GBP</option><option value="eur">EUR</option></select></div><p>$'.$user_profile['group_personal_information']['field_name']['#object']->field_gift_balance_usd['und'][0]['value'].'</p>';
    		}else{
    			echo "$0";
    		}  
    ?> 
    </div>
<?php print render($user_profile['user_picture']);?>
<?php print render($user_profile['group_profile_information']);?>
<?php print render($user_profile['group_size_guide']);?>
<div>
</div>
