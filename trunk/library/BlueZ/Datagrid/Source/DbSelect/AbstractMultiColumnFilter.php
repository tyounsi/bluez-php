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
abstract class BlueZ_Datagrid_Source_DbSelect_AbstractMultiColumnFilter extends BlueZ_Datagrid_Filter_Abstract
{
    /**
     * @var BlueZ_Datagrid_Column
     */
    protected $_columns = null;

    /**
     * Constructor.
     *
     * @param string $alias
     * @param BlueZ_Datagrid_Column $column
     */
    public function __construct($alias, $columns) {
        parent::__construct($alias);
        $this->setColumns($columns);
    }

    /**
     * Sets columns.
     *
     * @param array|BlueZ_Datagrid_Column $column
     */
    public function setColumns($columns)
    {
        $this->_columns = array();

        if (is_array($columns)) {
            foreach ($columns as $column) {
                $this->addColumn($column);
            }
        } elseif ($columns instanceof BlueZ_Datagrid_Column) {
            $this->addColumn($columns);
        } else {
            require_once 'BlueZ/Datagrid/Filter/Exception.php';
            throw new BlueZ_Datagrid_Filter_Exception('A column list should be an array.');
        }
    }

    /**
     * Adds a column.
     *
     * @param BlueZ_Datagrid_Column $column
     */
    public function addColumn ($column)
    {
        if (!($column instanceof BlueZ_Datagrid_Column)) {
            require_once 'BlueZ/Datagrid/Filter/Exception.php';
            throw new BlueZ_Datagrid_Filter_Exception('A column for this filter should be an instance of BlueZ_Datagrid_Column');
        }

        $this->_columns[] = $column;

        return $this;
    }

    public function getColumns() {
        return $this->_columns;
    }
}
