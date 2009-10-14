<?php
require_once 'Fizzy/Storage/Config.php';
require_once 'Fizzy/Storage/SQLite.php';
require_once 'Fizzy/Storage/XML.php';
require_once 'Fizzy/Storage/Exception/InvalidConfig.php';


/**
 * Main storage Class. You should only use this class to communicate with the
 * persistence layer.
 *
 * @author Jeroen Tietema <jeroen@voidwalkers.nl>
 */
class Fizzy_Storage
{
    /**
     * The config object.
     * @var Fizzy_Storage_Config
     */
    protected $_config = null;

    /**
     * The persistence driver used.
     * @var Fizzy_Storage_Interface
     */
    protected $_driver = null;

    /**
     * The constructor.
     * Loads the config and instanciates the driver.
     */
    public function __construct()
    {
        $config = $this->_config = new Fizzy_Storage_Config();

        if ($config->getDriver() === Fizzy_Storage_Config::SQLite)
        {
            $this->_driver = new Fizzy_Storage_SQLite($config);
        }
        elseif ($config->getDriver() === Fizzy_Storage_Config::XML)
        {
            $this->_driver = new Fizzy_Storage_XML($config);
        } 
        else
        {
            throw new Fizzy_Storage_Exception_InvalidConfig("Invalid storage driver.");
        }
    }

    /**
     * Persist the given model (add or save).
     * Returns the persisted model (with added id).
     * 
     * @param Fizzy_Model $model
     * @return Fizzy_Model
     */
    public function persist(Fizzy_Model $model)
    {
        return $this->_driver->persist($model);
    }

    /**
     * Remove the given model from persistence.
     *
     * @param Fizzy_Model $model
     */
    public function remove(Fizzy_Model $model)
    {
        return $this->_driver->remove($model);
    }

    /**
     * Fetch one item of $type with $uid.
     * 
     * @param string $type
     * @param mixed $uid
     */
    public function fetchOne($type, $uid)
    {
        $array = $this->_driver->fetchOne($type, $uid);

        $class = $this->_buildClassname($type);
        $model = new $class();
        $model->populate($array);
        return $model;
    }

    /**
     * Fetch all entities from a specific type (e.g. pages, users).
     * 
     * @param string $type
     */
    public function fetchAll($type)
    {
        $results = $this->_driver->fetchAll($type);

        $models = array();

        foreach ($results as $array)
        {
            $class = $this->_buildClassname($type);
            $model = new $class();
            $model->populate($array);
            $models[] = $model;
        }

        return $models;
    }

    protected function _buildClassname($type)
    {
        return ucfirst($type) . 'Model';
    }
}
