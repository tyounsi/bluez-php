<?php
require_once 'BlueZ/Datagrid/Source/DbSelect/AbstractColumnFilter.php';

/**
 * Abstract class of filter.
 *
 * @category   Zend
 * @package    BlueZ_Datagrid
 * @subpackage Filter
 * @author     Eugene Zabolotniy
 */
class BlueZ_Datagrid_Source_DbSelect_ColumnCaseFilter extends BlueZ_Datagrid_Source_DbSelect_AbstractColumnFilter
{
    protected $_customValues = array();
    protected $_caseValues = array();

    /**
     * Filter constructor.
     *
     * $filter applies only if $caseValue occurs in the request. It can be an
     * array of values, in this case $customValue should be an associative
     * array with keys from $caseValue and custom values.
     *
     * If the $customValue is set it will be used for filtering, or $caseValue
     * in other case.
     *
     * @param string $alias
     * @param BlueZ_Datagrid_Column $column
     * @param string|array $caseValues
     * @param string|array $customValues
     */
    public function  __construct($alias, $column, $caseValues, $customValues = null) {
        parent::__construct($alias, $column);
        $this->_customValues = $customValues;
        $this->_caseValues = $caseValues;
    }

    public function getCustomValues() {
        return $this->_customValues;
    }

    public function setCustomValues($customValues) {
        if (!is_array($customValues)) {
            $customValues = array($customValues);
        }
        $this->_customValues = $customValues;
    }

    public function getCaseValues() {
        return $this->_caseValues;
    }

    public function setCaseValues($caseValues) {
        if (!is_array($caseValues)) {
            $caseValues = array($caseValues);
        }
        $this->_caseValues = $caseValues;
    }

    /**
     * Applies filter to source.
     *
     * @param BlueZ_Datagrid_Source_DbSelect $source
     */
    public function apply($source)
    {
        $case = $this->_datagrid->getConfig()->getFilter($this->_alias);

        if (in_array($case, $this->_caseValues)) {
            if (isset($this->_customValues[$case]) ) {
                $case = $this->_customValues[$case];
            }
        } else {
            return false;
        }

        if (!($source instanceof BlueZ_Datagrid_Source_DbSelect)) {
            require_once 'BlueZ/Datagrid/Filter/Exception.php';
            throw new BlueZ_Datagrid_Filter_Exception('A source for this filter should be an instance of BlueZ_Datagrid_Source_DbSelect');
        }

        $adapter = $source->getAdapter();
        
        if (!($adapter instanceof Zend_Db_Adapter_Abstract)) {
            require_once 'BlueZ/Datagrid/Filter/Exception.php';
            throw new BlueZ_Datagrid_Filter_Exception('Please specify a DB adapter for your data source.');
        }
        
        if (!$this->_column) {
            require_once 'BlueZ/Datagrid/Filter/Exception.php';
            throw new BlueZ_Datagrid_Filter_Exception('A column is not set.');
        }

        $cond = $adapter->quoteIdentifier($this->_column->getSourceKey())
            . '=' . $adapter->quote($case);
        $source->getSelect()->where($cond);

        return true;
    }
}
