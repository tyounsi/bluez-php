<?php
require_once 'BlueZ/Datagrid/Source/DbSelect/ColumnLikeFilter.php';

/**
 * ColumnContainsFilter
 *
 * @author baziak
 */
class BlueZ_Datagrid_Source_DbSelect_ColumnContainsFilter extends BlueZ_Datagrid_Source_DbSelect_ColumnLikeFilter
{
    protected function getValue()
    {
        $value = parent::getValue();
        return isset($value) ? '%' . $value . '%' : null;
    }
}

