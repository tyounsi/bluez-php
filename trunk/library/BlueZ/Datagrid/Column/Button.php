<?php
/**
 * Class BlueZ_Datagrid_Column_Button
 * 
 * Generates a 'button' element.
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
 * Class BlueZ_Datagrid_Column_Button
 */

class BlueZ_Datagrid_Column_Button extends BlueZ_Datagrid_Column
{
    const BUTTON_ATTRIBUTES = 'buttonAttributes';
    
    /**
     * Fetches the rows data and generates a 'button' element with the use of Zend_View_Helper.     
     *
     * @param array $row
     * @param integer $rowNum
     * @return string
     */
    public function fetch($row, $rowNumber)
    {        
        if (isset($this->_options[self::BUTTON_ATTRIBUTES])) {
            $attrs = $this->_options[self::BUTTON_ATTRIBUTES];
        } else {
            $attrs = null;
        }
        
        $value = parent::fetch($row, $rowNumber);
        
        $name = $this->_alias . '-' . $rowNumber;  
                 
        $xhtml = $this->_datagrid->getRenderer()->formButton(
                $name, $value, $attrs);

        return $xhtml;
    }
}