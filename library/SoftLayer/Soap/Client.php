<?php

class Softlayer_Soap_Client extends SoapClient
{

    protected $_headers = array();
    protected $_serviceName;

    protected static $_user;
    protected static $_key;

    protected static $_endpoint;

    protected $_outputHeaders = array();

    public function __call($functionName, $arguments = null)
    {
        return parent::__call($functionName, $arguments, null, $this->_headers, $this->_outputHeaders);
    }

    public static function setApiCredentials($apiUser, $apiKey)
    {
        self::$_user = $apiUser;
        self::$_key  = $apiKey;
    }

    public static function setEndpoint($endpoint)
    {
        self::$_endpoint = $endpoint;
    }

    public static function getSoapClient($serviceName, $id = null)
    {
        $soapClient = new SoftLayer_Soap_Client(self::$_endpoint.$serviceName.'?wsdl');
        $soapClient->addAuthenticationHeaders(self::$_user, self::$_key);
        $soapClient->_serviceName = $serviceName;

        if ($id != null) {
            $initParameters = new stdClass();
            $initParameters->id = $id;
            $soapClient->addHeader($serviceName.'InitParameters', $initParameters);
        }

        return $soapClient;
    }

    public function addHeader($headerName, $value)
    {
        $this->_headers[$headerName] = new SoapHeader('http://api.service.softlayer.com/soap/v3/', $headerName, $value);
    }

    public function addAuthenticationHeaders($username, $apiKey)
    {
        $header = new stdClass();
        $header->username = $username;
        $header->apiKey   = $apiKey;

        $this->addHeader('authenticate', $header);
    }

    public function setObjectMask($mask)
    {
        $objectMask = new stdClass();
        $objectMask->mask = $mask;

        $this->addHeader($this->_serviceName.'ObjectMask', $objectMask);
    }

    public function setObjectFilter($filter)
    {
        $this->addHeader($this->_serviceName.'ObjectFilter', $filter);
    }

    public function setResultLimitHeader($limit, $offset = 0)
    {
        $resultLimit = new stdClass();
        $resultLimit->limit = intval($limit);
        $resultLimit->offset = intval($offset);

        $this->addHeader('resultLimit', $resultLimit);
    }

    public function getOutputHeader($headerName)
    {
        if ($headerName == 'totalItems') {
            return $this->_outputHeaders[$headerName]->amount;
        }

        return $this->_outputHeaders[$headerName];
    }

}
