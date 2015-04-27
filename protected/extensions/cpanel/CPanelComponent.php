<?php
Yii::import('ext.cpanel.lib.*');

/**
 * cPanel XMLAPI extension Client Class
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
 */
class CPanelComponent extends CComponent {
    public $defaultPort = 2083;

    public $defaultAuthType = 'pass';

    public $defaultProtocol = 'https';

    public $defaultHTTPClient = 'curl';

    public $defaultOutput = 'array';

    /**
     * @var array $cpanelXMLAPIWrappers
     */
    private $cpanelXMLAPIWrappers = array();

    public function init(){

    }

    public function getInstance($wrapper, $config = array()){
        if(!isset($this->cpanelXMLAPIWrappers[$wrapper]) && empty($config))
            throw new CException('Can not get new instance of the "xmlapiext. "config" is empty');

        $className = 'xmlapi_'.$wrapper;

        if(!class_exists($className))
            throw new Exception("'{$wrapper}' not exist");

        if(!isset($this->cpanelXMLAPIWrappers[$wrapper])){
            $this->cpanelXMLAPIWrappers[$wrapper] = new $className($config['host']);
            $this->cpanelXMLAPIWrappers[$wrapper]->host = $config['host'];
            $this->cpanelXMLAPIWrappers[$wrapper]->port = isset($config['port'])?$config['port']:$this->defaultPort;
            $this->cpanelXMLAPIWrappers[$wrapper]->protocol = isset($config['protocol'])?$config['protocol']:$this->defaultProtocol;
            $this->cpanelXMLAPIWrappers[$wrapper]->http_client = isset($config['http_client'])?$config['http_client']:$this->defaultHTTPClient;
            $this->cpanelXMLAPIWrappers[$wrapper]->auth_type = isset($config['auth_type'])?$config['auth_type']:$this->defaultAuthType;
            $this->cpanelXMLAPIWrappers[$wrapper]->output = isset($config['output'])?$config['output']:$this->defaultOutput;

            if(!isset($config['user']))
                throw new CException('"username" is not specified');
            $this->cpanelXMLAPIWrappers[$wrapper]->user = $config['user'];


            if($this->cpanelXMLAPIWrappers[$wrapper]->auth_type == 'pass'){
                if(!isset($config['password']))
                    throw new CException('Auth type "pass" is selected, but "password" is not specified');

                $this->cpanelXMLAPIWrappers[$wrapper]->password = $config['password'];
            }
            else{
                // auth type hash
                if(!isset($config['hash']))
                    throw new CException('Auth type "hash" is selected, but "has" is not specified');
                $this->cpanelXMLAPIWrappers[$wrapper]->hash = $config['hash'];
            }
        }

        return $this->cpanelXMLAPIWrappers[$wrapper];
    }


}
