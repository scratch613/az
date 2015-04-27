<?php
/**
 * Base class for all cPanel XMLAPI wrappers
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
 * @version 1.1.1
 *
 * Last Updated: 17 April 2010
 *
 * Changes
 *
 * 1.1.1
 * - fixed _check_result for error responce
 *
 * 1.1.0
 * - fixed calls for API2 in classes
 *
 * 1.0.0
 * - magic getter and setter
 *
 */
class xmlapi_base extends xmlapi{
    public $defaultValue = null;
    public $exceptionOnInvalidProperty = false;

    protected $result;

    protected $cpanel_api_ver = 'api1';

    public function __construct($host, $user = null, $password = null){
        parent::__construct($host, $user, $password);
    }

    /************************** EOF - MAGIC, GETTER, SETTER ************************/
    
    public function __set($name, $value){
        $setter = 'set_'.$name;
        if(method_exists($this, $setter)){
            $this->$setter($value);
        }
        else{
            throw new Exception("Property '{$name}' not exist or readonly");
        }
    }

    public function __get($name){
        $getter = 'get_'.$name;
        if(method_exists($this, $getter)){
            return $this->$getter();
        }
        else{
            if($this->exceptionOnInvalidProperty)
                throw new Exception("Property '{$name}' not exist or readonly");
            else
                return $this->defaultValue;
        }
    }

    public function get_result(){
        return $this->result;
    }

    /************************** EOF - MAGIC, GETTER, SETTER ************************/

    /************************** BOF - HELPER FUNCTIONS ************************/
    protected function _check_result($res = null){
        if($res !== null)
            $this->result = $res;
        switch($this->output){
            case 'json':
                $t = json_decode($this->result, true);
                if(isset($t['cpanelresult']['event']['result']) && ($t['cpanelresult']['event']['result'] == '1')) return $this->result;
                else return false;
                break;
            case 'xml':
                $t = simplexml_load_string($this->result, null, LIBXML_NOERROR | LIBXML_NOWARNING);
                if(isset($t->event->result) && ($t->event->result == '1')) return $this->result;
                else return false;
                break;
            case 'array':
                if(isset($this->result['event']['result']) && ($this->result['event']['result'] == '1')) {
                    switch($this->cpanel_api_ver){
                        case 'api1':
                            if(isset($this->result['data']['result']))
                                return $this->result['data']['result'];
                            else
                                return null;
                        case 'api2':
                            if(isset($this->result['data']))
                                return $this->result['data'];
                            else
                                return null;
                    }
                }
                else return false;
                break;
            case 'simplexml':
                if(isset($this->result->event->result) && ($this->result->event->result == '1')){
                    switch($this->cpanel_api_ver){
                        case 'api1': return $this->result->data->result;
                        case 'api2': return $this->result->data;
                    }
                }
                else return false;
                break;
            default:
                return $this->result;
        }
    }

    /************************** EOF - HELPER FUNCTIONS ************************/


}
