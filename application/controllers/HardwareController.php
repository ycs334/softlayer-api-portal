<?php
class HardwareController extends Zend_Controller_Action
{
    public function indexAction()
    { 
        $soapClient = SoftLayer_Soap_Client::getSoapClient('SoftLayer_Account');

        $paginator = new Zend_Paginator(new SoftLayer_Paginator_Adapter_Soap($soapClient, 'getHardware'));
        $paginator->setItemCountPerPage(25);
        $paginator->setCurrentPageNumber($this->_getParam('page'));

        $this->view->paginator = $paginator;
    }
}
