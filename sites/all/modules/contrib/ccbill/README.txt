
CCBill for Drupal 7.x
---------------------
CCBill is a service that handles credit card transactions instead of the website itself having to deal with any financial information. The CCBill module provides an API that allows you to integrate your Drupal site with CCBill.


Installation
------------
To install the CCBill module, copy it to the modules directory for your site and visit the Module administration page ('admin/build/modules') to enable it.


Setup
-----
1. Pass Drupal User ID to CCBill
In order for this module to work properly, you need to pass the Drupal User ID to CCBill. Here's an example of a standard link to a CCBill form (using 111111 as the test account number)
https://bill.ccbill.com/jpost/signup.cgi?&clientAccnum=111111&clientSubacc=0001&formName=111111-0003cc-1&language=English&allowedTypes=0000000445%3A840%2C0000000214%3A840%2C0000001098%3A840&subscriptionTypeId=0000000214%3A840

Using your own script, add a variable called 'drupalUserId' to the link. Here's an example for the User Id 13.
https://bill.ccbill.com/jpost/signup.cgi?&clientAccnum=111111&clientSubacc=0001&formName=111111-0003cc-1&language=English&allowedTypes=0000000445%3A840%2C0000000214%3A840%2C0000001098%3A840&subscriptionTypeId=0000000214%3A840&drupalUserId=13

CCBill will pass back any custom variables added to the link. The variable 'drupalUserId' is required to match a CCBill subscription to a Drupal user.

2. Configure CCBill
In order for this module to work properly, it needs to use CCBill's Background Post System. You can configure these settings in your CCBill admin panel. To do so, log in to your admin panel at https://webadmin.ccbill.com

2.1 Disable User Management System
By default, CCBill is managing users for you. Because Drupal has its own user system, this needs to be disabled. In the CCBill Webmaster Admin, go to Tools > Account Admin > User Management. Click the button to turn User Management "off". Under "Username Settings", select "Do Not Collect Usernames and Passwords".

2.2 Configure Background Post
When a user successfully signs up with CCBill, CCBill has the option to pass back data to your site. (Note: This is NOT the approval page a user gets sent to after a successful CCBill signup, it's only a URL to post back information).
Go to Tools > Account Administration > Advanced. The only setting you need to fill in is the "Approval Post URL" in the following format: http://www.yourwebsite.com/ccbill


Rules Integration
-----------------
This module integrates with the Rules module (http://drupal.org/project/rules). The module provides 2 events:
- User's CCBill subscription started
- User's CCBill subscription expired (only gets triggered when DataLink is setup correctly)

Use Case: When a user signs up for a new subscription on CCBill, the Drupal system adds a new role (and thus a new set of permissions) to the acting user. Useful for premium signups.


Example Rules Setup (New CCBill Subscription)
---------------------------------------------
- Create a new triggered rule here (admin/rules/trigger/add)
  Label: Change role after CCBill signup
  Event: User's CCBill subscription started
- Add an Action
  Select an action to add: Add user role
- Edit action Add User Role:
  Select role(s) you want to add to the user
- Save rule and activate


Hooks
-----
The CCBill module provides a hook called hook_ccbill. This hook gets called when a new subscription is added or an existing subscription expires:
hook_ccbill($op, $ccbill)

Currently available options for $op:
$op = 'add'   | New CCBill subscription is added
$op = 'expire | Existing subscription expired (only gets called if DataLink is active)

$ccbill is an array containing the following variables:
referring_url    | The URL that the user came from before signing up on CCBill (this would be your domain)
sub_id           | The subscription ID
start_date       | The start date of the subscription
initial_period   | The initial active period of the subscription in days
initial_price    | The price paid for the initial period
recurring_period | The number of days for the recurring period
recurring_price  | The price for each renewal
rebills          | The number of times the subscription is automatically renewed
uid              | The (Drupal) user id for this subscription (needs to be passed - to - CCBill, see section called "Setup" above)


CCBill Administration
---------------------
The CCBill module provides its own administration page available at 'admin/settings/ccbill'. On that page, you can enter your CCBill account number and DataLink credentials. Once these are entered and saved, you can click the "Test DataLink Integration" button. Please note that DataLink is only required if you want CCBill to manage any expirations; and if you want to make use of the "expire" op in hook_ccbill.

The DataLink credentials can be setup in the CCBill Admin Panel. To do so, log in to your admin panel at https://webadmin.ccbill.com


Maintainers
-----------
haagendazs (Daniel Hanold, danny@danielhanold.com)