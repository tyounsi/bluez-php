<?php
/**
 * class BlueZ_Datagrid_Column_Callback
 */

/**
 * A column of callback.
 * 
 * Class for extension
 */
require_once 'BlueZ/Datagrid/Column.php';

/**
 * Class BlueZ_Datagrid_Column_Counter
 * 
 */
class BlueZ_Datagrid_Column_Callback extends BlueZ_Datagrid_Column
{ 
     /**
     * Callback
     * 
     * @var array
     */
    protected $_callback = null;
       
    /**
     * Class constructor.
     * 
     * @param string $alias
     * @param string $title
     * @param array  $callback
     */
    public function __construct ($alias, $title = null, $sourceKey = null, 
            $defaultValue = null, $sortable = true, $callback = array(), $options = array())
    {
        parent::__construct($alias, $title, $sourceKey, $defaultValue, $sortable, $options);
        
        $this->_callback = $callback;
    }
      
    /**
     * Gets a callback for current column.
     * 
     * @return array
     */
    public function getCallback()
    {
        return $this->_callback;
    }
    
    /**
     * Sets a callback.
     * 
     * @return BlueZ_Datagrid_Column
     */
    public function setCallback($callback)
    {
        $this->_callback = $callback;        
        return $this;
    }

    /**
     * Fetch a data using callback.
     *
     * @param array   $row
     * @param integer $rowNumber
     * @return array
     */
    public function fetch($row, $rowNumber)
    {
        if (empty($this->_callback)) {
            return parent::fetch($row, $rowNumber);
        }
        
        return call_user_func($this->_callback, $row, $rowNumber, $this->_sourceKey);
    }
}