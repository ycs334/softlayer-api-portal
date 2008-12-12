<?php

class Softlayer_Soap_Client extends SoapClient
{

    protected $_headers = array();
    protected $_serviceName;

    protected static $_apiUser;
    protected static $_apiKey;

    protected static $_endpoint;

    public function __call($functionName, $arguments = null)
    {
        return parent::__call($functionName, $arguments, null, $this->_headers, null);
    }

    public static function setApiCredentials($apiUser, $apiKey)
    {
        self::$_apiUser = $apiUser;
        self::$_apiKey  = $apiKey;
    }

    public static function setEndpoint($endpoint)
    {
        self::$_endpoint = $endpoint;
    }

    public static function getSoapClient($serviceName, $id = null)
    {
        $soapClient = new SoftLayer_Soap_Client(self::$_endpoint.$serviceName.'?wsdl');
        $soapClient->addAuthenticationHeaders(self::$_apiUser, self::$_apiKey);
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
}
