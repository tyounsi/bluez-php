<?php

require_once 'Zend/View.php';
require_once 'BlueZ/Datagrid/Renderer/Interface.php';
require_once 'BlueZ/Datagrid/Renderer/Exception.php';
require_once 'BlueZ/Datagrid/Abstract.php';

/**
 * Renders a datagrid.
 *
 * @property BlueZ_Datagrid_Config $config
 *
 * @author Eugene Zabolotniy
 */
class BlueZ_Datagrid_Renderer extends Zend_View implements BlueZ_Datagrid_Renderer_Interface
{

    /**
     * @var BlueZ_Datagrid_Abstract
     */
    protected $_datagrid = null;

    /**
     * @var string
     */
    protected $_defaultScript = null;

    /**
     * @var array
     */
    protected $_sourceData = null;
    
    /**
     * @var ArrayIterator
     */
    protected $_sourceIterator = null;

    /**
     * @var integer
     */
    protected $_sourceRowIndex = 0;
    
    /**
     * @param $datagrid BlueZ_Datagrid_Abstract a datagrid object or null.
     */
    public function __construct($datagrid = null)
    {
        if ($datagrid instanceof BlueZ_Datagrid_Abstract) {
            $this->setDatagrid($datagrid);
        } elseif ($datagrid !== null) {
            throw new BlueZ_Datagrid_Renderer_Exception('Parameter $datagrid is wrong. BlueZ_Datagrid_Abstract instance or null expected.');
        }
    }

    /**
     * Gets a parent datagrid.
     * 
     * $return BlueZ_Datagrid_Abstract
     */
    public function getDatagrid()
    {
        return $this->_datagrid;
    }

    /**
     * Set a parent datagrid
     * 
     * @param BlueZ_Datagrid_Abstract $datagrid
     */
    public function setDatagrid($datagrid)
    {
        $this->_datagrid = $datagrid;
    }

    /**
     * Gets a name of default script.
     * 
     * @return string
     */
    public function getDefaultScript()
    {
        return $this->_defaultScript;
    }

    /**
     * Set a default script name.
     * 
     * @param string $scriptName
     */
    public function setDefaultScript($scriptName)
    {
        $this->_defaultScript = $scriptName;
    }

    /**
     * Gets a source data.
     * 
     * @return array
     */
    public function getSourceData()
    {
        return $this->_sourceData;
    }

    /**
     * Set a source data.
     * 
     * @param $sourceData
     */
    public function setSourceData($sourceData)
    {
        $this->_sourceData = $sourceData;
    }

    /**
     * Gets HTML code
     */
    public function render($scriptName)
    {
        if (empty($scriptName) && ! empty($this->_defaultScript)) {
            $scriptName = $this->_defaultScript;
        } elseif (empty($scriptName)) {
            throw new BlueZ_Datagrid_Renderer_Exception(
                'Script is not specified.');
        }  

        if ($this->_datagrid == null) {
            throw new BlueZ_Datagrid_Renderer_Exception(
                'Datagrid is not specified.');
        }

        $this->pager    = $this->_datagrid->getPager();
        $this->columns  = $this->_datagrid->getColumns();
        $this->config   = $this->_datagrid->getConfig();
        $this->titles   = $this->getTitles();
        
        return parent::render($scriptName);
    }
        
    /**
     * Takes a titles (or column names where titles not specified) 
     * from columns.
     * 
     * @return array    of title strings
     */
    public function getTitles()
    {
        $titles = array();
        
        foreach ($this->_datagrid->getColumns() as $column) {
            /* @var $column BlueZ_Datagrid_Column */
           
            if ($column->getTitle() != null) {
                $titles[] = $column->getTitle();
            } else {
                $titles[] = $column->getAlias();
            }
        }
        return $titles;
    }

    /**
     * Fetch and render a row.
     *
     * @param integer $index
     * @return array
     */
    public function renderRow($sourceRow, $rowNumber)
    {
        $row = array();
        foreach ($this->_datagrid->getColumns() as $column) {
            /* @var $column BlueZ_Datagrid_Column */
            
            $row[$column->getAlias()] = $column->fetch($sourceRow, $rowNumber);
        }
        
        return $row;
    }
    
    public function renderNextRow()
    {
        if ($this->_sourceIterator == null) {

            $arrayObj = new ArrayObject((array)$this->_sourceData);
            
            $this->_sourceIterator = $arrayObj->getIterator();
            $this->_sourceRowIndex = $this->_datagrid->getConfig()
                                          ->getFirstRowIndex();
        }
        
        if (!$this->_sourceIterator->valid() || $this->_sourceIterator->count() == 0) {
            return null;
        }
        
        $sourceRow = $this->_sourceIterator->current();
        $this->_sourceIterator->next();
        
        return $this->renderRow($sourceRow, $this->_sourceRowIndex++);
    }
    
    public function renderAllRows()
    {
        $rows = array();
        
        while($rows[] = $this->renderNextRow()) {}
        return $rows;
    }
}