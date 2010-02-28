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
class BlueZ_Datagrid_Source_DbSelect_ColumnLikeFilter extends BlueZ_Datagrid_Source_DbSelect_AbstractColumnFilter
{
    /**
     * Applies filter to source.
     *
     * @param BlueZ_Datagrid_Source_DbSelect $source
     */
    public function apply($source)
    {
        $value = $this->getValue();

        if (!isset($value)) {
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
          . ' like ' . $adapter->quote($value);
        $source->getSelect()->where($cond);

        return true;
    }
}
