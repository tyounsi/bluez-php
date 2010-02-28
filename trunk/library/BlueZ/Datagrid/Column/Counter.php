<?php
/**
 * class BlueZ_Datagrid_Column_Counter
 */

/**
 * A column of numbers of the rows.
 * 
 * Class for extension
 */
require_once 'BlueZ/Datagrid/Column.php';

/**
 * Class BlueZ_Datagrid_Column_Counter
 * 
 */
class BlueZ_Datagrid_Column_Counter extends BlueZ_Datagrid_Column
{
    const POSTFIX = 'postfix';
    
    /**
     * Class constructor.
     * 
     * @param string    $alias         a unique id of column
     * @param string    $sourceKey      key of column in source row
     * @param string    $title          a title of column
     * @param mixed     $defaultValue   default value of cells
     * @param array     $options        an array of options
     */
    public function __construct ($alias, $title = null, $options = array())
    {
        if (!is_array($options)) {
            $options = array(self::POSTFIX => (string) $options);
        }
        
        parent::__construct($alias, $title, false, null, $options);
    }
    
    /**
     * Fetches rows data and generates a 'counter' column.
     *
     * @param array   $row
     * @param integer $rowNum
     * @return string
     */
    public function fetch($row, $rowNum)
    {   
        if (isset($this->_options[self::POSTFIX])) {
            $postfix = $this->_options[self::POSTFIX];
        } else {
            $postfix = '';
        }        
                
        return '' . ($rowNum + 1) . $postfix;
    }
}