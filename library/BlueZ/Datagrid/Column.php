<?php

/**
 * A base class for datagrid columns.
 * 
 * @category    Zend
 * @package     BlueZ_Datagrid
 * @author      Eugene Zabolotniy
 * @author      Pavel Machekhin
 */
class BlueZ_Datagrid_Column 
{    
    /**
     * BlueZ_Datagrid object
     * 
     * @var BlueZ_Datagrid
     */
    protected $_datagrid = null;
    
    /**
     * Unique name of column.
     *
     * @var string
     */
    protected $_alias = null;
    
    /**
     * Title of column.
     *
     * @var string
     */
    protected $_title = null;
    
    /**
     * Key name in source row.
     *
     * @var string
     */
    protected $_sourceKey = null;
    
    /**
     * Default value of column.
     *
     * @var mixed
     */
    protected $_defaultValue = null;

    /**
     * Sortable flag.
     *
     * @var boolean
     */
    protected $_sortable = true;
    
    /**
     * Stack of options.
     *
     * @var array
     */
    protected $_options = array();
    
    /**
     * A static function for getting a string like 
     * ' key1 = "val1" key2 = "val2" ... keyN = "valN"',
     * 
     * @param array $attrs
     */
    public static function renderAttributes($attrs)
    {        
        $html = '';
        if (!empty($attrs)) {
            foreach ($attrs as $key => $value) {
                $html .= ' ' . $key . ' = "' . $value . '"'; 
            }
        }
        
        return $html;
    }
    
    /**
     * Class constructor.
     * 
     * @param string    $alias         a unique id of column
     * @param string    $title          a title of column
     * @param string    $sourceKey      key of column in source row,
     * if $sourceKey is null an alias will be used instead.
     * @param mixed     $defaultValue   default value of cells
     * @param boolean   $sortable       is it sortable?
     * @param array     $options        an array of options
     */
    function __construct($alias, $title = null, $sourceKey = null,
        $defaultValue = null, $sortable = true, $options = array())
    {
        $this->_alias = $alias;
        $this->_title = $title;
        
        if ($sourceKey === null) {
            $this->_sourceKey = $alias;
        } else {
            $this->_sourceKey = $sourceKey;
        }
        
        $this->_defaultValue = $defaultValue;
        $this->setSortable($sortable);
        $this->_options = $options;     
    }
    
    /**     
     * Gets a parent datagrid. 
     * 
     * @return BlueZ_Datagrid
     */
    public function getDatagrid()
    {        
        return $this->_datagrid;
    }    

    /**
     * Set a parent datagrid. 
     * 
     * @param BlueZ_Datagrid $datagrid
     */
    public function setDatagrid($datagrid)
    {
        $this->_datagrid = $datagrid;
    }
    
    /**
     * Gets a column alias.
     * 
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }
    
    /**
     * Set an alias for column.
     * 
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->_alias = $alias;
        return $this;
    }
    
    /**
     * Gets a title of column.
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }
    
    /**
     * Sets a column title.
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;        
        return $this;
    }
    
    /**
     * Gets a column source key.
     * 
     * @return string
     */
    public function getSourceKey()
    {
        return $this->_sourceKey;
    }

    /**
     * Set a source key name.
     *
     * @param string $key
     */
    public function setSourceKey($key)
    {
        $this->_sourceKey = $key;
        return $this;
    }
    
    /**
     * Gets a default value for field.
     * 
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->_defaultValue;
    }
    
    /**
     * Sets a default value for fields of a current column.
     *
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue = null)
    {
        $this->_defaultValue = $defaultValue;
        return $this;
    }

    /**
     * Returns true if the column is sortable.
     *
     * @return boolean
     */
    public function isSortable() {
        return $this->_sortable && $this->getSourceKey();
    }

    /**
     *
     * @param boolean $sortable
     */
    public function setSortable($sortable = true) {
        $this->_sortable = (boolean) $sortable;
    }
    
    /**
     * Gets an option for current column.
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
     * Set an array of options .
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

    /**
     * Fetch a data.
     *
     * @param array   $row
     * @param integer $rowNumber
     * @return array
     */
    public function fetch($row, $rowNumber)
    {   
        if (!isset($row[$this->_sourceKey]) || $row[$this->_sourceKey] === null) {
            return $this->_defaultValue;
        } else {
            return $row[$this->_sourceKey];
        }
    }

}
