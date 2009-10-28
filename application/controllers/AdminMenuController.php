<?php

require_once 'SecureController.php';

class AdminMenuController extends SecureController
{

    public function defaultAction()
    {
    }

    public function showAction()
    {
        $config = Fizzy_Config::getInstance();
        $storageOptions = $config->getSection('storage');
        $this->_storage = new Fizzy_Storage($storageOptions);

        $pages = $this->_storage->fetchAll('page');
        $this->getView()->pages = $pages;
        $this->getView()->setScript('admin/menu.phtml');
    }
}
