<?php
class NavigationController extends Zend_Controller_Action 
{
    public function init()
    {
        $softlayerApi = new Zend_Session_Namespace('softlayerApi');
        $this->view->sessionData = $softlayerApi;
    }

    public function indexAction() 
    {
    }

    public function menuAction()
    {
        $menuItems = array();

        $menuItems['/index'] = (object)array('href' => '/index', 'text' => 'home');

        if ($this->view->sessionData->username != null) {
            $menuItems['/hardware'] = (object)array('href' => '/hardware', 'text' => 'hardware');
        }

        list($nill, $requestedController) = explode('/', $this->getRequest()->getRequestUri());

        $currentPage = '/' . $requestedController;

        if ($currentPage == '') {
            $currentPage = '/index';
        }

        if (array_key_exists('/' . $requestedController, $menuItems)) {
            $menuItems[$currentPage]->class = 'active';
        }

        $this->view->menuItems = $menuItems;
    }

    public function sidebarAction()
    {
    }
}
