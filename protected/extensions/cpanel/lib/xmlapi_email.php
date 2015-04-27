<?php
/**
 * XMLAPI wrapper class for cPanel Email module
 *
 * This class allows for easy interaction with cPanel's XML-API allow functions within the XML-API to be called
 * by calling funcions within this class
 *
 * LICENSE:
 *
 * Copyright (c) 2010, Alexandr Dorogikh
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided
 * that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this list of conditions and the
 *   following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the
 *   following disclaimer in the documentation and/or other materials provided with the distribution.
 * * Neither the name of the cPanel, Inc. nor the names of its contributors may be used to endorse or promote
 *   products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
 * PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Alexandr Dorogikh (aka lexand)
 * @link http://creative-territory.net/
 * @version 1.1.0
 * @see http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail
 *
 * Last Updated: 17 April 2010
 *
 * Changes
 *
 * 1.1.0
 * - fixed calls for API2
 *
 * 1.0.0
 * - added main Mail calls
 *
 */
class xmlapi_email {

    private $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
    private $fullPattern='/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/';

    public function __construct($host, $user = null, $password = null) {
        parent::__construct($host, $user, $password);
    }

    /**
     * Display the account name or 'All Mail On Your Account'. This is useful
     * if you wish to retrieve the account name in a webmail interface.
     * @param string $account
     * An account name or email address. The function will return whichever is not specified.
     * @param string $display
     * If present, and an account is not specified, a string that contains 'All Mail On Your Account' is returned.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_accountname
     */
    public function accountname($account, $display) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account,
            'display' => $display
        )));
    }

    /**
     * Create an email forwarder for a specified address. You can forward mail
     * to a new address or pipe incoming email to a program.
     * @param string $domain
     * The domain for which you wish to add a forwarder (e.g. example.com).
     * @param string $email
     * The local address you wish to use as a forwarder (e.g. 'user' if the address was user@example.com).
     * @param string $fwdopt
     * This parameter defines what type of forwarder should be used. The valid
     * values for this option are:<br/>
     * 'pipe' - for forwarding to a program<br/>
     * 'fwd' - for forwarding to another non-system email address<br/>
     * 'system' - for forwarding to an account on the system<br/>
     * 'blackhole' - for bouncing emails using the blackhole functionality<br/>
     * 'fail' - for bounding emails using the fail functionality
     * @param string $failmsgs
     * If fwdopt is 'fail' this needs to be defined to determine the correct failure message.
     * @param string $fwdemail
     * The email address to which you want to forward mail, this should only be
     * used if 'fwdopt' equals 'fwd'.
     * @param string $fwdsystem
     * The system account that you wish to forward email to, this should only be
     * used if 'fwdopt' equals 'system'.
     * @param string $pipefwd
     * The path to the program to which you wish to pipe email.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_addforward
     */
    public function addforward($domain, $email, $fwdopt, $failmsgs = '', $fwdemail = '', $fwdsystem = '', $pipefwd = '') {
        $this->cpanel_api_ver = 'api2';
        
        if(!in_array($fwdopt, array('pipe', 'fwd', 'system', 'blackhole', 'fail')))
            throw new Exception('incorrect "fwdopt" value');

        if(($fwdopt == 'fwd') && empty($fwdemail)){
            throw new Exception('"fwdmail" must be specified');
            if(!$this->_check_email($fwdemail))
                throw new Exception('"fwdemail" not a valid email address');
        }

        if(($fwdopt == 'fail') && empty($failmsgs)){
            throw new Exception('"failmsgs" must be specified');
        }


        if(($fwdopt == 'system') && empty($fwdsystem))
            throw new Exception('"fwdsystem" must be specified');


        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'email' => $email,
            'fwdopt' => $fwdopt,
            'failmsgs' => $failmsgs,
            'fwdemail' => $fwdemail,
            'fwdsystem' => $fwdsystem,
            'pipefwd' => $pipefwd
        )));
    }

    /**
     * Add an MX record. This function will not work if you do not have access
     * to the 'changemx' feature.
     * @param string $domain
     * The domain to which you wish to add the mail exchanger.
     * @param string $exchange
     * The name of the mail exchanger (e.g. mail.example.com).
     * @param int $preference
     * The priority of the mail exchanger. Remember: Lower values translate to
     * higher priorities, with 0 being the highest priority exchanger.
     * Traditionally, increments of 5 or 10 are used.
     * @param string $alwaysaccept
     * This value describes whether or not the local machine should accept mail
     * for this domain. Possible values are as follows:<br/>
     * 'auto' - Allow cPanel to determine the appropriate role based on a DNS
     * query on the MX record.<br/>
     * 'local' - Always accept and route mail for the domain.<br/>
     * 'secondary' - Accept mail for the domain until a higher priority (lower
     * numbered) mail server becomes available.<br/>
     * 'remote' - Do not accept mail for the domain.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_addmx
     */
    public function addmx($domain, $exchange, $preference, $alwaysaccept = 'auto') {
        $this->cpanel_api_ver = 'api2';

        if(!empty($alwaysaccept) && !in_array($alwaysaccept, array('auto','local','secondary','remote')))
            throw new Exception('incorrect "alwaysaccept" value');

        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'exchange' => $exchange,
            'preference' => $preference,
            'alwaysaccept' => $alwaysaccept
        )));
    }

    /**
     * Add (create) an e-mail account.
     * @param string $domain
     * Domain name for the e-mail account.
     * @param string $email
     * Username part of the e-mail account (the address part before "@").
     * @param string $password
     * Password for the e-mail account.
     * @param int $quota
     * Positive integer defining a disk quota for the e-mail account; could be 0 for unlimited.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_addpop
     */
    public function addpop($domain, $email, $password, $quota) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'email' => $email,
            'password' => $password,
            'quota' => $quota
        )));
    }

    /**
     * Retrieve a list of mail-related subdirectories (boxes) in your mail directory.
     * @param string $account
     * The name of the email account you wish to review.
     * @param string $dir
     * This parameter allows you to specify which mail directories will be
     * displayed. Specifying 'default' or 'mail' will cause the function to
     * list all of your domains' mail directories. By providing a domain (e.g.
     * 'mail/example.com' or 'example.com'), the function will display account
     * directories related to the domain.
     * @param boolean $showdotfiles
     * A boolean variable that allows you to specify whether or not you wish to
     * view hidden directories and files (e.g. '.hidden-dir').
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_browseboxes
     */
    public function browseboxes($account = '', $dir = '', $showdotfiles = false) {
        $this->cpanel_api_ver = 'api2';

        if(!empty($account) && !$this->_check_email($account))
            throw new Exception('"account" contains not a valid email address');

        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account,
            'dir' => $dir,
            'showdotfiles' => (int)$showdotfiles
        )));
    }

    /**
     * Change values for a specific MX record. This function is not available in
     * DEMO mode. You must have access to the 'changemx' feature to use this function.
     * @param string $domain
     * The domain corresponding to the MX record you wish to change.
     * @param string $exchange
     * The name you wish to give to the new exchanger.
     * @param int $oldpreference
     * The priority value of the old exchanger. If two entries for 'oldexchanger'
     * match, the function will decide which value will be used.
     * @param int $preference
     * The new exchanger's priority value.
     * @param string $oldexchange
     * The name of the exchanger you wish to replace. If you do not specify this
     * parameter, a new entry will be created.
     * @param string $alwaysaccept
     * This parameter specifies how the new exchanger should behave. Possible
     * values are 'local', 'secondary', 'backup', or 'remote'. If you choose
     * not to specify this parameter, cPanel will choose the best possible value.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_changemx
     */
    public function changemx($domain, $exchange, $oldpreference, $preference, $oldexchange = '', $alwaysaccept = '') {
        $this->cpanel_api_ver = 'api2';

        if(!in_array($alwaysaccept, array('auto', 'local', 'secondary', 'remote')))
            throw new Exception('Invalid "alwaysaccept" value');

        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'exchange' => $exchange,
            'oldexchange' => $oldexchange,
            'oldpreference' => $oldpreference,
            'preference' => $preference,
            'alwaysaccept' => $alwaysaccept
        )));
    }

    /**
     * Check to see how the main email account for a domain handles undeliverable
     * mail. Unroutable messages may be sent to /dev/null, :fail:, :blackhole:,
     * forwarded to another address, or piped to a program.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_checkmaindiscard
     */
    public function checkmaindiscard() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__));
    }

    /**
     * Rebuild an email address' cache file.
     * @param string $username
     * The username corresponding to the account whose cache file you wish to rebuild.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_clearpopcache
     */
    public function clearpopcache($username) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'username' => $username
        )));
    }

    /**
     * Delete an email filter.
     * @param string $filtername
     * The name of the filter you wish to delete.
     * @param string $account
     * This parameter allows you to specify an email address or account username
     * to remove user-level filters. By specifying an email address, the function
     * will effect user-level filters associated with the account. Entering a
     * cPanel username will cause the function to remove user-level filters
     * associated with your account's default email address. By not specifying
     * this value, the function will remove an account-level filter.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_deletefilter
     */
    public function deletefilter($filtername, $account = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account,
            'filtername' => $filtername
        )));
    }

    /**
     * Delete an MX record. This function will not work if the 'changemx' feature
     * is disabled for the current user.
     * @param string $domain
     * The domain that corresponds to the mail exchanger you wish to remove.
     * @param string $exchange
     * The name of the mail exchanger you wish to remove (e.g. 'mail.example.com').
     * @param integer $preference
     * The priority of the mail exchanger, 0 being the highest priority exchanger.
     * Traditionally, increments of 5 or 10 are used.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_delmx
     */
    public function delmx($domain, $exchange, $preference) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'exchange' => $exchange,
            'preference' => $preference
        )));
    }

    /**
     * Delete an e-mail account.
     * @param string $domain
     * The domain corresponding to the email account you wish to remove. (This
     * value should consist of the text after the 'at' (@) sign. For example,
     * example.com if the address you wished to remove was user@example.com).
     * @param string $email
     * The username corresponding to the email account you wish to remove. (This
     * value should consist of the text before the 'at' (@) sign. For example,
     * user if the address you wished to remove was user@example.com).
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_delpop
     */
    public function delpop($domain, $email) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'email' => $email
        )));
    }

    /**
     * Modify an email account's quota.
     * @param string $domain
     * The domain of the email account you wish to modify (e.g. 'example.com'
     * if the address was 'user@example.com').
     * @param string $email
     * The username portion of the email address (e.g. 'user' if the address was
     * 'user@example.com').
     * @param int $quota
     * A positive integer indicating the new disk quota value in megabytes. Enter
     * 0 for an unlimited quota.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_editquota
     */
    public function editquota($domain, $email, $quota) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'email' => $email,
            'quota' => $quota
        )));
    }

    /**
     * Retrieve information about an auto responder used by a specified email address.
     * @param string $email
     * The email address corresponding to the auto responder information you wish to review.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_fetchautoresponder
     */
    public function fetchautoresponder($email) {
        $this->cpanel_api_ver = 'api2';

        if(!$this->_check_email($email))
            throw new Exception('"email" contains not a valid email address');

        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'email' => $email
        )));
    }

    /**
     * Retrieve a list of character encodings supported by cPanel.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_fetchcharmaps
     */
    public function fetchcharmaps() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__));
    }

    /**
     * Retrieve a list of email filters.
     * @param string $account
     * This parameter allows you to specify an email address or account username
     * to review user-level filters. Specifying an email address will cause the
     * function to retrieve user-level filters associated with the account.
     * Entering a cPanel username will cause the function to return user-level
     * filters associated with your account's default email address. If you do
     * not specify this value, the function will retrieve account-level filter
     * information.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_filterlist
     */
    public function filterlist($account = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account
        )));
    }

    /**
     * Count the number of email filters and return a default suggested rule name
     * (e.g. Rule [1 + count]). For example, if user@example.com used 3 user-level
     * filters, this function would return 'Rule 4.'
     * @param string $account
     * An email address associated with an account from which you wish to read
     * the user-level filter file in order to receive the appropriate result.
     * If you choose not to specify a value for this variable, the function will
     * return a value based on account-level filters. Passing a username to this
     * variable will cause the function to scan the account's filters for the
     * default email account.
     * @param string $filtername
     * A fallback in case the function cannot find any relevant filter files.
     * Under certain conditions, the function will create empty versions of
     * missing files, resulting in erroneous output when the function is run again.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_filtername
     */
    public function filtername($account = '', $filtername = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account,
            'filtername' => $filtername
        )));
    }

    /**
     * Retrieve the full path to a specified mail folder. This directory needs
     * to be under the account's main mail directory.
     * @param string $account
     * The email address corresponding to the directory whose path you wish to
     * find (e.g. user@example.com).
     * @param string $dir
     * The mail folder you wish to query for its full path. If you do not specify
     * this parameter, 'mail' will be used.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_getabsbrowsedir
     */
    public function getabsbrowsedir($account, $dir = '') {
        $this->cpanel_api_ver = 'api2';

        if(!$this->_check_email($account))
            throw new Exception('"account" contains not a valid email address');

        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account,
            'dir' => $dir
        )));
    }

    /**
     * Check the cPanel configuration to see if a domain uses the local server
     * as a mail exchanger. This function is not available to users that do not
     * have access to the 'changemx' feature. Remember: This function checks the
     * cPanel configuration, not DNS records.
     * @param string $domain
     * The domain you wish to examine. If this value is not specified, the
     * function will return output for each domain you own.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_getalwaysaccept
     */
    public function getalwaysaccept($domain = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain
        )));
    }

    /**
     * Retrieve information about a specific email account's disk usage.
     * @param string $domain
     * The domain that corresponds to the email address whose disk usage
     * information you wish to view. This value needs to be the section of the
     * email address after the 'at' (@) sign (e.g. example.com).
     * @param string $login
     * The username section of the email address whose disk usage information you
     * wish to view. This value needs to be the section of the email address
     * before the 'at' (@) sign (e.g. user).
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_getdiskusage
     */
    public function getdiskusage($domain, $login) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'login' => $login
        )));
    }

    /**
     * Retrieve a list of domains that use aliases and custom catch-all addresses.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listaliasbackups
     */
    public function listaliasbackups() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__));
    }

    /**
     * Retrieve a list of auto responders associated with a domain.
     * @param string $domain
     * The domain whose auto responders you wish to view.
     * @param string $regex
     * Regular expressions allow you to filter results based on a set of criteria.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listautoresponders
     */
    public function listautoresponders($domain = '', $regex = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'regex' => $regex
        )));
    }

    /**
     * Retrieve the default address or action taken when unroutable messages
     * are received by the default address.
     * @param string $domain
     * The domain that corresponds to the default address and information you
     * wish to view.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listdefaultaddresses
     */
    public function listdefaultaddresses($domain) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain
        )));
    }

    /**
     * Retrieve the destination for email forwarded by a domain forwarder.
     * @param string $domain
     * The domain corresponding to the forwarder whose destination you wish to view.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listdomainforwards
     */
    public function listdomainforwards($domain) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain
        )));
    }

    /**
     * Retrieve a list of domains that use domain-level filters.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listfilterbackups
     */
    public function listfilterbackups() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__));
    }

    /**
     * List all of the old-style email filters in your .filter file. This function lists both account-level and user-level filters.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listfilters
     */
    public function listfilters() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__));
    }

    /**
     * List forwarders associated with a specific domain.
     * @param string $domain
     * The domain name whose forwarders you wish to review.
     * @param string $regex
     * Regular expressions allow you to filter results based on a set of criteria.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listforwards
     */
    public function listforwards($domain = '', $regex = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'regex' => $regex
        )));
    }

    /**
     * Lists mailing lists.
     * @param string $domain
     * The domain whose mailing lists you wish to view.
     * @param string $regex
     * Regular expressions allow you to filter results based on a set of criteria.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listlists
     */
    public function listlists($domain = '', $regex = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'regex' => $regex
        )));
    }

    /**
     * Retrieve a list of domains associated with your account that send and
     * receive email. This list includes the main domain, addon domains, and
     * parked domains.
     * @param boolean $skipmain
     * By passing '1' to this variable, you may skip the main domain.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listmaildomains
     */
    public function listmaildomains($skipmain = false) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'skipmain' => (int)$skipmain
        )));
    }

    /**
     * List all mail exchangers associated with a domain.
     * @param string $domain
     * The domain whose mail exchangers you wish to view.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listmxs
     */
    public function listmxs($domain) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain
        )));
    }

    /**
     * Retrieve a list of email accounts associated with your cPanel account.
     * Email::listpopswithdisk is the preferred way of retrieving a list of email
     * accounts.
     * @param string $regex
     * Regular expressions allow you to filter results based on a set of criteria.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listpops
     */
    public function listpops($regex = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'regex' => $regex
        )));
    }

    /**
     * Retrieve a list of email accounts and logins associated with your cPanel
     * account. This will include email addresses from the the main domain, addon
     * domains and parked domains.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listpopssingle
     */
    public function listpopssingle() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__));
    }

    /**
     * List email accounts associated with a particular domain. This function
     * will also list quota and disk usage information.
     * @param string $domain
     * The domain whose email accounts you wish to view.
     * @param boolean $nearquotaonly
     * Passing '1' to this parameter allows you to only view accounts that have
     * used 95% or more of their allotted disk space.
     * @param boolean $no_validate
     * Passing '1' to this parameter will cause the function to only read data
     * from your '.cpanel/email_accounts.yaml' file. This parameter is 'off' by
     * default, causing the function to check the passwd file, quota files, etc.
     * Theoretically, each of these files should contain identical values.
     * @param string $regex
     * Regular expressions allow you to filter results based on a set of criteria.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listpopswithdisk
     */
    public function listpopswithdisk($domain = '', $nearquotaonly = false, $no_validate = false, $regex = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'nearquotaonly' => (int)$nearquotaonly,
            'no_validate' => (int)$no_validate,
            'regex' => $regex
        )));
    }

    /**
     * Retrieve a list of email accounts and logins associated with your cPanel
     * account. This list includes email addresses from the the main domain,
     * addon domains, and parked domains. This list will also contain your
     * account's username and an HTML link to the 'mainacct.jpg' graphic,
     * based on your account's style (skin).
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_listpopswithimage
     */
    public function listpopswithimage() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__));
    }

    /**
     * Retrieve the rules and actions associated with an email filter.
     * @param string $filtername
     * The name of the filter you wish to review.
     * @param string $account
     * This parameter allows you to specify an email address or account
     * username to review user-level filters. By specifying an email address,
     * the function will retrieve user-level filters associated with the account.
     * Entering a cPanel username will cause the function to return user-level
     * filters associated with your account's default email address. By not
     * specifying this value, the function will retrieve account-level filter
     * information.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_loadfilter
     */
    public function loadfilter($filtername, $account = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account,
            'filtername' => $filtername
        )));
    }

    /**
     * Change an email account's password.
     * @param string $domain
     * The domain corresponding to the email address whose password you wish to
     * change. (This value should consist of the text after the 'at' (@) sign.
     * For example, example.com if the address you wished to remove was user@example.com).
     * @param string $email
     * The username corresponding to the email address whose password you wish
     * to change. (This value should consist of the text before the 'at' (@)
     * sign. For example, user if the address you wished to remove was user@example.com).
     * @param string $password
     * The new password for the account.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_passwdpop
     */
    public function passwdpop() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'email' => $email,
            'password' => $password
        )));
    }

    /**
     * Set a mail exchanger for a specified domain to local, remote, secondary,
     * or auto. The 'auto' value allows cPanel to determine the appropriate role
     * for the mail exchanger. Note: This function only affects the cPanel
     * configuration. You will need to configure the MX's DNS entry elsewhere.
     * Remember: This function is not available if the 'changemx' feature is not
     * enabled for the user.
     * @param string $domain
     * The domain corresponding to the mail exchanger you wish to set.
     * @param string $mxcheck
     * The status of the mail exchanger as it corresponds to cPanel's configuration.
     * Input options are as follows:<br/>
     * 'auto' - Allow cPanel to determine the appropriate role based on a DNS query on the MX record.<br/>
     * 'local' - Always accept and route mail for the domain.<br/>
     * 'secondary' - Accept mail for the domain until a high priority (lower numbered)
     * mail server becomes available.<br/>
     * 'remote' - Do not accept mail for the domain.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_setalwaysaccept
     */
    public function setalwaysaccept($domain, $mxcheck = 'auto') {
        $this->cpanel_api_ver = 'api2';

        if(!in_array($mxcheck, array('auto', 'local', 'secondary', 'remote')))
            throw new Exception('Invalid "mkcheck" value');

        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'domain' => $domain,
            'mxcheck' => $mxcheck
        )));
    }

    /**
     * Configure a default (catchall) email address. A default address handles
     * unroutable mail sent to a domain. Note: Irrelevant parameters are passed
     * to the function regardless of its configuration and will be included in
     * the rule. This will not cause any problems.
     * @param string $fwdopt
     * Describes how unroutable mail will be handled. The following options are available:<br/>
     * 'fail' => Bounce unroutable messages, returning a failure message to the sender.<br/>
     * 'fwd' => Forward unroutable messages to another email address.<br/>
     * 'blackhole' => Send undeliverable mail to /dev/null. This option will not
     * generate a failure notice.<br/>
     * 'pipe' => Pipe undeliverable mail to a program.
     * @param string $domain
     * The domain to which the rule will apply. If this parameter is not specified,
     * cPanel will attempt to use the cPanel account's main domain. Note: The user 
     * must own this domain.
     * @param string $failmsgs
     * The failure message that will be sent to the sender in the event an
     * incoming message is bounced. The default value is set to, "No such person
     * at this address." Note: This parameter only takes effect if fwdopt = 'fail'.
     * @param string $fwdemail
     * The email address to which mail received by the default address will be
     * sent. Note: This parameter only takes effect if fwdopt = 'fwd'. Enter
     * the email address in standard format. (e.g.'user@example.com).
     * @param string $pipefwd
     * The program to which messages received by the default address will be
     * piped (|). Note: cPanel will append the user's home directory to
     * the beginning of the value.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_setdefaultaddress
     */
    public function setdefaultaddress($fwdopt, $domain, $failmsg = '', $fwdemail = '', $pipefwd = '') {
        $this->cpanel_api_ver = 'api2';

        if(!in_array($fwdopt, array('pipe', 'fwd', 'blackhole', 'fail')))
            throw new Exception('incorrect "fwdopt" value');

        if(($fwdopt == 'fwd') && empty($fwdemail)){
            throw new Exception('"fwdmail" must be specified');
            if(!$this->_check_email($fwdemail))
                throw new Exception('"fwdemail" not a valid email address');
        }

        if(($fwdopt == 'fail') && empty($failmsg)){
            throw new Exception('"failmsg" must be specified');
        }

        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'fwdopt' => $fwdopt,
            'domain' => $domain,
            'failmsg' => $failmsg,
            'fwdemail' => $fwdemail,
            'pipefwd' => $pipefwd
        )));
    }

    /**
     * Create a new email filter. Note the asterisks after some of the parameter
     * names. Since it is possible to have more than one set of conditions, your
     * first set of conditions must have a '1' appended to the parameter names.
     * The second set of conditions with a '2', etc. You may have up to 4096
     * conditions. Generally, you will only need a second set of 'part', 'match',
     * 'val,' and 'opt,'parameters unless you a want second set of actions.
     * In this case, you will need to create incremental versions of 'action'
     * and 'dest'. You will need to define ' part1', 'match1', 'val1', 'opt1',
     * 'action1', and 'dest1'. In addition, you may want values for 'part2',
     * 'match2,' 'val2,' and 'opt2' if you need a complex rule. See the
     * cPanel interface for reference.
     * @param string $account
     * To configure a user-level filter, enter the email address to which you
     * would like to apply the rule. If you would like to set up an account-level
     * filter, do not enter a value for this parameter. If you would like to
     * configure a filter for the default email account, enter the cPanel username
     * corresponding to the catch-all address to which the new rule will apply.
     * @param string $action
     * The action taken by the filter. Acceptable values include 'deliver',
     * 'fail', 'finish', 'save', and 'pipe'. If you do not wish for this filter
     * to take more than one action, you will not need to specify an incremental
     * version of this parameter.
     * @param string $filtername
     * The name you wish to give to the new filter.
     * @param string $match
     * The new filter match type. Acceptable values are 'is', 'matches', 'contains',
     * 'does not contain', 'begins', 'ends', 'does not begin', 'does not end',
     * 'does not match', 'is above', 'is not above', 'is below', and 'is not below'.
     * The last 4 options pertain only to numbers.
     * @param string $part
     * The section of the email to which you want to apply the 'match' parameter.
     * Acceptable values are '$header_from:', '$header_subject:', '$header_to:',
     * '$reply_address:', '$message_body', '$message_headers', 'foranyaddress $h_to",$h_cc:,$h_bcc:', (!!!!)
     * 'not delivered', 'error_message', '$h_X-Spam-Status:', '$h_X-Spam-Score:',
     * and '$h_X-Spam-Bar:'. The last 3 options require SpamAssassin to be enabled.
     * You may also use 'error_message' or 'not delivered' which do not require the
     * 'match' parameter.
     * @param string $val
     * The value against which you want to match. (e.g. 'cheap prescriptions' or
     * 'free stuff').
     * @param string $dest
     * The destination for mail received by the filter, if one is required.
     * (e.g. /dev/null) This parameter corresponds with the 'action' parameter.
     * You do not need an incremental version of this parameter if you do not
     * need more than one action.
     * @param string $opt
     * This parameter connects conditionals. Acceptable values are 'and' or 'or'.
     * This value defaults to 'or'.
     * @param string $oldfiltername
     * This function can also be used to rename an existing filter. To change
     * the filter's name, supply the existing filter's name in this parameter
     * and the new name in the 'filtername' parameter.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_storefilter
     */
    public function storefilter($account, $action, $filtername, $match, $part, $val, $dest = '', $opt = 'or', $oldfiltername = '') {
        $this->cpanel_api_ver = 'api2';

        if(!in_array($action, array('deliver', 'fail', 'finish', 'save', 'pipe')))
            throw new Exception('Incorrect "action" value');

        if(!in_array($match, array('is', 'matches', 'contains', 'does not contain', 'begins', 'ends', 'does not begin', 'does not end', 'does not match', 'is above', 'is not above', 'is below', 'is not below')))
            throw new Exception('Incorrect "match" value');

        if(!in_array($part, array('$header_from:', '$header_subject:', '$header_to:', '$reply_address:', '$message_body', '$message_headers', 'foranyaddress $h_to",$h_cc:,$h_bcc:', 'not delivered', 'error_message', '$h_X-Spam-Status:', '$h_X-Spam-Score:', '$h_X-Spam-Bar:')))
            throw new Exception('Incorrect "part" value');

        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account,
            'action' => $action,
            'dest' => $dest,
            'filtername' => $filtername,
            'match' => $match,
            'oldfiltername' => $oldfiltername,
            'opt' => $opt,
            'part' => $part,
            'val' => $val
        )));
    }

    /**
     * Test the action of account-level mail filters. You can only test filters
     * for your cPanel account's main domain. This function only tests the body
     * of the message. You must have access to the 'blockers' feature to use
     * this function.
     * @param string $msg
     * The contents of the body of the message you wish to test.
     * @param string $account
     * This parameter allows you to specify and test old-style cPanel filters in
     * your $home/filters directory. By not specifying this parameter, you will
     * test your main domain's filters found in the /etc/vfilters/ directory.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiEmail#Email_tracefilter
     */
    public function tracefilter($msg, $account = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Email', __FUNCTION__, array(
            'account' => $account,
            'msg' => $msg
        )));
    }

    private function _check_email($email, $allowName = false, $checkMX = false, $checkPort = false){
		$valid=is_string($value) && (preg_match($this->pattern,$value) || $allowName && preg_match($this->fullPattern,$value));
		if($valid)
			$domain=rtrim(substr($value,strpos($value,'@')+1),'>');
		if($valid && $checkMX && function_exists('checkdnsrr'))
			$valid=checkdnsrr($domain,'MX');
		if($valid && $checkPort && function_exists('fsockopen'))
			$valid=fsockopen($domain,25)!==false;
		return $valid;
    }
}

