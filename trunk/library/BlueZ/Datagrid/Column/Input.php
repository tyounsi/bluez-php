<?php

/**
 * @see BlueZ_Datagrid_Column
 */
require_once 'BlueZ/Datagrid/Column.php';

/**
 * Class BlueZ_Datagrid_Column_Input
 * 
 * Generates a 'input' element.
 * 
 * @category   Zend
 * @package    Datagrid
 * @subpackage Column
 * @author     Pavel Machekhin
 */
class BlueZ_Datagrid_Column_Input extends BlueZ_Datagrid_Column
{  
    const INPUT_ATTRIBUTES = 'inputAttributes';
    
    /**
     * Generates an 'input' element using of Zend_View_Helper.
     *
     * @param array   $row
     * @param integer $rowNum
     * @return string
     */
    public function fetch($row, $rowNumber)
    {
        if (isset($this->_options[self::INPUT_ATTRIBUTES])) {
            $attrs = $this->_options[self::INPUT_ATTRIBUTES];
        } else {
            $attrs = null;
        }
        
        $name = $this->_alias . '-' . $rowNumber;
        $xhtml = $this->_datagrid->getRenderer()->formText(
                $name, $row[$this->_sourceKey], $attrs);

        return $xhtml;
    }
}