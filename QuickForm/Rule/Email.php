<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4.0                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Bertrand Mansion <bmansion@mamasam.com>                     |
// +----------------------------------------------------------------------+
//
// $Id$

require_once('HTML/QuickForm/Rule.php');

/**
* Email validation rule
* @version     1.0
*/
class HTML_QuickForm_Rule_Email extends HTML_QuickForm_Rule
{
    var $regex = '/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/';

    /**
     * Validates an email address
     *
     * @param     string    $email          Email address
     * @param     boolean   $checkDomain    True if dns check should be performed
     * @access    public
     * @return    boolean   true if email is valid
     */
    function validate($email, $checkDomain = false)
    {
        if (preg_match($this->regex, $email)) {
            if ($checkDomain && function_exists('checkdnsrr')) {
                $tokens = explode('@', $email);
                if (checkdnsrr($tokens[1], 'MX') || checkdnsrr($tokens[1], 'A')) {
                    return true;
                }
                return false;
            }
            return true;
        }
        return false;
    } // end func validate

    /**
     * Returns the javascript test
     *
     * @param     string    $jsValue    JS code to find the element value
     * @param     string    $jsField    Element name in the form
     * @param     string    $jsMessage  Error message encoded for javascript
     * @param     string    $jsReset    JS code to revert the value back to default if error
     * @param     mixed     $options    Options for this rule, not used yet
     * @access    public
     * @return    string    javascript code
     */
    function getValidationScript($jsValue, $jsField, $jsMessage, $jsReset, $options = null)
    {
        $js = "$jsValue\n" .
              "  var field = frm.elements['$jsField'];\n" .
              "  var regex = " . $this->regex . ";\n" .
              "  if (value != '' && !regex.test(value) && !errFlag['$jsField']) {\n" .
              "    errFlag['$jsField'] = true;\n" .
              "    _qfMsg = _qfMsg + '\\n - $jsMessage';\n" .
              $jsReset .
              "  }\n";
        return $js;
    } // end func getValidationScript

} // end class HTML_QuickForm_Rule_Email
?>