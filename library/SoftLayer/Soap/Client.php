<?php

class Softlayer_Soap_Client extends SoapClient
{

    protected $headers = array();
    protected $serviceName;

    protected static $apiUser;
    protected static $apiKey;

    protected static $endpoint;

    public function __call($functionName, $arguments = null)
    {
        return parent::__call($functionName, $arguments, null, $this->headers, null);
    }

    public static function setApiCredentials($apiUser, $apiKey)
    {
        self::$apiUser = $apiUser;
        self::$apiKey  = $apiKey;
    }

    public static function setEndpoint($endpoint)
    {
        self::$endpoint = $endpoint;
    }

    public static function getSoapClient($serviceName, $id = null)
    {
        $soapClient = new SoftLayer_Soap_Client(self::$endpoint.$serviceName.'?wsdl');
        $soapClient->addAuthenticationHeaders(self::$apiUser, self::$apiKey);
        $soapClient->serviceName = $serviceName;

        if ($id != null) {
            $initParameters = new stdClass();
            $initParameters->id = $id;
            $soapClient->addHeader($serviceName.'InitParameters', $initParameters);
        }

        return $soapClient;
    }

    public function addHeader($headerName, $value)
    {
        $this->headers[$headerName] = new SoapHeader('http://api.service.softlayer.com/soap/v3/', $headerName, $value);
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

        $this->addHeader($this->serviceName.'ObjectMask', $objectMask);
    }
}
