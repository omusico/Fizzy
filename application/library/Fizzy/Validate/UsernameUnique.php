<?php
/**
 * Class Fizzy_Validate_UsernameUnique
 * @category Fizzy
 * @package Fizzy_Validate
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
 * @copyright Copyright (c) 2009 Voidwalkers (http://www.voidwalkers.nl)
 * @license http://www.voidwalkers.nl/license/new-bsd The New BSD License
 */

/** Zend_Validate_Abstract */
require_once 'Zend/Validate/Abstract.php';

/**
 * Form validator to check if a username is unique. Advises the Fizzy_Storage
 * to check this.
 *
 * @author Mattijs Hoitink <mattijs@voidwalkers.nl>
 */
class Fizzy_Validate_UsernameUnique extends Zend_Validate_Abstract
{
    const NOT_UNIQUE = 'notUnique';

    protected $_messageTemplates = array (
        self::NOT_UNIQUE => 'Username is not unique.'
    );

    /** **/

    /**
     * Check if a username is valid (unique).
     * @param string $value
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);

        $storage = Fizzy::getInstance()->getStorage();
        $users = $storage->fetchAll('User');
        $usernames = array();
        foreach($users as $user) {
            $usernames[$user->getId()] = $user->username;
        }

        # Check if we are editing the page the slug belongs to
        $editingOriginal = false;
        if(is_array($context) && isset($context['id'])) {
            $id = $context['id'];
            if(array_key_exists($id, $usernames) && $value === $usernames[$id]) {
                $editingOriginal = true;
            }
        }

        if(in_array($value, $usernames) && !$editingOriginal) {
            $this->_error(self::NOT_UNIQUE);
            return false;
        }

        return true;
    }
}