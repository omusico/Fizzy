<?php
/**
 * Class Admin_WebdavController
 * @category Fizzy
 * @package Admin
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.voidwalkers.nl/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@voidwalkers.nl so we can send you a copy immediately.
 *
 * @copyright Copyright (c) 2009-2010 Voidwalkers (http://www.voidwalkers.nl)
 * @license http://www.voidwalkers.nl/license/new-bsd The New BSD License
 */

/**
 * Controller that handles WebDAV requests.
 *
 * @author Mattijs Hoitink <mattijs@voidwalkers.nl>
 */
class Admin_WebdavController extends Zend_Controller_Action
{
    /**
     * Application config
     * @var Zend_Config
     */
    protected $_config = null;

    public function init()
    {
        // Disable view rendering
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();

        // Get the application config
        $this->_config = Zend_Registry::get('config');
    }

    /**
     * Handles a WebDAV request.
     */
    public function requestAction()
    {
        if (!isset($this->_config->resources->sabredav->enabled) ||
                0 == $this->_config->resources->sabredav->enabled) {
            // Render 404
            $response = $this->getResponse();
            $response->clearAllHeaders();
            $response->clearBody();
            $response->setHttpResponseCode(404);
            $response->sendResponse();
            return;
        }
        
        $baseUri = $this->view->url('@admin_webdav');
        $publicDir = ROOT_PATH . '/public/uploads';
        $tmpDir = ROOT_PATH . '/data/tmp';

        $auth = new Sabre_HTTP_BasicAuth();
        $auth->setRealm('Fizzy');
        
        $authResult = $auth->getUserPass();
        if (false === $authResult) {
            $auth->requireLogin();
            die('Authentication required');
        }

        list($username, $password) = $authResult;
        
        $authAdapter = new Fizzy_Doctrine_AuthAdapter($username, $password);
        $authResult = $authAdapter->authenticate();

        if ($authResult->getCode() !== Zend_Auth_Result::SUCCESS) {
            $auth->requireLogin();
            die('Authentication failed');
        }

        $publicDirObj = new Sabre_DAV_FS_Directory($publicDir);
        $objectTree = new Sabre_DAV_ObjectTree($publicDirObj);

        $server = new Sabre_DAV_Server($objectTree);
        $server->setBaseUri($baseUri);

        if (isset($this->_config->resources->sabredav->browser) &&
                false != $this->_config->resources->sabredav->browser) {
            $browser = new Sabre_DAV_Browser_Plugin();
            $server->addPlugin($browser);
        }

        $server->exec();
    }

    
}