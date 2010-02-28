<?php
require_once 'BlueZ/Datagrid/Filter/Abstract.php';

/**
 * Abstract class of filter.
 *
 * @category   Zend
 * @package    BlueZ_Datagrid
 * @subpackage Filter
 * @author     Eugene Zabolotniy
 */
abstract class BlueZ_Datagrid_Source_DbSelect_AbstractColumnFilter extends BlueZ_Datagrid_Filter_Abstract
{
    /**
     * @var BlueZ_Datagrid_Column
     */
    protected $_column = null;

    /**
     * Constructor.
     *
     * @param string $alias
     * @param BlueZ_Datagrid_Column $column
     */
    public function __construct($alias, $column) {
        parent::__construct($alias);
        $this->setColumn($column);
    }

    /**
     * Sets a column.
     *
     * @param BlueZ_Datagrid_Column $column
     */
    public function setColumn($column)
    {
        if (!($column instanceof BlueZ_Datagrid_Column)) {
            require_once 'BlueZ/Datagrid/Filter/Exception.php';
            throw new BlueZ_Datagrid_Filter_Exception('A column for this filter should be an instance of BlueZ_Datagrid_Column');
        }

        $this->_column = $column;

        return $this;
    }

    public function getColumn() {
        return $this->_column;
    }

    protected function _getValue()
    {
        return $this->_datagrid->getConfig()->getFilter($this->_alias);
    }
}
