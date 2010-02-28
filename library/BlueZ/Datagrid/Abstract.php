<?php

/**
 * @see BlueZ_Datagrid_Config
 */
require_once 'BlueZ/Datagrid/Config.php';

/**
 * @see BlueZ_Datagrid_Source_Abstract
 */
require_once 'BlueZ/Datagrid/Source/Abstract.php';

/**
 * @see BlueZ_Datagrid_Renderer_Interface
 */
require_once 'BlueZ/Datagrid/Renderer/Interface.php';

/**
 * @see BlueZ_Datagrid_Renderer
 */
require_once 'BlueZ/Datagrid/Renderer.php';

/**
 * @see BlueZ_Datagrid_Column
 */
require_once 'BlueZ/Datagrid/Column.php';

/**
 * @see BlueZ_Datagrid_Pager
 */
require_once 'BlueZ/Datagrid/Pager.php';

/**
 * An abstract class for BlueZ_Datagrid.
 *
 * @category    Zend
 * @package     BlueZ_Datagrid
 * @author      Eugene Zabolotniy
 * @author      Pavel Machekhin
 */
abstract class BlueZ_Datagrid_Abstract
{

    /**
     * @var BlueZ_Datagrid_Config
     */
    private $_config = null;

    /**
     * @var BlueZ_Datagrid_Source_Abstract
     */
    private $_source = null;

    /**
     * @var BlueZ_Datagrid_Renderer
     */
    private $_renderer = null;

    /**
     * @var array of BlueZ_Datagrid_Column objects
     */
    private $_columns = array();

    /**
     * @var array of BlueZ_Datagrid_Filter_Interface instances
     */
    private $_filters = array();

    /**
     * @var BlueZ_Datagrid_Pager
     */
    private $_pager = null;

    /**
     * Constructor of the class.
     * 
     */
    public function __construct()
    {
        // create a default instances of config, renderer and pager

        if (!$this->getConfig()) {
            $this->setConfig(new BlueZ_Datagrid_Config());
        }

        if (!$this->getRenderer()) {
            $this->setRenderer(new BlueZ_Datagrid_Renderer());
        }

        if (!$this->getPager()) {
            $this->setPager(new BlueZ_Datagrid_Pager());
        }

        $this->init();
    }

    /**
     * Gets the config.
     * 
     * @return BlueZ_Datagrid_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Gets the renderer.
     * 
     * @return BlueZ_Datagrid_Renderer
     */
    public function getRenderer()
    {
        return $this->_renderer;
    }

    /**
     * Gets the source.
     * 
     * @return BlueZ_Datagrid_Source_Abstract
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * Gets the page navigator.
     * 
     * @return BlueZ_Datagrid_Pager_Abstract
     */
    public function getPager()
    {
        return $this->_pager;
    }
        
    /**
     * Sets the columns.
     * 
     * @param array $_columns
     */
    public function setColumns($columns)
    {
        $this->_columns = $columns;
    }

    /**
     * Sets the config.
     * 
     * @param BlueZ_Datagrid_Config $_config
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * Sets the renderer.
     * 
     * @param BlueZ_Datagrid_Renderer $_renderer
     */
    public function setRenderer($renderer)
    {
        $this->_renderer = $renderer;
        $this->_renderer->setDatagrid($this);
    }

    /**
     * Sets the source.
     * 
     * @param BlueZ_Datagrid_Source_Abstract $_source
     */
    public function setSource($source)
    {
        $this->_source = $source;
    }

    /**
     * Sets the page navigator.
     * 
     * @param BlueZ_Datagrid_Pager_Abstract $pager
     */
    public function setPager($pager)
    {
        $this->_pager = $pager;
        $this->_pager->setDatagrid($this);
    }

    /**
     * Gets filters.
     *
     * @return BlueZ_Datagrid_Filter_Interface
     */
    public function getColumns()
    {
        return $this->_columns;
    }
    
    /**
     * Gets the column from datagrid.
     * 
     * @param $alias string 
     * @return BlueZ_Datagrid_Column 
     */
    public function getColumn($alias)
    {
        if (isset($this->_columns[$alias])) {
            return $this->_columns[$alias];
        }
        
        return null;
    }

    /**
     * Add a column to datagrid.
     * Name of column must be unique.
     * 
     * @param $column BlueZ_Datagrid_Column
     */
    public function addColumn($column)
    {
        if ($column instanceof BlueZ_Datagrid_Column) {
            
            if (isset($this->_columns[$column->getAlias()])) {
                throw new BlueZ_Datagrid_Exception(
                        'Column with the same alias already exists.');
            }
            
            $column->setDatagrid($this);
            $this->_columns[$column->getAlias()] = $column;
        }
    }

    /**
     * Remove a column from datagrid.
     * 
     * @param $column BlueZ_Datagrid_Column | string
     */
    public function removeColumn($column)
    {
        if ($column instanceof BlueZ_Datagrid_Column) {
            $name = $column->getAlias();
        } else {
            $name = (string) $column;
        }
            
        if (isset($this->_columns[$name]) && $column === $this->_columns[$name]) {
            unset($this->_columns[$name]);
        }
             
        return false;
    }

    /**
     * Gets filters.
     *
     * @return BlueZ_Datagrid_Filter_Interface
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Gets a data filter.
     *
     * @param $alias string
     * @return BlueZ_Datagrid_Filter_Interface
     */
    public function getFilter($alias)
    {
        if (isset($this->_filters[$alias])) {
            return $this->_filters[$alias];
        }

        return null;
    }

    /**
     * Adds a filter to datagrid.
     * Name of the fuilter must be unique.
     *
     * @param $filter BlueZ_Datagrid_Filter_Interface
     */
    public function addFilter($filter)
    {
        if ($filter instanceof BlueZ_Datagrid_Filter_Interface) {

            if (isset($this->_filters[$filter->getAlias()])) {
                throw new BlueZ_Datagrid_Exception(
                        'Filter with the same alias already exists.');
            }

            $filter->setDatagrid($this);
            $this->_filters[$filter->getAlias()] = $filter;
        } else {
            throw new BlueZ_Datagrid_Exception(
                'Filter must be instance of BlueZ_Datagrid_Filter_Interface.');
        }
    }

    /**
     * Removes a filter from a datagrid.
     *
     * @param $filter BlueZ_Datagrid_Filter_Interface | string
     */
    public function removeFilter($filter)
    {
        if ($filter instanceof BlueZ_Datagrid_Filter_Interface) {
            $name = $filter->getAlias();
        } else {
            $name = (string) $filter;
        }

        if (isset($this->_filters[$name]) && $filter === $this->_filters[$name]) {
            unset($this->_filters[$name]);
        }

        return false;
    }

    /**
     * Initializes datagrid.
     */
    public function init()
    {
    }
        
    /**
     * Displays the data, which passes to renderer by fetch()
     */
    public function display()
    {
        echo $this->fetch();
    }

    /**
     * Gets the data and passes them to renderer.
     * 
     * @return BlueZ_Datagrid_Renderer
     */
    public function fetch()
    {
        // fetch data for rendering
        $data = $this->_fetchData();

        $this->_renderer->setSourceData($data);

        return $this->_renderer->render(null);
    }

    protected function _fetchData()
    {
        // applying sorts to data source
        $sorts = $this->_config->getSortBy();

        if (is_array($sorts) && count($sorts)) {

            $sourceSorts = array();

            foreach ($sorts as $sort) {
                if (isset($sort[BlueZ_Datagrid_Config::SORT_PART_NAME])) {
                    $alias = $sort[BlueZ_Datagrid_Config::SORT_PART_NAME];
                    $column = $this->getColumn($alias);

                    $temp = array();

                    if ($column !== null && $column->getSourceKey()) {
                        $temp[] = $column->getSourceKey();

                        if ($sort[BlueZ_Datagrid_Config::SORT_PART_DIRECTION]) {
                            $temp[] = $sort[BlueZ_Datagrid_Config::SORT_PART_DIRECTION];
                        }
                    }

                    $sourceSorts[] = $temp;
                }
            }

            $this->_source->sort($sourceSorts);
        }

        // applying filters
        foreach ($this->getFilters() as $filter) {
            $filter->apply($this->_source);
        }

        $perPage = $this->_config->getRecordsPerPage();

        // fetch data for page
        return $this->_source->fetch($this->_config->getFirstRowIndex(), $perPage);
    }
}