<?php
/**
 * BlueZ_Datagrid_Source_Array
 * 
 * Class that helped to add the columns from array.
 * 
 * @category   Zend
 * @package    Datagrid
 * @subpackage Source
 * @author     Eugene Zabolotniy
 * @author     Pavel Machekhin
 */
class BlueZ_Datagrid_Source_Array extends BlueZ_Datagrid_Source_Abstract
{
    /**
     * Stack of columns data.
     *
     * @var array
     */
    private $_data;

    /**
     * Stack of sorting data.
     *
     * @var array
     */
    private $_sortBy;

    /**
	 * Binding a columns data to source.
	 * 
	 * @param   mixed     $source
     * @return  Datagrid_Source_Array
	 */
    public function bind($source)
    {
        if(!$this->check($source)) {
            throw new Exception('BlueZ_Datagrid: invalid source type');
        }
        $size = sizeof($source);
        if($size > 0) {
            $this->_data = array_values($source);
        } else {
            $this->_data = $source;
        }
        return $this;
    }
    /**
	 * Checking the column data.
	 * 
	 * @param   mixed     $source
     * @return  bool
	 */
    public function check($source)
    {
        return is_array($source);
    }

    /**
	 * Return a count of rows.
	 * 
	 * @return integer     the number or records
	 */
    public function count()
    {
        return sizeof($this->_data);
    }
    
    /**
     * Gets a data as array. Data starts with $offset and ends after $len lines.
     *
     * @param   integer $offset     Limit offset (starting from 0)
     * @param   integer $len        Limit length
     * @return  array       The 2D Array of the records
     */
    public function fetch($offset = 0, $len = null)
    {
        if($this->_data) {
            $firstElement = array_slice($this->_data, 0, 1);
            $this->setOptions(array('fields' => array_keys($firstElement)));
        }
        //slicing
        if(is_null($len)) {
            $slice = array_slice($this->_data, $offset);
        } else {
            $slice = array_slice($this->_data, $offset, $len);
        }

        return $slice;
    }

    /**
     * Sorts a data.
     * 
     * @param   string|array  $sortBy     Field to sort by
     * @return  BlueZ_Datagrid_Source_Array
     */
    public function sort($sortBy)
    {   
        // TODO test it
        if (is_null($sortBy) || !is_array($sortBy) && !is_string($sortBy)) {
            return $this;           
        }
        
        if (is_string($sortBy)) {
            $sortBy = array($sortBy);
        }
        
        $this->_sortBy = array();
        
        foreach($sortBy as $sort) {
            if (is_string($sort)) {
                $this->_sortBy[] = array($sort, 'ASC');
            } elseif (count($sort) == 2) {
                $this->_sortBy[] = array($sort[0], strtoupper($sort[1]));
            } elseif (count($sort) == 1) {
                $this->_sortBy[] = array($sort[0], 'ASC');
            }  
        }
        
        usort($this->_data, array($this, '_sortcmp'));
        
        return $this;
    }
    
    /**
     * Filter a data.
     * 
     * @param $filters  a list of filters
     * return BlueZ_Datagrid_Source_Array 
     */
    public function filter($filters)
    {
        $result = array();
        foreach ($this->_data as $row) {
            if ($this->_passRow($filters, $row)) {
               $result[] = $row;
            }
        }
        $this->_data = $result;
        
        return $this;
    }
    
    /**
     * A compare function for multisort.
     * 
     * @param  mixed   $a      first row
     * @param  mixed   $b      second row
     * @param  int     $depth  a number of filter (don't need to be set, uses in recursive call)
     */
    private function _sortcmp($a, $b, $depth = 0) {
        $result = strnatcmp($a[$this->_sortBy[$depth][0]], $b[$this->_sortBy[$depth][0]]);
        if ($this->_sortBy[$depth][1] == "DESC") {
            $result = -$result;
        }
        if($result == 0) {
            $depth++;
            if (isset($this->_sortBy[$depth])) $result = $this->_sortcmp($a, $b, $depth);
        }
        return $result;
    }
    
    /**
     * Pass $row over $filters.
     * 
     * @param   array   $filters    filters list
     * @param   array   $row        row to check
     * @return  bool    true if row pass the filters or false if not
     */
    private function _passRow($filters, $row)
    {
        foreach ($filters as $filter) {
            if (count($filter) == 4) {
               
                 switch ($filter[2]) {
                     case '=':   
                         if ($row[$filter[1]] != $filter[3]) {
                             return false;
                         }
                         break;
                     case '!=':
                     case '<>':
                         if ($row[$filter[1]] == $filter[3]) {
                             return false;
                         }
                         break;
                     case '<':
                         if ($row[$filter[1]] >= $filter[3]) {
                             return false;
                         }
                         break; 
                     case '>':
                         if ($row[$filter[1]] <= $filter[3]) {
                             return false;
                         }
                         break;
                     case '<=':
                         if ($row[$filter[1]] > $filter[3]) {
                             return false;
                         }
                         break;

                     case '>=':
                         if ($row[$filter[1]] < $filter[3]) {
                             return false;
                         }
                         break;
                 }
            }
        }
        return true;
    }
}
