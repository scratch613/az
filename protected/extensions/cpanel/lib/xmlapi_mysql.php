<?php
/**
 * XMLAPI wrapper class for cPanel Mysql module
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
 * @see http://docs.cpanel.net/twiki/bin/view/ApiDocs/Api1/ApiMysql
 * @see http://docs.cpanel.net/twiki/bin/view/ApiDocs/Api2/ApiMysqlFE
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
class xmlapi_mysql extends xmlapi_base {

    private $allowed_permissions = array('alter', 'temporary', 'routine,create', 'delete', 'drop', 'select', 'insert', 'update', 'references', 'index', 'lock', 'all');

    public function __construct($host, $user = null, $password = null){
        parent::__construct($host, $user, $password);
    }

    /**
     * Retrieve a list of databases that belong to a specific account.
     * @param string $regexp
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysqlFE#MysqlFE_listdbs
     */
    public function listdbs($regex = ''){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'MysqlFE', __FUNCTION__, array(
            'regex' => $regex
        )));
    }

    /**
     * Retrieve a list of existing database backups. 
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysqlFE#MysqlFE_listdbsbackup
     */
    public function listdbsbackup(){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'MysqlFE', __FUNCTION__));
    }

    /**
     * Retrieve a list of remote MySQL connection hosts.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysqlFE#MysqlFE_listhosts
     */
    public function listhosts(){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'MysqlFE', __FUNCTION__));
    }

    /**
     * List all of the MySQL users available to a cPanel account.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysqlFE#MysqlFE_listusers
     */
    public function listusers(){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'MysqlFE', __FUNCTION__));
    }

    /**
     * List users who can access a particular database.
     * @param string $dbname
     * The name of the database whose users you wish to view.
     * Be sure to use the cPanel convention's full MySQL database name. (e.g. cpaneluser_dbname).
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysqlFE#MysqlFE_listusersindb
     */
    public function listusersindb($dbname){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'MysqlFE', __FUNCTION__, array(
            'db' => $dbname
        )));
    }

    /**
     * Retrieve a list of permissions that correspond to a specific user and database.
     * @param string $dbname
     * @param string $dbuser
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysqlFE#MysqlFE_userdbprivs
     */
    public function userdbprivs($dbname, $dbuser){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'MysqlFE', __FUNCTION__, array(
            'db' => $dbname,
            'user' => $dbUser
        )));
    }

    /**
     * Add a new MySQL database to a cPanel account.
     * @param string $dbname
     * The name of the MySQL database you wish to add.
     * The cPanel account's username will automatically be prepended to the
     * name of the database. (e.g. Entering 'dbname' would result in 'cpuser_dbname').
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_adddb
     */
    public function adddb($dbname){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbname)));
    }

    /**
     * Authorize a remote host to access a cPanel account's MySQL users.
     * @param string $dbhost
     * The IP address or hostname you wish to allow access.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_addhost
     */
    public function addhost($dbhost){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbhost)));
    }

    /**
     * Create a new MySQL user.
     * @param string $dbuser
     * The MySQL user you wish to create. The account's username will automatically
     * be prepended to this value. (e.g. Entering 'user' here would result in 'cpuser_user').
     * @param string $password
     * 	 The password for the new MySQL user.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_adduser
     */
    public function adduser($dbuser, $password){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbuser, $password)));
    }

    /**
     * Grant a user permission to access a database within a cPanel account.
     * @param string $dbname
     * The name of the database you wish to allow the user to access.
     * @param string $dbuser
     * The MySQL user you wish to give access to the database.
     * @param string $paerms
     * A comma-separated list of permissions you wish to grant the user.
     * (e.g. "all" or "alter,drop,create,delete,insert,update,lock" ).
     * Possible values with corresponding MySQL permissions are as follows:<br/>
     * alter => ALTER<br/>
     * temporary => CREATE TEMPORARY TABLES<br/>
     * routine => CREATE ROUTINE<br/>
     * create => CREATE<br/>
     * delete => DELETE<br/>
     * drop => DROP<br/>
     * select => SELECT<br/>
     * insert => INSERT<br/>
     * update => UPDATE<br/>
     * references => REFERENCES<br/>
     * index => INDEX<br/>
     * lock => LOCK TABLES<br/>
     * all => ALL
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_adduserdb
     */
    public function adduserdb($dbname, $dbuser, $perms){
        $this->cpanel_api_ver = 'api1';
        if(!is_string($perms))
            throw new Exception('"perms" must be a string');
        $perms = array_map('trim', explode(',', $perms));
        if(array_search('all', $perms) !== false)
            return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbname, $dbuser, 'all')));
        else{
            for($i = 0, $cnt = count($perms); $i < $cnt; $i++){
                if(array_search($perms[$i], $this->allowed_permissions) === false)
                    unset($perms[$i]);
            }
            $perms = implode(',', $perms);
            return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbname, $dbuser, $perms)));
        }
    }

    /**
     * Remove a database from MySQL.
     * @param string $dbname
     * The name of the database you wish to remove from MySQL.
     * You must prefix this value with the cPanel username. (e.g. cpuser_dbname).
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_deldb
     */
    public function deldb($dbname){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbname)));
    }

    /**
     * Remove host access permissions from MySQL.
     * @param string $dnhost
     * The IP address of the host whose access you wish to revoke.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_delhost
     */
    public function delhost($dbhost){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbhost)));
    }

    /**
     * Remove a user from MySQL.
     * @param string $dbuser
     * The name of the MySQL user you wish to remove.
     * You must prefix this value with the cPanel username. (e.g. cpuser_dbuser).
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_deluser_AN1
     */
    public function deluser($dbuser){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbuser)));
    }

    /**
     * Disallow a MySQL user to access a database.
     * @param string $dbname
     * The MySQL database from which you wish to remove the user's permissions.
     * @param string $dbuser
     * The name of the MySQL user you wish to remove.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_deluserdb
     */
    public function deluserdb($dbname, $dbuser){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbname. $dbuser)));
    }

    /**
     * Get the address of the MySQL host that is used by the server
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_gethost
     */
    public function gethost(){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__));
    }

    /**
     * Refresh the cache of MySQL information. This includes users, databases,
     * routines and other related information.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_initcache
     */
    public function initcache(){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__));
    }

    /**
     * Retrieve the number of databases currently in use.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_number_of_dbs
     */
    public function number_of_dbs(){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__));
    }

    /**
     * Retrieve the number of database users an account has created.
     * @param string $dbuser
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_number_of_dbs_AN1
     */
    public function number_of_userdbs($dbuser){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbuser))); //!possible function name is incorrect
    }

    /**
     * Force an update of MySQL privileges and passwords.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_updateprivs
     */
    public function updateprivs(){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__));
    }

    /**
     * Run a MySQL database repair.
     * @param string $dbname The name of the MySQL database you wish to repair.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_repairdb
     */
    public function repairdb($dbname){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbname)));
    }

    /**
     * Run a MySQL database check.
     * @param string $dbname
     * The name of the MySQL database you wish to check.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_deluser
     */
    public function checkdb($dbname){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__, array($dbname)));
    }

    /**
     * List MySQL routines created by a user. 
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiMysql#Mysql_routines
     */
    public function routines(){
        $this->cpanel_api_ver = 'api1';
        return $this->_check_result($this->api1_query($this->user, 'Mysql', __FUNCTION__));
    }

}

