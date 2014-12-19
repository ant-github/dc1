<?php

/**
 * @file
 * Default theme implementation to format the simplenews newsletter footer.
 *
 * Copy this file in your theme directory to create a custom themed footer.
 * Rename it to simplenews-newsletter-footer--[tid].tpl.php to override it for a
 * newsletter using the newsletter term's id.
 *
 * @todo Update the available variables.
 * Available variables:
 * - $build: Array as expected by render()
 * - $build['#node']: The $node object
 * - $language: language code
 * - $key: email key [node|test]
 * - $format: newsletter format [plain|html]
 * - $unsubscribe_text: unsubscribe text
 * - $test_message: test message warning message
 * - $simplenews_theme: path to the configured simplenews theme
 *
 * Available tokens:
 * - [simplenews-subscriber:unsubscribe-url]: unsubscribe url to be used as link
 *
 * Other available tokens can be found on the node edit form when token.module
 * is installed.
 *
 * @see template_preprocess_simplenews_newsletter_footer()
 */
?>
<?php if (!$opt_out_hidden): ?>
  <?php if ($format == 'html'): ?>
 <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;" width="100%">
                  <tbody>
                    <tr>
                      <td align="center" bgcolor="#000000" style="border-collapse: collapse; padding: 5px 0;" valign="top">
                        <!-- <br /> --><span style="color: #ffffff; font-family: Arial; font-size: 11px;"><a href="http://www.deliverycode.com/contact-us" style="text-decoration: none; color: #ffffff;">Contact Us</a> &nbsp;/&nbsp; <a href="http://www.deliverycode.com/privacy" style="text-decoration: none; color: #ffffff;">Privacy</a> &nbsp;/&nbsp; <a href="http://www.deliverycode.com/terms" style="text-decoration: none; color: #ffffff;">Terms and Conditions</a> </span><br />
                        <!-- <br /> --></td>
                    </tr>
                    <tr>
                      <!-- <td valign="top"  bgcolor="#000000" align="center" style="border-collapse: collapse;">
                                <span style="color: #666666; font-family: Arial; font-size: 11px;">
                                  <br>
                                </td>
                              </tr> -->
                    </tr>
                    <tr>
                      <td align="center" style="color: #000; font-family: Arial; font-size: 11px;">
                        <br />
                        Copyright © 2014 Delivery Code. All rights reserved.<br />
                        <br />
                        We reserve the right to change the Terms &amp; Conditions at any time<br />
                        If you no longer wish to receive emails from Delivery Code, please unsubscribe by clicking <a href="[simplenews-subscriber:unsubscribe-url]" style="color: #666666;" target="_blank">here</a><br />
                        Unless otherwise noted, Delivery Code has applied VAT at 20% to the appropriate items in this transaction based on country of delivery in accordance with the EU laws on distance selling. Our VAT number is GB 180725703<br />
                        This email was sent to you by Delivery Code, Registered number: 8662491. Registered Address: Terleys, Molehill Green, Felsted, Dunmow, Essex CM6 3JP</td>
                    </tr>
                    <tr>
                      <td height="60" style="border-collapse: collapse;" valign="top">
                        &nbsp;</td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
        <!--[if (gte mso 9)|(IE)]> </td>
                    </tr>
                  </table>
                  <![endif]--></td>
    </tr>
  </tbody>
</table>

  <?php else: ?>
  -- <?php print $unsubscribe_text ?>: [simplenews-subscriber:unsubscribe-url]
  <?php endif ?>
<?php endif; ?>

<?php if ($key == 'test'): ?>
- - - <?php print $test_message ?> - - -
<?php endif ?>
