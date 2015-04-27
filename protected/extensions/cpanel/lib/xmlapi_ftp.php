<?php
/**
 * XMLAPI wrapper class for cPanel Ftp module
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
 * @see http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp
 *
 * Last Updated: 17 April 2010
 *
 * Changes
 *
 * 1.1.0
 * - fixed calls for API2
 *
 * 1.0.0
 * - added main Ftp calls
 *
 */
class xmlapi_ftp extends xmlapi_base {
    public function __construct($host, $user = null, $password = null) {
        parent::__construct($host, $user, $password);
    }

    /**
     * Add a new FTP account.
     * @param string $user
     * The name of the FTP account you wish to create. When authenticating with
     * this name, remember to append the main domain to the end. (e.g. user@example.com).
     * @param string $pass
     * The password for the new FTP account.
     * @param string $homedir
     * The FTP account's root directory. This should be relative to the
     * account's public_html directory.
     * @param int $quota
     * The new FTP account's quota. 0 indicates that the account will not use
     * a quota. This parameter defaults to 0.
     * @param boolean $disallowdot
     * By setting this parameter to 1, any dots will be stripped from the username.
     * @param string $homedir2
     * The path to the FTP account's root directory. This value should be relative
     * to the account's home directory.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_addftp
     */
    public function addftp($user, $pass, $homedir='', $quota = 0, $disallowdot = false, $homedir2 = '') {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array(
            $user,
            $pass,
            $homedir,
            $quota,
            (int)$disallowdot,
            $homedir2
        )));
    }

    /**
     * Delete an FTP account.
     * @param string $user
     * The name of the FTP account you wish to remove.
     * @param boolean $destroy
     * Setting this parameter to 1 causes the FTP account's document root to be deleted.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_delftp
     */
    public function delftp($user, $destroy = false) {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array(
            $user,
            (int)$destroy,
        )));
    }

    /**
     * Change an FTP account's quota.
     * @param string $user
     * The name of the FTP account whose quota you wish to change.
     * @param int $quota
     * The new quota for the FTP account.
     * @param boolean $kill
     * Indicates whether or not the quota should be removed. If you set the
     * quota parameter to 0, this parameter is automatically set.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_ftpquota
     */
    public function ftpquota($user, $quota = 0 , $kill = false) {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array(
            $user,
            $quota,
            (int)$kill,
        )));
    }

    /**
     * Display the FTP daemon the server is using.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_ftpservername
     */
    public function ftpservername() {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__));
    }

    /**
     * Retrieve FTP stats login information for the current username.
     * This username can be used for downloading server logs.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_ftpstatslogin
     */
    public function ftpstatslogin() {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__));
    }

    /**
     * Create the public_ftp directory used for anonymous FTP logins.
     * This function will also return whether or not the directory is writable.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_get_anonftp
     */
    public function get_anonftp() {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__));
    }

    /**
     * Create the incoming public_ftp directory used for anonymous FTP uploads.
     * This function will also return whether or not the directory is writable.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_get_anonftpin
     */
    public function get_anonftpin() {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__));
    }

    /**
     * Retrieve the anonymous FTP welcome message.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_get_welcomemsg
     */
    public function get_welcomemsg() {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__));
    }

    /**
     * The name of the FTP account whose quota you wish to retrieve.
     * @param string $user
     * The name of the FTP account whose quota you wish to retrieve.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_getftpquota
     */
    public function getftpquota($user) {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array(
            $user
        )));
    }

    /**
     * Kill one or all FTP sessions associated with the authenticated user.
     * @param int $pid
     * The process identification number (PID) of the FTP session you wish to kill.
     * If you wish to kill all FTP sessions associated with the account, you may
     * pass 'all' to this parameter.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_kill_ftp_session
     */
    public function kill_ftp_session($pid) {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array(
            $pid
        )));
    }

    /**
     * List FTP accounts associated with the authenticated user's account.
     * @param string $include_acct_types
     * This parameter allows you to specify which FTP account types you wish to
     * view. If you wish to view multiple types, use the pipe character (|) as
     * a separator.
     * @param string $skip_acct_types
     * This parameter allows you to exclude certain FTP account types from the
     * list. If you wish to exclude multiple account types, use the pipe character
     * (|) as a separator.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_listftp
     */
    public function listftp($include_acct_types, $skip_acct_types) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Ftp', __FUNCTION__, array(
            'nclude_acct_types' => $include_acct_types,
            'skip_acct_types' => $skip_acct_types
        )));
    }

    /**
     * Retrieve a list of FTP sessions associated with the authenticated account.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_listftpsessions
     */
    public function listftpsessions() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Ftp', __FUNCTION__));
    }

    /**
     * Retrieve an HTML link to the logs for a domain. The username and password
     * are included in this url.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_listftpstatsurl
     */
    public function listftpstatsurl() {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__));
    }

    /**
     * Generate a list of FTP accounts associated with a cPanel account.
     * The list will contain each account's disk information.
     * @param string $dirhtml
     * This parameter allows you to prepend the 'dir' return variable with a URL.
     * Passing 'example.com/' to this parameter will cause all 'dir' responses to
     * begin with 'example.com/', followed by a relative path to the FTP document
     * root. (e.g. example.com/ftp').
     * @param string $include_acct_types
     * This parameter allows you to specify the type of FTP account you wish to view.
     * If you wish to view multiple account types, use the pipe character (|) as
     * a separator.
     * @param string $skip_acct_types
     * This parameter allows you to exclude certain FTP account types from the list.
     * If you wish to exclude multiple account types, use the pipe character (|) as
     * a separator.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_listftpwithdisk
     */
    public function listftpwithdisk($dirhtml, $include_acct_types='', $skip_acct_types='') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Ftp', __FUNCTION__, array(
            'dirhtml' => $dirhtml,
            'include_acct_types' => $include_acct_types,
            'skip_acct_types' => $skip_acct_types
        )));
    }


    /**
     * Change an FTP user's password.
     * @param string $user
     * The name of the FTP account whose password you wish to change.
     * @param string $pass
     * The new password for the FTP account.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_getftpquota_AN1
     */
    // incorrect function name
    /*
    public function getftpquota($user, $pass) {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array(
            $user
        )));
    }
    */

    /**
     * Enable or disable anonymous FTP logins.
     * @param boolean $set
     * A boolean integer indicating whether FTP logins should be enabled or disabled.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_set_anonftp
     */
    public function set_anonftp($set) {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array((int)$set)));
    }

    /**
     * Change whether the directory for anonymous FTP uploads is writable or not.
     * @param boolean $set
     * A boolean integer indicating whether the directory should be writable or not.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_set_anonftpin
     */
    public function set_anonftpin($set) {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array((int)$set)));
    }

    /**
     * Change the anonymous FTP banner.
     * @param string $msg
     * The welcome message you wish to display to anonymous FTP users.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFtp#Ftp_set_welcomemsg
     */
    public function set_welcomemsg($msg) {
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Ftp', __FUNCTION__, array($msg)));
    }
}

