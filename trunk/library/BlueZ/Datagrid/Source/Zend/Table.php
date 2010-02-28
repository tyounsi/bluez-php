<?php

/**
 * @see BlueZ_Datagrid_Source_Zend_Select
 */
require_once 'BlueZ/Datagrid/Source/Zend/Select.php';

/**
 * Wrap a Zend_Db_Table and use it to pick data.
 * 
 * @category    Zend
 * @package     BlueZ_Datagrid
 * @subpackage  Source_Zend
 * @author      Eugene Zabolotniy
 */
class BlueZ_Datagrid_Source_Zend_Table extends BlueZ_Datagrid_Source_Zend_Select {
    
    /**
     * @var Zend_Db_Table
     */
    protected $_table = null;
    
    /**
     * Bind to source.
     *  
     * @param   mixed     $source
     * @return  BlueZ_Datagrid_Source_Zend_Table
     */
    public function bind($source)
    {
        if (!$this->check($source)) {
            throw new BlueZ_Datagrid_Source_Exception('Datagrid: wrong source type, Zend_Db_Select expected.');
        }
        
        /* @var $source Zend_Db_Table_Abstract */

        $info = $source->info();
        
        $this->_table = $source;
        $this->_select = $source->getAdapter()->select();
        
        $this->_select->from($info[Zend_Db_Table_Abstract::NAME],
                            $info[Zend_Db_Table_Abstract::COLS],
                            $info[Zend_Db_Table_Abstract::SCHEMA]);
        
        return $this;
    }
    
    /**
     * Validate a source.
     *
     * @param   mixed     $source
     * @return  bool
     */
    public function check($source)
    {
        return $source instanceof Zend_Db_Table_Abstract;
    }
    
    /**
     * Return a count of rows.
     *
     * @return  int         The number or records
     */
    public function count()
    {
        return parent::count();
    }
    
    /**
     * Gets a data as array. Data starts with $offset and ends after $len lines.
     *
     * @param   integer $offset     Limit offset (starting from 0)
     * @param   integer $len        Limit length
     * @return  array               The 2D Array of the records
     */
    public function fetch($offset = 0, $len = null)
    {
        return parent::fetch($offset, $len);
    }
    
    /**
     * Sorts a data.
     * 
     * @param   string  $sortBy     Field to sort by
     * @param   string  $sortDir    Sort direction: 'ASC' or 'DESC' 
     *                              (default: ASC)
     * @return  BlueZ_Datagrid_Source_Zend_Table
     */
    public function sort($sortBy)
    {
        parent::sort($sortBy);
    }
    
    /**
     * Filter a data.
     * 
     * @param $filters  a list of filters
     * return BlueZ_Datagrid_Source_Zend_Table 
     */
    public function filter($filters)
    {
        foreach ($filters as $filter) {
            $this->_select->where($filter[1] . $filter[2] 
               . $this->_table->getAdapter()->quote($filter[3]));
        }
        
        return $this;
    }
}
