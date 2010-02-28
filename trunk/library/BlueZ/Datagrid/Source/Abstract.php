<?php
/**
 * BlueZ_Datagrid_Source_Abstract class.
 * Represents a Zend Framework based component,
 * what help to display data.
 * 
 * @category    Zend
 * @package     BlueZ_Datagrid
 * @subpackage  Source
 * @author      Eugene Zabolotniy
 * @author      Pavel Machekhin
 */
abstract class BlueZ_Datagrid_Source_Abstract
{
    const DIRECTION_DESCENDANT = 'desc';
    const DIRECTION_ASCENDANT  = 'asc';
    
    /**
     * @var BlueZ_Datagrid
     */
    protected $_datagrid = null;

    /**
     * @var array
     */
    protected $_options = array();

    /**
     * bind 
     * @return BlueZ_Datagrid_Source
     */
    abstract public function bind($source);

    /**
     * check
     * @return bool
     */
    abstract public function check($source);

    /**
     * count
     * @return integer
     */
    abstract public function count();

    /**
     * fetch
     * @return string
     */
    abstract public function fetch($offset = 0, $len = null);

    /**
     * sort
     * @return string
     */
    abstract public function sort($sortBy);

    /**
     * Filter source.
     * 
     * @param $filters 
     */
    abstract public function filter($filters);

    /**
     * get options
     * @param mixed
     */
    public function getOptions() 
    {
        return $this->_options;
    }

    /**
     * set option
     * @param string
     */
    public function getOption($name) 
    {
       return $this->_option[$name];
    }

    /**
     * set datagrid
     */
    public function getDatagrid() 
    {
        return $this->_datagrid;
    }

    /**
     * set options
     * @param mixed $options
     */    
    public function setOptions($options) 
    {
        $this->_options = array_merge($this->_options, $options);
        return $this;
    }

    /**
     * set option
     * @param string
     * @param mixed
     */
    public function setOption($name, $value) 
    {
        $this->_options[$name] = $value;
        return $this;
    }

    /**
     * set datagrid
     */
    public function setDatagrid($datagrid) 
    {
        $this->_datagrid = $datagrid;
        return $this;
    }
}