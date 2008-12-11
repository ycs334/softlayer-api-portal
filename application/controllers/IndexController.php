<?php
class IndexController extends Zend_Controller_Action 
{
    public function indexAction() 
    {
        $soapClient = SoftLayer_Soap_Client::getSoapClient('SoftLayer_Account');

        $objectMask = new SoftLayer_Soap_ObjectMask();
        $objectMask->hardware->softwareComponents;

        $soapClient->setObjectMask($objectMask);

        $this->view->account = $soapClient->getObject();
    }
}

