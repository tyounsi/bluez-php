<?php

/**
 * @see BlueZ_Datagrid_Source_Abstract
 */
require_once 'BlueZ/Datagrid/Source/Abstract.php';

/**
 * @see BlueZ_Datagrid_Source_Exception
 */
require_once 'BlueZ/Datagrid/Source/Exception.php';

/**
 * @see Zend_Db_Select
 */
require_once 'Zend/Db/Select.php';

/**
 * @see Zend_Db_Adapter_Abstract
 */
require_once 'Zend/Db/Adapter/Abstract.php';

/**
 * Wrap a Zend_Db_Select and use it to pick data.
 * 
 * @category    Zend
 * @package     BlueZ_Datagrid
 * @subpackage  Source
 * @author      Eugene Zabolotniy
 */
class BlueZ_Datagrid_Source_DbSelect extends BlueZ_Datagrid_Source_Abstract {
    /**
     * @var Zend_Db_Select
     */
    protected $_select;
    
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_adapter = null;
    
    /**
     * Constructor.
     *
     * @param Zend_Db_Adapter_Abstract $adapter
     */
    public function __construct($adapter = null)
    {
        if ($adapter instanceof Zend_Db_Adapter_Abstract) {
            $this->_adapter = $adapter;
        }
    }

    /**
     * Returns a select object.
     *
     * @return Zend_Db_Select
     */
    public function getSelect()
    {
        return $this->_select;
    }

    /**
     * Returns a select object.
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }
    
    /**
     * Bind to source.
     *
     * @param   mixed     $source
     * @return  Datagrid_Source_DbSelect
     */
    public function bind($source)
    {
        if (!$this->check($source)) {
            throw new BlueZ_Datagrid_Source_Exception('Datagrid: wrong source type, Zend_Db_Select expected.');
        }
        
        /* @var $source Zend_Db_Select */
        
        $this->_select = $source;
        
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
        return $source instanceof Zend_Db_Select;
    }
    
    /**
     * Gets a data as array. 
     * 
     * Data starts with $offset and ends after $len lines.
     *
     * @param   integer $offset     Limit offset (starting from 0)
     * @param   integer $len        Limit length
     * @return  array       The 2D Array of the records
     */
    public function fetch($offset = 0, $len = null)
    {
        $originOffset = $this->_select->getPart(Zend_Db_Select::LIMIT_OFFSET);
        $originCount = $this->_select->getPart(Zend_Db_Select::LIMIT_COUNT);

        $newOffset = $offset + (int) $originOffset;
        
        $rest = null;
        
        // if we haw an original count calc $rest 
        if ($originCount !== null) {
            $rest = $originCount - $newOffset;
        }
        
        $newCount = 0;
        
        if ($rest == null) {
            $newCount = $len;
        } elseif ($rest > 0) {
            $newCount = min($rest, $len);            
        } else {
            return array();
        }

        $select = clone ($this->_select);
        
        $select->limit($newCount, $newOffset);
//        echo "<pre>";
//        print_r($select->__toString());
//        echo "</pre>";
//        exit;
        $stmt = $select->query();
        
        $records = $stmt->fetchAll();
        
        return $records;
    }
    
    /**
     * Return a count of rows.
     *
     * @return  int         The number or records
     */
    public function count()
    {
        $select = clone ($this->_select);
        
//        $select->reset(Zend_Db_Select::GROUP);
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);

        $select->from(null, 'COUNT(*)');

        $stmt = $select->query();
        /* @var $stmt Zend_Db_Statement */

        $result = $stmt->fetchAll(Zend_Db::FETCH_NUM);
 
        if (count($select->getPart(Zend_Db_Select::GROUP))) {
            return count($result);
        }
        
        return $result[0][0];
    }
    
    /**
     * Sorts a data.
     * 
     * @param   string|array  $sortBy     Field to sort by
     * @return  Datagrid_Source_Array
     */
    public function sort($sortBy)
    {
        $this->_select->reset(Zend_Db_Select::ORDER);

        if (is_string($sortBy)) {
            $sortBy = array($sortBy);
        }
        
        if (is_null($sortBy) || !is_array($sortBy)) {
            return $this;           
        }        
        
        $sorts = array();
        
        foreach($sortBy as $sort) {
            if (is_string($sort)) {
                $sorts[] = $sort;
            } elseif (count($sort) == 2) {
                $sorts[] = $sort[0] . ' ' . strtoupper($sort[1]);
            } elseif (count($sort) == 1) {
                $sorts[] = $sort[0];
            }  
        }

        $this->_select->order($sorts);
        
        return $this;
    }
    
    /**
     * Filter a data.
     * 
     * @param $filters  a list of filters
     * return BlueZ_Datagrid_Source_DbSelect
     */
    public function filter($filters)
    {
        $operators = array('<=', '!=', '<>', '>=', '=', '>', '<');
        
        foreach ($filters as $filter) {
            
            if (in_array($filter[2], $operators)) {
                $this->_select->where(
                    $this->_adapter->quoteIdentifier($filter[1]) . $filter[2] 
                    . $this->_adapter->quote($filter[3]));
            }
        }
        
        return $this;
    }
}
