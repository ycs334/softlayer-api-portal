<?php
class DemoController extends Zend_Controller_Action
{
    public function getForm()
    {
        $form = new Zend_Form();
        $form->setAction('/demo/login')
             ->setMethod('post');

        $username   = $form->createElement('text', 'username', array('label' => 'Username'));
        $key        = $form->createElement('password', 'key', array('label' => 'API Key'));

        $form->addElement($username)
             ->addElement($key)
             ->addElement('submit', 'login', array('label' => 'Login'));

        return $form;
    }

    public function indexAction()
    { 
        $softlayerApi = new Zend_Session_Namespace('softlayerApi');

        if ($softlayerApi->account == null) {
            $this->view->form = $this->getForm();
        } else {
            $this->view->account = $softlayerApi->account;
        }
    }

    public function loginAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->_forward('/index');
        }

        $form = $this->getForm();
        
        if (!$form->isValid($_POST)) {
            $this->view->form = $form;
            $this->render('index');
        }

        $values = $form->getValues();

        SoftLayer_Soap_Client::setApiCredentials($values['username'], $values['key']);

        // check if the account record can be fetched
        try {
            $account = SoftLayer_Soap_Client::getSoapClient('SoftLayer_Account')->getObject();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        Zend_Session::regenerateId();

        $softlayerApi = new Zend_Session_Namespace('softlayerApi');

        $softlayerApi->account = $account;
        $softlayerApi->username = $values['username'];
        $softlayerApi->key = $values['key'];

        $this->_redirect('/index');
    }

    public function logoutAction()
    {
        Zend_Session::destroy();
        $this->_redirect('/index');
    }
}
