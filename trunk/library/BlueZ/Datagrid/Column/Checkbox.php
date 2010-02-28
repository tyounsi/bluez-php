<?php
/**
 * Class BlueZ_Datagrid_Column_Checkbox
 * 
 * Generates a 'checkbox' element.
 * 
 * @category   Zend
 * @package    Datagrid
 * @subpackage Column
 * @author     Pavel Machekhin
 */

/**
 * Class for extension.
 */
require_once 'BlueZ/Datagrid/Column.php';

/**
 * Class BlueZ_Datagrid_Column_Checkbox
 */
class BlueZ_Datagrid_Column_Checkbox extends BlueZ_Datagrid_Column
{
    const CHECKBOX_ATTRIBUTES   = 'checkboxAttributes';
    
    const USE_DATA_AS_ID        = 'useDataAsId';
    const USE_DATA_AS_VALUE     = 'useDataAsValue';
    const USE_DATA_AS_STATE     = 'useDataAsState';
    
    /**
     * Class constructor.
     * 
     * @param string    $alias         a unique id of column
     * @param string    $sourceKey      key of column in source row
     * @param string    $title          a title of column
     * @param mixed     $defaultValue   default value of cells
     * @param array     $options        an array of options
     */
    public function __construct ($alias, $title = null, $sourceKey = null,
        $defaultValue = 1, $options = array())
    {
        parent::__construct($alias, $title, $sourceKey, $defaultValue, $options);
    }
    
    /**
     * Fetches the row data and generates a checkbox element 
     * with the use of Zend_View_Helper.     
     *
     * @param array $row
     * @param integer $rowNum
     * @return string
     */
    public function fetch($row, $rowNumber)
    { 
        $options =& $this->_options;

        if (isset($row[$this->_sourceKey])) {
            $data = $row[$this->_sourceKey];     
        } else {
            $data = null;
        }
        
        if (isset($options[self::CHECKBOX_ATTRIBUTES])) {
            $attrs = $options[self::CHECKBOX_ATTRIBUTES];
        } else {
            $attrs = array();
        }
        
        if (isset($options[self::USE_DATA_AS_ID]) && $options[self::USE_DATA_AS_ID]) {
            // TODO: think about: id must be unique
            $name = $this->_alias . '-' . $data;
        } else {
            $name = $this->_alias . '-' . $rowNumber;
        }

        if (isset($options[self::USE_DATA_AS_VALUE]) && $options[self::USE_DATA_AS_VALUE]) {
            $value = $data;
        } else {
            $value = $this->_defaultValue;
        }
        
        if (isset($options[self::USE_DATA_AS_STATE]) && $options[self::USE_DATA_AS_STATE]) {
            $check = $value;
        } else {
            $check = !$value;
        }

        $checkOptions = array($value);
        
        return $this->_datagrid->getRenderer()->formCheckbox(
            $name, $check, $attrs, $checkOptions);
    }
}
