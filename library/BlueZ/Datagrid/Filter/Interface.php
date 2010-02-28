<?php

/**
 * Filter interface.
 *
 * @category   Zend
 * @package    BlueZ_Datagrid
 * @subpackage Filter
 * @author     Eugene Zabolotniy
 */
interface BlueZ_Datagrid_Filter_Interface
{
    /**
     * Applies filter to source.
     *
     * @param BlueZ_Datagrid_Source_Abstract $source
     */
    function apply($source);

    /**
     * Sets datagrid.
     *
     * @param BlueZ_Datagrid_Abstract $datagrid
     */
    function setDatagrid($datagrid);

    /**
     * Gets datagrid.
     *
     * @return BlueZ_Datagrid_Abstract
     */
    function getDatagrid();

     /**
     * Gets filter alias.
     *
     * @return string
     */
    function getAlias();
}
