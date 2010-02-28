<?php
require_once 'BlueZ/Datagrid/Filter/Interface.php';

/**
 * Abstract class of filter.
 *
 * @category   Zend
 * @package    BlueZ_Datagrid
 * @subpackage Filter
 * @author     Eugene Zabolotniy
 */
abstract class BlueZ_Datagrid_Filter_Abstract implements BlueZ_Datagrid_Filter_Interface
{
    protected $_alias = null;
    protected $_datagrid = null;
    protected $_options = array();

    /**
     * Filter constructor.
     *
     * @param string $alias
     */
    public function __construct($alias, $options = null)
    {
        $this->_alias = $alias;
        if (!empty($options)) {
            $this->_options = $options;
        }
    }

    /**
     * Sets datagrid.
     *
     * @param BlueZ_Datagrid_Abstract $datagrid
     */
    public function setDatagrid($datagrid)
    {
        $this->_datagrid = $datagrid;
    }

    /**
     * Gets datagrid.
     *
     * @return BlueZ_Datagrid_Abstract
     */
    public function getDatagrid()
    {
        return $this->_datagrid;
    }

    /**
     * Gets filter alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }

    public function getValue()
    {
        return $this->_datagrid->getConfig()->getFilter($this->_alias);
    }

    /**
     * Gets an option for a filter.
     *
     * @param  $name
     * @return array
     */
    public function getOption($name)
    {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        }
        return null;
    }

    /**
     * Gets an array of options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Set an option.
     *
     * @param string $name
     */
    public function setOption($name, $value)
    {
        $this->_options[$name] = $value;
        return $this;
    }

    /**
     * Set an array of options.
     *
     * @return array
     */
    public function setOptions($attributes, $merge = false)
    {
        if ($merge) {
            $this->_options = array_merge($this->_options, $attributes);
        } else {
            $this->_options = $attributes;
        }
        return $this;
    }
}
