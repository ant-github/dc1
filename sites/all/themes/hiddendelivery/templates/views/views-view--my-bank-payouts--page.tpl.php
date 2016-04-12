<?php

/*
Important Note:
Please do not replace directrly this file with the live website file,  Please replace or change the working code

Reason:  
Different public key is used in this file, on live site there is different stripe publishable key"    
 "pk_test_4S8XotFis6j7VWvhD0nWT2Jd" on live it is different
 */


/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
global $base_url;
global $user;
if ($user->uid != 0) {
    $user_details = user_load($user->uid);

    /* get stripe coutries list to transfer ammount from user's field_stripe_account_country */
$stripe_country_list = field_info_field('field_stripe_account_country');
$stripe_country_list_options = list_allowed_values($stripe_country_list);    
if (array_key_exists($user_details->field_delivery_address['und'][0]['country'], $stripe_country_list_options)) {  
    ?>
    <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
    <!-- jQuery is used only for this example; it isn't required to use Stripe -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script type="text/javascript">
        // this identifies your website in the createToken call below
        Stripe.setPublishableKey('pk_live_Devr8oSBxu3oSaYdFwJ2xIUw');
    </script>
    <div class="user-bank-payout-forms">
        <noscript>
        <div class="bs-callout bs-callout-danger">
            <h4>JavaScript is not enabled!</h4>
            <p>This payment form requires your browser to have JavaScript enabled. Please activate JavaScript and reload this page. Check <a href="http://enable-javascript.com" target="_blank">enable-javascript.com</a> for more informations.</p>
        </div>
        </noscript>    
        <div class="bank-payout-tabs">
            <ul>
                <li class="active add-bank-account-tab">Bank Account</li>
            </ul>
        </div>

<?php
if (isset($user_details->field_stripe_payout_bank_account['und'][0]['value']) && $user_details->field_stripe_payout_bank_account['und'][0]['value'] != '') {
?>
        <div class="col-sm-12 bank-account-detail">
            <!-- if user ssn/personal_id and ID Proof not uploaded on stripe then again show these field with bank details to fill up --->
<!--
<?php 
        if($user_details->field_delivery_address['und'][0]['country'] == 'US'){
            if($user_details->field_has_id_on_stripe_account['und'][0]['value'] != 1 || $user_details->field_has_ssn_personal_on_stripe['und'][0]['value'] != 1){
?>   
                    <form id="update_model_id_info" class="ssn-upload-proof-form" enctype="multipart/form-data" action="<?php echo $base_url; ?>/update_model_id_info" method="post">  
                        <?php 
                        if ($user_details->field_has_ssn_personal_on_stripe['und'][0]['value'] != 1){ 
                        ?>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6 form-group routing-number-field">
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <div class="row">
                                                <label>Your SSN (last 4 digits only)</label>
                                                <input type="text" name="ssn_last_4" class="form-control ssn-last-4"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php                         
                        }
                        if ($user_details->field_has_id_on_stripe_account['und'][0]['value'] != 1) {
                        ?>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6 form-group verification-doc-div-main routing-number-field">
                                    <span class="id-proof-text">ID required to link with bank account</span>
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <img/>
                                            <div class="row verification-doc-div">
                                                <label>Upload ID</label>
                                                <input type="file" name="upload_verification_doc" accept=".png, .jpg, .jpeg" onChange="showpreviewproof(this)" class="form-control form-file verification-doc"/>
                                                                   
                                            </div>
                                            <span class="desc-text">(Accept only png/jpeg images.)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6"> 
                                    <div class="row">
                                        <div class="col-sm-11"> 
                                            <div class="row">
                                                <input type="submit" class="submit-button form-submit" value="Submit"/> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>            
<?php        
            } }else if($user_details->field_delivery_address['und'][0]['country'] == 'CA'){
                if($user_details->field_has_id_on_stripe_account['und'][0]['value'] != 1 || $user_details->field_has_ssn_personal_on_stripe['und'][0]['value'] != 1){
?>                          
            <form id="update_model_id_info" class="personal-id-upload-proof-form" enctype="multipart/form-data" action="<?php echo $base_url; ?>/update_model_id_info" method="post"> 
                <?php
                if ($user_details->field_has_ssn_personal_on_stripe['und'][0]['value'] != 1){ 
                ?>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6 form-group routing-number-field">
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <div class="row">
                                                <label>Personal Id Number</label>
                                                <input type="text" name="personal_id_number" class="form-control personal-id-number"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php                 
                }
                    if ($user_details->field_has_id_on_stripe_account['und'][0]['value'] != 1) {
                ?>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6 form-group verification-doc-div-main routing-number-field">
                                    <div class="row">
                                    <span class="id-proof-text">ID required to link with bank account</span>
                                        <div class="col-sm-11">
                                            <img/>
                                            <div class="row verification-doc-div">
                                                <label>Upload ID</label>
                                                <input type="file" name="upload_verification_doc" accept=".png, .jpg, .jpeg" onChange="showpreviewproof(this)" class="form-control form-file verification-doc"/>
                                                                  
                                            </div>
                                        <span class="desc-text">(Accept only png/jpeg images.)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                    }
                    ?>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6"> 
                                    <div class="row">
                                        <div class="col-sm-11"> 
                                            <div class="row">
                                                <input type="submit" class="submit-button form-submit" value="Submit"/> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
<?php               
                }  }else{
                    if($user_details->field_has_id_on_stripe_account['und'][0]['value'] != 1){
?>
                <form id="update_model_id_info" class="upload-proof-form" enctype="multipart/form-data" action="<?php echo $base_url; ?>/update_model_id_info" method="post">      
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6 form-group verification-doc-div-main routing-number-field">
                                    <div class="row">
                                    <span class="id-proof-text">ID required to link with bank account</span>
                                        <div class="col-sm-11">
                                            <img/>
                                            <div class="row verification-doc-div">
                                                <label>Upload ID</label>
                                                <input type="file" name="upload_verification_doc" accept=".png, .jpg, .jpeg" onChange="showpreviewproof(this)" class="form-control form-file verification-doc"/>
                                            </div>
                                            <span class="desc-text">(Accept only png/jpeg images.)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6"> 
                                    <div class="row">
                                        <div class="col-sm-11"> 
                                            <div class="row">
                                                <input type="submit" class="submit-button form-submit" value="Submit"/> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
<?php        
                }}
?>      
            -->
            <div class="bank-account-details-text">
             You currently have a bank account linked. Account ending with <?php echo $user_details->field_stripe_bank_account['und'][0]['value'];?>.
            </div>
            <div class="add-new-bank-account">
                <button class="add-new-bank-button">Add New</button>
            </div>            
            <div class="delete-account-bank-account">
                <a href="<?php echo $base_url;?>/dc_delete_model_account"><button class="delete-account-bank-account-button">Delete Current Account</button></a>
                <p>*Pending transfers must be completed before changing account details. If your account has pending transfers & account info is changed mid-transfer it will continute to the orginal account.</p>
            </div>            
        </div>
        <div class="col-sm-12 add-bank-account-form" style="display: none;">
<?php }else{ ?>
        <div class="col-sm-12 add-bank-account-form">
<?php } ?>       
        
            <div class="form-fields">
                <span class="payment-errors"></span>
                <?php
//                if (isset($user_details->field_bank_payout_account_id['und'][0]['value']) && $user_details->field_bank_payout_account_id['und'][0]['value'] != '') {
                    $country_value = $user_details->field_delivery_address['und'][0]['country'];
                    if ($country_value == 'US' || $country_value == 'CA' || $country_value == 'AU') {

                        if ($country_value == 'AU') {
                            $currency_value = 'AUD';
                        } else {
                            $currency_value = 'USD';
                        }
                        ?> 
                        <script type="text/javascript">
                            //            Stripe.setPublishableKey('pk_test_4S8XotFis6j7VWvhD0nWT2Jd');
                            function stripeResponseHandlerAcc(status, response) {
                                if (response.error) {
                                    // re-enable the submit button
                                    $('.submit-button').removeAttr("disabled");
                                    // show the errors on the form
                                    $(".payment-errors").css("display", "block");
                                    $(".payment-errors").css("color", "#b94a48");
                                    $(".payment-errors").css("padding-left", "10px");
                                    $(".payment-errors").css("background", "#f2dede");
                                    $(".payment-errors").html(response.error.message);
                                } else {
                                    var form$ = $("#model-add-bank-details-form");
                                    // token contains id, last4, and card type
                                    var token = response['id'];
                                    // insert the token into the form so it gets submitted to the server
                                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                                    // and submit
                                    form$.get(0).submit();
                                }
                            }
                            $(document).ready(function () {
                                $("#model-add-bank-details-form").submit(function (event) {
                                    
                                if($('#model-add-bank-details-form .ssn-last-4').length){
                                    var user_ssn_4 = $('#model-add-bank-details-form .ssn-last-4').val();
                                    if(user_ssn_4 == ''){
                                        $(".payment-errors").css("display", "block");
                                        $(".payment-errors").css("color", "#b94a48");
                                        $(".payment-errors").css("padding-left", "10px");
                                        $(".payment-errors").css("background", "#f2dede");
                                        $(".payment-errors").html('Please enter your SSN last 4 characters.');
                                        $('html,body').animate({'scrollTop' : 200},1000);
                                        return false;
                                    }                                     
                                }                                    
                                if($('#model-add-bank-details-form .personal-id-number').length){
                                    var user_personal_id_number = $('#model-add-bank-details-form .personal-id-number').val();
                                    if(user_personal_id_number == ''){
                                        $(".payment-errors").css("display", "block");
                                        $(".payment-errors").css("color", "#b94a48");
                                        $(".payment-errors").css("padding-left", "10px");
                                        $(".payment-errors").css("background", "#f2dede");
                                        $(".payment-errors").html('Please enter your Personal Id Number.');
                                        $('html,body').animate({'scrollTop' : 200},1000);
                                        return false;
                                    }                                    
                                }                                    
                                if($('#model-add-bank-details-form .verification-doc').length){
                                    var user_verification_doc = $('#model-add-bank-details-form .verification-doc').val();
                                    if(user_verification_doc == ''){
                                        $(".payment-errors").css("display", "block");
                                        $(".payment-errors").css("color", "#b94a48");
                                        $(".payment-errors").css("padding-left", "10px");
                                        $(".payment-errors").css("background", "#f2dede");
                                        $(".payment-errors").html('Please upload your identity proof document.');
                                        $('html,body').animate({'scrollTop' : 200},1000);
                                        return false;
                                    }                                    
                                } 
                               
                                    // disable the submit button to prevent repeated clicks
                                    $('.submit-button').attr("disabled", "disabled");
                                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                                    Stripe.bankAccount.createToken({
                                        country: '<?php echo $country_value; ?>',
                                        currency: '<?php echo $currency_value; ?>',
                                        routing_number: $('.routing-number').val(),
                                        account_number: $('.account-number').val(),
                                        account_holder_name: $('.account-holder-name').val()
                                    }, stripeResponseHandlerAcc);
                                    return false; // submit from callback
                                });
                            });
                        </script>            
                        <form id="model-add-bank-details-form" enctype="multipart/form-data" action="<?php echo $base_url; ?>/dc_add_bank_details" method="post">
                            <div class="col-sm-12 clearfix">
<!--                                <div class="form-heading">
                                    <span>Add New Bank Account</span>
                                </div>-->
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6 account-number-field">
                                            <div class="row">
                                                <div class="col-sm-11">
                                                    <div class="row">
                                                        <label>Full Legal Name On Account</label>
                                                        <input type="text" name="accountHolderName" class="form-control account-holder-name"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 bank-country-field">
                                            <div class="row">
                                                <div class="col-sm-11">
                                                    <div class="row">        
                                                        <label>Your Bank Account Country</label>
                                                        <select class="form-control bank-country" disabled="disabled">
                                                            
                <?php
                    foreach($stripe_country_list_options AS $key=>$values){
                ?>
                    <option value="<?php echo $key;?>" <?php if ($country_value == $key) {echo "selected=selected";}?>><?php echo $values;?></option>
                <?php
                    }
                ?>  
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="row">

                                        <div class="col-sm-6 account-number-field">
                                            <div class="row">
                                                <div class="col-sm-11">
                                                    <div class="row">
                                                        <label>Account Number</label>
                                                        <input type="text" name="accountNumber" class="form-control account-number"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 form-group routing-number-field">
                                            <div class="row">
                                                <div class="col-sm-11">
                                                    <div class="row">
                                                        <label>Routing Number</label>
                                                        <input type="text" name="routingNumber" class="form-control routing-number"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        </div>
                                </div>
                        <div class="col-sm-12">
                            <div class="row">

<?php
                if ($user_details->field_has_ssn_personal_on_stripe['und'][0]['value'] != 1 && $country_value =='CA'){ 
                ?>

                                <div class="col-sm-6 form-group routing-number-field">
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <div class="row">
                                                <label>Personal Id Number</label>
                                                <input type="text" name="personal_id_number" class="form-control personal-id-number"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                <?php                 
                }
                if ($user_details->field_has_ssn_personal_on_stripe['und'][0]['value'] != 1 && $country_value =='US'){ 
                ?>

                                <div class="col-sm-6 form-group routing-number-field">
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <div class="row">
                                                <label>Your SSN (last 4 digits only)</label>
                                                <input type="text" name="ssn_last_4" class="form-control ssn-last-4"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                <?php                 
                }
                ?>
                            </div>
                        </div> 
                        <div class="col-sm-12">
                            <div class="row">
                <?php
                    if ($user_details->field_has_id_on_stripe_account['und'][0]['value'] != 1) {
                ?>
                                <div class="col-sm-6 form-group verification-doc-div-main routing-number-field">
                                    <div class="row">
                                        <span class="id-proof-text">ID required to link with bank account</span>
                                        <div class="col-sm-11">
                                            <img/>
                                            <div class="row verification-doc-div">
                                                <label>Upload ID</label>
                                                <input type="file" name="upload_verification_doc" accept=".png, .jpg, .jpeg" onChange="showpreviewproof(this)" class="form-control form-file verification-doc"/>
                                                
                                            </div>
                                            <span class="desc-text">(Accept only png/jpeg images.)</span>
                                        </div>
                                    </div>
                                </div>

                    <?php 
                    }
                    ?>
                            </div>
                        </div>                                
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6"> 
                                                    <div class="row">
                                                        <div class="col-sm-11"> 
                                                            <div class="row">                                
                                                        <input type="submit" class="submit-button form-submit" value="Submit"/> 
                                                    </div>
                                                </div>
                                            </div>                                
                                        </div>  
                                        </div>  
                                        </div>     
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php
                    } else {
                        ?>
                        <script type="text/javascript">
                            //            Stripe.setPublishableKey('pk_test_4S8XotFis6j7VWvhD0nWT2Jd');
                            function stripeResponseHandlerAcc(status, response) {
                                if (response.error) {
                                    // re-enable the submit button
                                    $('.form-submit').removeAttr("disabled");
                                    // show the errors on the form
                                    $(".payment-errors").css("display", "block");
                                    $(".payment-errors").css("color", "#b94a48");
                                    $(".payment-errors").css("padding-left", "10px");
                                    $(".payment-errors").css("background", "#f2dede");
                                    $(".payment-errors").html(response.error.message);
                                } else {
                                    var form$ = $("#model-add-bank-details-iban-form");
                                    // token contains id, last4, and card type
                                    var token = response['id'];
                                    // insert the token into the form so it gets submitted to the server
                                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                                    // and submit
                                    form$.get(0).submit();
                                }
                            }
                            $(document).ready(function () {
                                $("#model-add-bank-details-iban-form").submit(function (event) {
                                    
                                if($('#model-add-bank-details-iban-form .verification-doc').length){
                                    var user_verification_doc = $('#model-add-bank-details-iban-form .verification-doc').val();
                                    if(user_verification_doc == ''){
                                        $(".payment-errors").css("display", "block");
                                        $(".payment-errors").css("color", "#b94a48");
                                        $(".payment-errors").css("padding-left", "10px");
                                        $(".payment-errors").css("background", "#f2dede");
                                        $(".payment-errors").html('Please upload your identity proof document.');
                                        $('html,body').animate({'scrollTop' : 200},1000);
                                        return false;
                                    }                                    
                                }                                     
                                    // disable the submit button to prevent repeated clicks
                                    $('.submit-button').attr("disabled", "disabled");
                                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                                    Stripe.bankAccount.createToken({
                                        country: '<?php echo $country_value; ?>',
                                        currency: $('.bank-currency-iban').val(),
                                        account_number: $('.account-number-iban').val(),
                                        account_holder_name: $('.account-holder-name').val()
                                    }, stripeResponseHandlerAcc);
                                    return false; // submit from callback
                                });
                            });
                        </script>
                        <form id="model-add-bank-details-iban-form" enctype="multipart/form-data" action="<?php echo $base_url; ?>/dc_add_bank_details" method="post">
                            <div class="col-sm-12 clearfix">
<!--                                <div class="form-heading">
                                    <label>Add New Bank Account</label>
                                </div>-->
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6 bank-country-field">
                                            <div class="row">
                                                <div class="col-sm-11">
                                                    <div class="row">

                                                        <label>Your Bank Account Country</label>
                                                        <select class="form-control bank-country-iban" disabled="disabled">
                <?php
                    foreach($stripe_country_list_options AS $key=>$values){
                ?>
                    <option value="<?php echo $key;?>" <?php if ($country_value == $key) {echo "selected=selected";}?>><?php echo $values;?></option>
                <?php
                    }
                ?>                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 form-group bank-currency-field">
                                            <div class="row">
                                                <div class="col-sm-11">
                                                    <div class="row">
                                                        <label>Select Currency</label>
                                                        <select class="form-control bank-currency-iban">
                                                            <option value="">Select your bank account currency</option> 
                                                            <option value="AED">United Arab Emirates dirham</option> 
                                                            <option value="AFN">Afghan afghani</option> 
                                                            <option value="ALL">Albanian lek</option> 
                                                            <option value="AMD">Armenian dram</option> 
                                                            <option value="AOA">Angolan kwanza</option> 
                                                            <option value="ARS">Argentine peso</option> 
                                                            <option value="AUD">Australian dollar</option> 
                                                            <option value="AWG">Aruban florin</option> 
                                                            <option value="AZN">Azerbaijani manat</option> 
                                                            <option value="BAM">Bosnia and Herzegovina convertible mark</option> 
                                                            <option value="BBD">Barbadian dollar</option> 
                                                            <option value="BDT">Bangladeshi taka</option> 
                                                            <option value="BGN">Bulgarian lev</option> 
                                                            <option value="BHD">Bahraini dinar</option> 
                                                            <option value="BIF">Burundian franc</option> 
                                                            <option value="BMD">Bermudian dollar</option> 
                                                            <option value="BND">Brunei dollar</option> 
                                                            <option value="BOB">Bolivian boliviano</option> 
                                                            <option value="BRL">Brazilian real</option> 
                                                            <option value="BSD">Bahamian dollar</option> 
                                                            <option value="BTN">Bhutanese ngultrum</option> 
                                                            <option value="BWP">Botswana pula</option> 
                                                            <option value="BYR">Belarusian ruble</option> 
                                                            <option value="BZD">Belize dollar</option> 
                                                            <option value="CAD">Canadian dollar</option> 
                                                            <option value="CDF">Congolese franc</option> 
                                                            <option value="CHF">Swiss franc</option> 
                                                            <option value="CLP">Chilean peso</option> 
                                                            <option value="CNY">Chinese yuan</option> 
                                                            <option value="COP">Colombian peso</option> 
                                                            <option value="CRC">Costa Rican colón</option> 
                                                            <option value="CUP">Cuban convertible peso</option> 
                                                            <option value="CVE">Cape Verdean escudo</option> 
                                                            <option value="CZK">Czech koruna</option> 
                                                            <option value="DJF">Djiboutian franc</option> 
                                                            <option value="DKK">Danish krone</option> 
                                                            <option value="DOP">Dominican peso</option> 
                                                            <option value="DZD">Algerian dinar</option> 
                                                            <option value="EGP">Egyptian pound</option> 
                                                            <option value="ERN">Eritrean nakfa</option> 
                                                            <option value="ETB">Ethiopian birr</option> 
                                                            <option value="EUR">Euro</option> 
                                                            <option value="FJD">Fijian dollar</option> 
                                                            <option value="FKP">Falkland Islands pound</option> 
                                                            <option value="GBP">British pound</option> 
                                                            <option value="GEL">Georgian lari</option> 
                                                            <option value="GHS">Ghana cedi</option> 
                                                            <option value="GMD">Gambian dalasi</option> 
                                                            <option value="GNF">Guinean franc</option> 
                                                            <option value="GTQ">Guatemalan quetzal</option> 
                                                            <option value="GYD">Guyanese dollar</option> 
                                                            <option value="HKD">Hong Kong dollar</option> 
                                                            <option value="HNL">Honduran lempira</option> 
                                                            <option value="HRK">Croatian kuna</option> 
                                                            <option value="HTG">Haitian gourde</option> 
                                                            <option value="HUF">Hungarian forint</option> 
                                                            <option value="IDR">Indonesian rupiah</option> 
                                                            <option value="ILS">Israeli new shekel</option> 
                                                            <option value="IMP">Manx pound</option> 
                                                            <option value="INR">Indian rupee</option> 
                                                            <option value="IQD">Iraqi dinar</option> 
                                                            <option value="IRR">Iranian rial</option> 
                                                            <option value="ISK">Icelandic króna</option> 
                                                            <option value="JEP">Jersey pound</option> 
                                                            <option value="JMD">Jamaican dollar</option> 
                                                            <option value="JOD">Jordanian dinar</option> 
                                                            <option value="JPY">Japanese yen</option> 
                                                            <option value="KES">Kenyan shilling</option> 
                                                            <option value="KGS">Kyrgyzstani som</option> 
                                                            <option value="KHR">Cambodian riel</option> 
                                                            <option value="KMF">Comorian franc</option> 
                                                            <option value="KPW">North Korean won</option> 
                                                            <option value="KRW">South Korean won</option> 
                                                            <option value="KWD">Kuwaiti dinar</option> 
                                                            <option value="KYD">Cayman Islands dollar</option> 
                                                            <option value="KZT">Kazakhstani tenge</option> 
                                                            <option value="LAK">Lao kip</option> 
                                                            <option value="LBP">Lebanese pound</option> 
                                                            <option value="LKR">Sri Lankan rupee</option> 
                                                            <option value="LRD">Liberian dollar</option> 
                                                            <option value="LSL">Lesotho loti</option> 
                                                            <option value="LTL">Lithuanian litas</option> 
                                                            <option value="LVL">Latvian lats</option> 
                                                            <option value="LYD">Libyan dinar</option> 
                                                            <option value="MAD">Moroccan dirham</option> 
                                                            <option value="MDL">Moldovan leu</option> 
                                                            <option value="MGA">Malagasy ariary</option> 
                                                            <option value="MKD">Macedonian denar</option> 
                                                            <option value="MMK">Burmese kyat</option> 
                                                            <option value="MNT">Mongolian tögrög</option> 
                                                            <option value="MOP">Macanese pataca</option> 
                                                            <option value="MRO">Mauritanian ouguiya</option> 
                                                            <option value="MUR">Mauritian rupee</option> 
                                                            <option value="MVR">Maldivian rufiyaa</option> 
                                                            <option value="MWK">Malawian kwacha</option> 
                                                            <option value="MXN">Mexican peso</option> 
                                                            <option value="MYR">Malaysian ringgit</option> 
                                                            <option value="MZN">Mozambican metical</option> 
                                                            <option value="NAD">Namibian dollar</option> 
                                                            <option value="NGN">Nigerian naira</option> 
                                                            <option value="NIO">Nicaraguan cordoba</option> 
                                                            <option value="NOK">Norwegian krone</option> 
                                                            <option value="NPR">Nepalese rupee</option> 
                                                            <option value="NZD">New Zealand dollar</option> 
                                                            <option value="OMR">Omani rial</option> 
                                                            <option value="PAB">Panamanian balboa</option> 
                                                            <option value="PEN">Peruvian nuevo sol</option> 
                                                            <option value="PGK">Papua New Guinean kina</option> 
                                                            <option value="PHP">Philippine peso</option> 
                                                            <option value="PKR">Pakistani rupee</option> 
                                                            <option value="PLN">Polish złoty</option> 
                                                            <option value="PRB">Transnistrian ruble</option> 
                                                            <option value="PYG">Paraguayan guaraní</option> 
                                                            <option value="QAR">Qatari riyal</option> 
                                                            <option value="RON">Romanian leu</option> 
                                                            <option value="RSD">Serbian dinar</option> 
                                                            <option value="RUB">Russian ruble</option> 
                                                            <option value="RWF">Rwandan franc</option> 
                                                            <option value="SAR">Saudi riyal</option> 
                                                            <option value="SBD">Solomon Islands dollar</option> 
                                                            <option value="SCR">Seychellois rupee</option> 
                                                            <option value="SDG">Singapore dollar</option> 
                                                            <option value="SEK">Swedish krona</option> 
                                                            <option value="SGD">Singapore dollar</option> 
                                                            <option value="SHP">Saint Helena pound</option> 
                                                            <option value="SLL">Sierra Leonean leone</option> 
                                                            <option value="SOS">Somali shilling</option> 
                                                            <option value="SRD">Surinamese dollar</option> 
                                                            <option value="SSP">South Sudanese pound</option> 
                                                            <option value="STD">Sao Tome and Principe dobra</option> 
                                                            <option value="SVC">Salvadoran colon</option> 
                                                            <option value="SYP">Syrian pound</option> 
                                                            <option value="SZL">Swazi lilangeni</option> 
                                                            <option value="THB">Thai baht</option> 
                                                            <option value="TJS">Tajikistani somoni</option> 
                                                            <option value="TMT">Turkmenistan manat</option> 
                                                            <option value="TND">Tunisian dinar</option> 
                                                            <option value="TOP">Tongan paanga</option> 
                                                            <option value="TRY">Turkish lira</option> 
                                                            <option value="TTD">Trinidad and Tobago dollar</option> 
                                                            <option value="TWD">New Taiwan dollar</option> 
                                                            <option value="TZS">Tanzanian shilling</option> 
                                                            <option value="UAH">Ukrainian hryvnia</option> 
                                                            <option value="UGX">Ugandan shilling</option> 
                                                            <option value="USD">United States dollar</option> 
                                                            <option value="UYU">Uruguayan peso</option> 
                                                            <option value="UZS">Uzbekistani som</option> 
                                                            <option value="VEF">Venezuelan bolívar</option> 
                                                            <option value="VND">Vietnamese dong</option> 
                                                            <option value="VUV">Vanuatu vatu</option> 
                                                            <option value="WST">Samoan tala</option> 
                                                            <option value="XAF">Central African CFA franc</option> 
                                                            <option value="XCD">East Caribbean dollar</option> 
                                                            <option value="XOF">West African CFA franc</option> 
                                                            <option value="XPF">CFP franc</option> 
                                                            <option value="YER">Yemeni rial</option> 
                                                            <option value="ZAR">South African rand</option> 
                                                            <option value="ZMW">Zambian kwacha</option> 
                                                            <option value="ZWL">Zimbabwean dollar</option>  
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6 account-number-field">
                                                    <div class="row">
                                                        <div class="col-sm-11">
                                                            <div class="row">
                                                                <label>Full Legal Name On Account</label>
                                                                <input type="text" name="accountHolderName" class="form-control account-holder-name"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-sm-6 account-number-field">
                                                    <div class="row">
                                                        <div class="col-sm-11">
                                                            <div class="row">
                                                                <label>Account Number (IBAN)</label>
                                                                <input type="text" name="accountNumberIban" class="form-control account-number-iban"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    <?php                 
                                        if ($user_details->field_has_id_on_stripe_account['und'][0]['value'] != 1) {
                                    ?>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group verification-doc-div-main routing-number-field">
                                                        <div class="row">
                                                            <span class="id-proof-text">ID required to link with bank account</span>
                                                            <div class="col-sm-11">
                                                                <img/>
                                                                <div class="row verification-doc-div">
                                                                    <label>Upload ID</label>
                                                                    <input type="file" name="upload_verification_doc" accept=".png, .jpg, .jpeg" onChange="showpreviewproof(this)" class="form-control form-file verification-doc"/>
                                                               
                                                                    
                                                                </div>
                                                                <span class="desc-text">(Accept only png/jpeg images.)</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php 
                                        }
                                        ?>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6"> 
                                                    <div class="row">
                                                        <div class="col-sm-11"> 
                                                            <div class="row">

                                                                <input type="submit" class="submit-button form-submit" value="Submit"/> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </div>

                        </form>
                        <?php
                    }
                ?>            
            </div>
        </div>
    </div>
    <div class="model-payout-section">
        <div class="form-heading">
            <span>Payouts</span>
        </div>
        <?php echo views_embed_view('model_s_payout_view', 'block'); ?>
    </div>        
    <?php
}else{
?>
    <div class="model-bank-payout-section">
        <div class="view view-model-s-payout-view view-id-model_s_payout_view view-display-id-block view-dom-id-8101f148731275bb8429a2d663138d3d">
            <div class="view-empty">
              <p>This option is currently not available for your country.</p>
            </div>
        </div>
    </div>     
<?php    
}
}
?>
<script type="text/javascript">
    function showpreviewproof(input) {
       jQuery(".verification-doc-div-main img").attr("width", "100");
       jQuery(".verification-doc-div-main .desc-text").css("display", "none");
       jQuery(".verification-doc-div-main img").attr("height", "70");
        if (input.files && input.files[0]) {
            jQuery('.file1_proofformError').css('display', 'none');

                var ImageDir = new FileReader();
                ImageDir.onload = function (e) {
                         jQuery('.verification-doc-div-main img').attr('src', e.target.result);
                }
                ImageDir.readAsDataURL(input.files[0]);  
        }
    }
</script>