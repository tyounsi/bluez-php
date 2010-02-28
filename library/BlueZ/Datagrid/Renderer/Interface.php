<?php

/**
 * @see Zend_View_Interface
 */
require_once 'Zend/View/Interface.php';

/**
 * This interface must be implemented by BlueZ_Datagrid renderer.
 * 
 * @category    Zend
 * @package     Zend_Database
 * @subpackage  Renderer
 */
interface BlueZ_Datagrid_Renderer_Interface extends Zend_View_Interface
{
    function getDefaultScript();
    function setDefaultScript($scriptName);
    function getDatagrid();
    function setDatagrid($datagrid);
    function getSourceData();
    function setSourceData($rawdata);
}
