<?php

class Admin_IndexController extends Fizzy_SecuredController
{

    protected $_sessionNamespace = 'fizzy';
    protected $_redirect = '/fizzy/login';

    /**
     * Default action redirects to Pages overview.
     */
    public function indexAction()
    {
        $this->_redirect('/fizzy/pages');
    }

    public function configurationAction()
    {
        $this->view->config = Zend_Registry::get('config')->toArray();
        $this->renderScript('configuration.phtml');
    }
    
}
