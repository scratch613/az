<?php
/**
 * XMLAPI wrapper class for cPanel Park module
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
 * @version 1.0.0
 * @see http://docs.cpanel.net/twiki/bin/view/ApiDocs/ApiPark
 *
 * Last Updated: 02 June 2010
 *
 * Changes
 *
 * 1.0.0
 * - added main Park calls
 *
 */
class xmlapi_park extends xmlapi_base{
    public function __construct($host, $user = null, $password = null) {
        parent::__construct($host, $user, $password);
    }

    /**
     * Park a domain on top of another domain.
     * @param string $domain The domain name you wish to park.
     * @param string $topdomain The domain on top of which the parked domain will be parked.
     * @return struct
     * <ul>
     *   <li>reason => <i>string</i></li>
     *   <li>result => <i>boolean</i></li>
     * </ul>
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/Api2/ApiPark#Park_park
     */
    public function park($domain, $topdomain){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Park', __FUNCTION__, array(
            'domain' => $domain,
            'topdomain' => $topdomain
        )));
    }

    /**
     * Remove a parked domain.
     * @param string $domain The domain name of the parked domain you wish to remove.
     * @return struct
     * <ul>
     *   <li>reason => <i>string</i></li>
     *   <li>result => <i>boolean</i></li>
     * </ul>
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/Api2/ApiPark#Park_unpark
     */
    public function unpark($domain){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Park', __FUNCTION__, array(
            'domain' => $domain,
        )));
    }

    /**
     * Retrieve a list of parked domains associated with a cPanel account.
     * @param string $regex This will only return domains that match against the provided regular expression.
     * @return array
     * <ul>
     *   <li>
     *     <ul>
     *       <li>domain => <i>string</i> A string value that contains the parked domain's domain name.</li>
     *       <li>status => <i>string</i> A string value that indicates whether or not the parked domain redirects to another domain.</li>
     *       <li>reldir => <i>string</i> A string value that contains the relative path to the domain's document root prefixed by 'home:'. (e.g. home:public_html/addon-example.com)</li>
     *       <li>dir => <i>string</i> A string value that contains the absolute path to the document root. (e.g. /home/user/public_html/parked-domain.com/)</li>
     *       <li>basedir => <i>string</i> A string value that contains the relative path to the domain's document root. This value is not prefixed by 'home:'. (e.g. public_html/addon-example.com/)</li>
     *     </ul>
     *   </li>
     *   <li>...</li>
     * </ul>
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/Api2/ApiPark#Park_listparkeddomains
     */
    public function listparkeddomains($regex = ''){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Park', __FUNCTION__, array(
            'regex' => $regex,
        )));
    }

    /**
     * Retrieve a list of addon domains associated with a cPanel account.
     * @param String $regex Allows you to search for a specific addon domain by specifying a specific string or regular expression to match against the "domain" key in the returned data.
     * @return array
     * <ul>
     *   <li>
     *     <ul>
     *       <li>domainkey => <i>string</i> The addon domain's domain key. This value includes the cPanel username and main domain separated by an underscore (_). (e.g. username_maindomain.com)</li>
     *       <li>domain => <i>string</i> The addon domain's domain name. (e.g. addon-example.com)</li>
     *       <li>rootdomain => <i>string</i> The cPanel account's main domain.</li>
     *       <li>status => <i>string</i> Indicates whether or not the addon domain redirects to another domain.</li>
     *       <li>fullsubdomain => <i>string</i> The subdomain that corresponds to the addon domain. (e.g. username.maindomain.com)</li>
     *       <li>subdomain => <i>string</i> The username that corresponds to the subdomain and addon domain. (e.g. username)</li>
     *       <li>reldir => <i>string</i> The relative path to the domain's document root prefixed by 'home:'. (e.g. home:public_html/addon-example.com/)</li>
     *       <li>dir => <i>string</i> The absolute path to the domain's document root. (e.g. /home/user/public_html/addon-example.com/)</li>
     *       <li>basedir => <i>string</i> The relative path to the domain's document root. This value is not prefixed by 'home:'. (e.g. public_html/addon-example.com/)</li>
     *     </ul>
     *   </li>
     *   <li>...</li>
     * </ul>
     * @link http://docs.cpanel.net/twiki/bin/view/ApiDocs/Api2/ApiPark#Park_listaddondomains
     */
    public function listaddondomains ($regex = ''){
        $this->cpanel_api_ver = 'api2';
        return $this->_check_result($this->api2_query($this->user, 'Park', __FUNCTION__, array(
            'regex' => $regex,
        )));
    }

}
