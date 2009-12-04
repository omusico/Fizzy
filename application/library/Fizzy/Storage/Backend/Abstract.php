<?php
/**
 * Class Fizzy_Storage_Backend_Abstract
 * @package Fizzy
 * @subpackage Storage
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

/** Fizzy_AutoFill */
require_once 'Fizzy/AutoFill.php';

/** Fizzy_Storage_Backend_Interface */
require_once 'Fizzy/Storage/Backend/Interface.php';

/**
 * Abstract that can be extended by storage backend implementation. Has
 * convenience methods.
 *
 * @author Mattijs Hoitink <mattijs@voidwalkers.nl>
 */
abstract class Fizzy_Storage_Backend_Abstract extends Fizzy_AutoFill 
    implements Fizzy_Storage_Backend_Interface
{

    /**
     * DSN for the storage backend.
     * @var string
     */
    protected $_dsn = null;

    /**
     * Field used to store the identifier for a dataset.
     * @var string
     */
    protected $_identifierField = 'id';

    /** **/

    /**
     * Sets the DSN for the storage backend.
     * @param string $dsn
     * @return Fizzy_Storage_Backend_Abstract
     */
    public function setDsn($dsn)
    {
        $this->_dsn = $dsn;
        return $this;
    }

    /**
     * Returns the DSN for the storage backend.
     * @return string
     */
    public function getDsn()
    {
        return $this->_dsn;
    }

    /**
     * Returns the field used by the backend to store the identifier for a
     * dataset.
     * @return string
     */
    public function getIdentifierField()
    {
        return $this->_identifierField;
    }

}