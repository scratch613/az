<?php
/**
 * XMLAPI wrapper class for cPanel Cron module
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
 * @see http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiCron
 *
 * Last Updated: 17 April 2010
 *
 * Changes
 *
 * 1.1.0
 * - fixed calls for API2
 *
 * 1.0.0
 * - added main Cron calls
 *
 */
class xmlapi_cron extends xmlapi_base {
    public function __construct($host, $user = null, $password = null) {
        parent::__construct($host, $user, $password);
    }

    /**
     * Add a crontab entry.
     * @param string command
     * The command, script, or program you wish for your cronjob to execute.
     * @param int day
     * The day on which you would like this crontab entry to run. Wildcards
     * and any acceptable input to a crontab time expression line are allowed here.
     * @param int hour
     * The hour at which you would like this crontab entry to run. Wildcards and
     * any acceptable input to a crontab time expression line are allowed here.
     * @param int minute
     * The minute at which you would like this crontab entry to run. Wildcards
     * and any acceptable input to a crontab time expression line are allowed here.
     * @param int month
     * The month you would like this crontab entry to run. Wildcards and any
     * acceptable input to a crontab time expression line are allowed here.
     * @param int weekday
     * The weekday on which you would like this crontab entry to run. Wildcards
     * and any acceptable input to a crontab time expression line is allowed here.
     * Acceptable values range from 0 to 6, where 0 represents Sunday and 6
     * represents Saturday.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiCron#Cron_add_line
     */
    public function add_line() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Cron', __FUNCTION__));
    }
    
    /**
     * Edit a crontab entry.
     * @param string command
     * The command, script, or program you wish for your cronjob to execute.
     * @param int day
     * The day on which you would like this crontab entry to run. Wildcards
     * and any acceptable input to a crontab time expression line are allowed here.
     * @param int hour
     * The hour at which you would like this crontab entry to run. Wildcards and
     * any acceptable input to a crontab time expression line are allowed here.
     * @param int minute
     * The minute at which you would like this crontab entry to run. Wildcards
     * and any acceptable input to a crontab time expression line are allowed here.
     * @param int month
     * The month you would like this crontab entry to run. Wildcards and any
     * acceptable input to a crontab time expression line are allowed here.
     * @param int weekday
     * The weekday on which you would like this crontab entry to run. Wildcards
     * and any acceptable input to a crontab time expression line is allowed here.
     * Acceptable values range from 0 to 6, where 0 represents Sunday and 6
     * represents Saturday.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiCron#Cron_edit_line
     */
    public function edit_line() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Cron', __FUNCTION__));
    }

    /**
     * This function returns the default notification email address for the authenticated user's crontab.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiCron#Cron_get_email
     */
    public function get_email() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Cron', __FUNCTION__));
    }

    /**
     * Lists cronjobs by user. This function will include the sequence (num) and task of the cronjob.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiCron#Cron_listcron
     */
    public function listcron() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Cron', __FUNCTION__));
    }

    /**
     * Remove an entry from the crontab.
     * @param int $line
     * The line number of the crontab entry you wish to remove, as reported by listcron.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiCron#Cron_remove_line
     */
    public function remove_line($line) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Cron', __FUNCTION__, array(
            'line' => $line
        )));
    }

    /**
     * Set the default notification email for the cron system.
     * @param string $email
     * The email address that should receive notifications from the cron system.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiCron#Cron_set_email
     */
    public function set_email($email) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Cron', __FUNCTION__, array(
            'email' => $email
        )));
    }

}
