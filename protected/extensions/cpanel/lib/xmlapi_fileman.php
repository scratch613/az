<?php
/**
 * XMLAPI wrapper class for cPanel Fileman module
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
 * @see http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman
 *
 * Last Updated: 17 April 2010
 *
 * Changes
 *
 * 1.1.0
 * - fixed calls for API2
 *
 * 1.0.0
 * - added main Fileman calls
 *
 */
class xmlapi_fileman extends xmlapi_base {

    private $allowed_operations = array('copy', 'move', 'rename', 'chmod', 'extract', 'compress', 'link', 'unlink', 'trash');
    private $allowed_metadata = array(
        'compress' => array( 'zip', 'tar.gz', 'tar.bz2', 'tar', 'gz', 'bz2'),
        //for 'chmod' check int
    );

    public function __construct($host, $user = null, $password = null) {
        parent::__construct($host, $user, $password);
    }

    /**
     * Search for files and directories that begin with a specified string.
     * @param string $path
     * The full path to the directory you wish to search. (e.g. /home/user/public_html/test/).
     * @param boolean $dirsonly
     * When this parameter is passed a value of <b>true</b> only directories are returned.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_autocompletedir
     */
    public function autocompletedir($path, $dirsonly = false) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'path' => $path,
            'dirsonly' => (int)$dirsonly,
        )));
    }

    /**
     * Perform an operation on a file or group of files. You can use this
     * function to copy, move, rename, chmod, extract and compress, link
     * and unlink, and trash files and directories.
     * @param string $op
     * 'copy', 'move', 'rename', 'chmod', 'extract', 'compress', 'link', 'unlink', 'trash'
     * @param string $sourcefiles
     * The files on which you wish to perform the operation. You can include
     * multiple files by separating each file with a comma (,). Do not add spaces.
     * Related to the home directory, without leading slash
     * @param string $destfiles
     * A comma separated list of destination filenames. If multiple sourcefiles
     * are listed with multiple destination files ('destfiles'), the function
     * attempts to handle each transaction on a 1-to-1 basis. If only 1 file is
     * specified in 'sourcefiles,' it will be moved, or copied, or etc. to the
     * first directory listed.
     * Related to the home directory.
     * @param string|int $metadata
     * This parameter contains any added values required by the operation.
     * When using 'compress,' this would be the archive type. Acceptable values
     * for the 'compress' operation include 'zip', 'tar.gz', 'tar.bz2', 'tar', 'gz', 'bz2'. 
     * The 'chmod' operation requires octal permissions (e.g. '0755' or '0700').
     * @param boolean $doubledecoded
     * Entering '1' to this parameter will cause the function to
     * decode the URI-encoded variables 'srcfiles' and 'destfiles'.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_fileop
     */
    public function fileop($op, $sourcefiles, $destfiles, $metadata = '', $doubledecoded = false) {
        $this->cpanel_api_ver = 'api2';

        if(!in_array($op, $this->allowed_operations))
            throw new Exception("'{$op}' not allowed");

        switch($op) {
            case 'chmod':
                if(is_int($metadata))
                    $metadata = sprintf('%o', $metadata);
                break;
            case 'compress':
                if(!in_array($metadata, $this->allowed_metadata['compress']))
                    throw new Exception("'{$metadata}' not allowed for '{$op}' operation. Use ona of next:'".implode('\', \'',$this->allowed_metadata['compress']));
                break;
        }

        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'op' => $op,
            'sourcefiles' => $sourcefiles,
            'destfiles' => $destfiles,
            'doubledecoded' => (int)$doubledecoded,
            'metadata' => $metadata
        )));
    }

    /**
     * List cPanel's current working directory. By default, this will be /usr/local/cpanel/base.
     * @param string $dir
     * The path you wish to be returned by this function. If you pass this parameter
     * a relative value, it will be appended to /usr/local/cpanel/base.
     * If you pass this parameter an absolute value, the function will return this value.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_getabsdir
     */
    public function getabsdir($dir = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir
        )));
    }

    /**
     * Retrieve the home directory path for a given account in URI format.
     * @param string $dir
     * The directory you wish to retrieve in URI format.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_getdir
     */
    public function getdir($dir = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir
        )));
    }

    /**
     * Retrieve a list of actions you are able to perform on a file or directory.
     * These values will include a URL fragment that you can append to a
     * cPanel File Manager URL.
     * @param string $dir
     * The relative directory that contains the files you wish to query. '/' is your home directory.
     * @param string $file
     * The file or directory for which you wish to query.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_getdiractions
     */
    public function getdiractions($dir, $file) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir,
            'file' => $file
        )));
    }

    /**
     * Retrieve disk usage statistics about your account. These values will
     * include your quota and the amount of disk space used by your cPanel account.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_getdiskinfo
     */
    public function getdiskinfo() {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__));
    }

    /**
     * Attempt to guess the filetype of a specific file. This module uses the
     * 'file' command and searches through file extensions. You must have access
     * to the 'filemanager' feature to use this function.
     * @param string $dir
     * The directory that contains the file you wish to query.
     * @param string $file
     * The name of the file you wish to query.
     * @param string $editor
     * By setting this parameter to 'editarea,' certain filetypes will return a
     * long-style name. (e.g. 'javascript' instead of 'js').
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_getedittype
     */
    public function getedittype($dir, $file, $editor = 'editarea') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir,
            'editor' => $editor,
            'file' => $file
        )));
    }

    /**
     * Returns actions that can be performed on a file, including a URL fragment
     * which can be appended to a cPanel File Manager URL.
     * @param string $dir
     * Base directory for the file or directory you want to query about.
     * Note that it requires the full (absolute) path.
     * @param string $file
     * File or directory name to query for.
     * @param boolean $newedit
     * Will cause certain output functions to create landing page URL's and
     * targets for 'file' instead of 'editor'.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_getfileactions
     */
    public function getfileactions($dir, $file, $newedit = false) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir,
            'file' => $file,
            'newedit' => (int)$newedit
        )));
    }

    /**
     * Split a specific directory into individual parts.
     * @param string $dir
     * The URI-encoded path you wish to split into individual parts.
     * (e.g. %2fhome%2fuser%2fpublic_html%2fdirectory9%2fsubdirectory1).
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_getpath
     */
    public function getpath($dir) {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir
        )));
    }

    /**
     * List files and attributes contained within a specified directory.
     * @param string $dir
     * The directory that contains the files you wish to browse. '/' represents
     * your home directory. If this parameter is not specified, the home directory
     * will be used. Related to the home directory.
     * @param boolean $checkleaf
     * A value of '1' will cause the function to add the 'isleaf' parameter to
     * the output key. '1' if you would like to add it to the output key. (e.g.
     * 'checkleaf' => '1' indicates a directory that has no subdirectories.).
     * @param boolean $filelist
     * A value of '1' tells the function to look for keys that begin with
     * 'filepath-*.' These keys are used to indicate specific files to list.
     * @param array $filepath
     * This parameter allows you to specify files you want listed with the output
     * if 'filelist' is set to '1.' This can be any number of parameters, such as
     * 'filelist-A', 'filelist-B', etc. Each of these keys indicate a file you wish
     * to view.
     * @param boolean $needmime
     * A value of '1' indicates that you would like the function to add the
     * 'mimename' and 'mimetype' output keys.
     * @param boolean $showdotfiles
     * A value of '1' indicates that you would like the function to add dotfiles
     * to the output keys.
     * @param string $types
     * This parameter acts as a filter, allowing you to specify which file types
     * you wish to view. Acceptable values include 'dir', 'file', and 'special'.
     * To add multiple values, separate each type using a pipe (|). For example,
     * 'dir | file' would only show directories and files.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_listfiles
     */
    public function listfiles($dir = '', $checkleaf = true, $filelist = false, $filepath=array(), $needmime = true, $showdotfiles = true, $types = 'dir | file') {
        $this->cpanel_api_ver = 'api2';

        $args = array(
            'checkleaf' => (int)$checkleaf,
            'dir' => $dir,
            'needmime' => (int)$needmime,
            'showdotfiles' => (int)$showdotfiles,
            'types' => $types
        );

        if(!empty($types)){
            $types = implode('|', array_map('trim', explode('|', $types)));
            $args['types'] = $types;
        }

        if($filelist){
            if(!empty($filepath)){
                $args['filelist'] = 1;
                foreach($filepath as $fp=>$v){
                    if(!preg_match('/^filepath-/'))
                        $args['filepath-'.$fp] = $v;
                    else{
                        $args[$fp] = $v;
                    }
                }
            }
        }
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, $args));
    }

    /**
     * Perform a recursive search for filenames within a specific directory.
     * You must have access to the 'filemanager' feature to use this function.
     * @param string $dir
     * The top-level directory in which you wish to begin your search. Remember:
     * Subdirectories of this directory will also be searched. If you do not
     * specify this parameter, it will default to your home directory.
     * @param string $regex
     * A regular expression (or string) that indicates the file to be searched.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_search
     */
    public function search($dir = '', $regex = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir,
            'regex' => $regex
        )));
    }

    /**
     * Retrieve information about specific files.
     * @param string $file
     * The name of the file whose statistics you wish to review. You may define
     * multiple files by separating each value with a pipe (|). (e.g. 'file1|file2|file3').
     * @param string $dir
     * The directory whose files you wish to review. (e.g. /home/user/public_html/files)
     * If you do not specify a value for this parameter, it will default to your home directory.
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_statfiles
     */
    public function statfiles($file, $dir = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir,
            'file' => $file
        )));
    }

    /**
     * View a file within your home directory. This function will also display
     * additional information about the file, such as the contents of a tarball,
     * a link to an image, and more.
     * @param string $file
     * The path to the file you wish to view.
     * @param string $dir
     * The directory in which the file is located. This path must be relative
     * to the user's home directory. (e.g. 'public_html/myfiles/').
     * @return mixed
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiFileman#Fileman_viewfile
     */
    public function viewfile($file, $dir = '') {
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Fileman', __FUNCTION__, array(
            'dir' => $dir,
            'file' => $file
        )));
    }

}

