<?php

/**
 * @see BlueZ_Datagrid_Column
 */
require_once 'BlueZ/Datagrid/Column.php';

/**
 * A column of links.
 * 
 * @author Eugene Zabolotniy
 */
class BlueZ_Datagrid_Column_Link extends BlueZ_Datagrid_Column
{    
    /**
     * @var string
     */
    private $_linkTemplate = '';
    
    /**
     * Constructor.
     * 
     * You can use a name of data source column names.
     * You can see an example:
     * <code>
     * $column = new BlueZ_Datagrid_Column_Link('login', 'User Name', 'index.php?view={user_id}');
     * </code>
     * A part of string '{user_id}' will be replaced 
     * to a value of 'user_id' source data column.
     * 
     * @param   string  $alias          column alias
     * @param   string  $title          a title to display
     * @param   string  $linkTemplate   a template of title.
     * @param   array   $linkAttributes an attributes of link
     * @param   array   $attributes     an attributes of cell
     */
    public function __construct($alias, $title = null, $linkTemplate, $options = array())
    {        
        parent::__construct($alias, $title, null, null, $options);

        $this->_linkTemplate = $linkTemplate;       
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
        $text = parent::fetch($row, $rowNumber);
        
        $href = $this->_linkTemplate;
        
        if (isset($this->_linkTemplate)) {
            foreach ($row as $colId => $cell) {
            	$href = str_replace('{' . $colId . '}', $cell, $href);
            }
        }
        
        $xhtml = '<a href="'. $href . '" title="' . $text . '">' . $text . '</a>';  
        
        return $xhtml;
    }
}
