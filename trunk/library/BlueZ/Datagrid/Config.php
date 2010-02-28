<?php

/**
 * Pick, prepare and store configuration parameters for datagrid.
 *
 * @category    Zend
 * @package     BlueZ_Datagrid
 * @author      Eugene Zabolotniy
 */
class BlueZ_Datagrid_Config
{
    /**
     * A default prefix for datagrid parameters.
     */
    const PREFIX_DEFAULT = 'zdg_';

    const PAGE = 'page';
    const RECORDS_PER_PAGE = 'perpage';
    const SORT_BY = 'sortby';
    const FILTER = 'filter_';
    const OPTION = 'option_';
    
    const SORT_PART_RAW         = 'raw';
    const SORT_PART_NAME        = 'name';
    const SORT_PART_DIRECTION   = 'direction';

    /**
     * @var integer
     */
    protected $_page = 1;
    
    /**
     * @var integer
     */
    protected $_recordsPerPage = 10;

    /**
     * @var array
     */
    protected $_sortBy = null;
    
    /**
     * Total records of source.
     *
     * @var integer
     */
    protected $_totalRecords = null;

    /**
     * @var array
     */
    protected $_filters = array();

    /**
     * @var array
     */
    protected $_options = array();
    
    /**
     * @var string
     */
    protected $_prefix = self::PREFIX_DEFAULT;
    
    /**
     * A public constructor for config.
     * 
     * @var $prefix     a prefix for datagrid parameters
     */
    public function __construct($prefix = self::PREFIX_DEFAULT)
    {
        $this->setPrefix($prefix);
    }
    
    /**
     * Parse sort string.
     *
     * @param string $sort
     * @return array of sorts
     */
    public function parseSort($sort)
    {
        $sort = trim($sort);

        $parts = array();

        if ($sort) {
            $pattern = '/^(.+?)(?:\s+(ASC|DESC))?$/i';

            preg_match($pattern, $sort, $parts);
            
            $parts = array (
                self::SORT_PART_RAW       => $parts[0],
                self::SORT_PART_NAME      => $parts[1],
                self::SORT_PART_DIRECTION => isset($parts[2]) ? $parts[2] : ''
            );

            return $parts;
        } else {
            return null;
        }
    }
    
    /**
     * Parse an arrayof sorts.
     *
     * @param array $sorts
     * @return array of arrays
     */
    public function parseSorts($sorts)
    {
        $parsed = array();
        
        foreach($sorts as $sort) {
            $sort = $this->parseSort($sort);
            if ($sort) {
                $parsed[] = $sort;
            }
        }
        
        return $parsed;
    }

    /**
     * Gets filters.
     * 
     * @return array
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Gets a filter with name specified in parameter $alias.
     *
     * @return string
     */
    public function getFilter($alias)
    {
        if (isset($this->_filters[$alias])) {
            return $this->_filters[$alias];
        }
        return null;
    }

    /**
     * @return integer
     */
    public function getPage()
    {        
        return $this->_page;
    }

    /**
     * Gets options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Gets a option with name specified in parameter $alias.
     *
     * @return string
     */
    public function getOption($alias, $default = null)
    {
        if (isset($this->_options[$alias])) {
            return $this->_options[$alias];
        }
        return $default;
    }
    
    /**
     * Gets a current page first row index.
     * 
     * @return integer 
     */
    public function getFirstRowIndex()
    {
        return ($this->_page - 1) * $this->_recordsPerPage;
    }
    
    /**
     * Gets a number of lines per one page.
     * 
     * @return integer
     */
    public function getRecordsPerPage()
    {
        return $this->_recordsPerPage;
    }

    /**
     * Return an array of sorting options.
     * 
     * @return array
     */
    public function getSortBy()
    {
        return $this->_sortBy;
    }

    /**
     * Sets a filter.
     *
     * @param string $alias
     * @param string $value
     * @return BlueZ_Datagrid_Config
     */
    public function setFilter($alias, $value)
    {
        $this->_filters[$alias] = $value;
        return $this;
    }

    /**
     * Sets filter set.
     * 
     * @param array $filters
     * @return BlueZ_Datagrid_Config
     */
    public function setFilters($filters)
    {
        $this->_filters = $filters;
        return $this;
    }

    /**
     * Sets an option.
     *
     * @param string $alias
     * @param string $value
     * @return BlueZ_Datagrid_Config
     */
    public function setOption($alias, $value)
    {
        $this->_options[$alias] = $value;
        return $this;
    }

    /**
     * Sets options set.
     *
     * @param array $filters
     * @return BlueZ_Datagrid_Config
     */
    public function setOptions($options)
    {
        $this->_options = $options;
        return $this;
    }

    /**
     * Set a number of lines per one page.
     * 
     * @param integer $page
     * @return BlueZ_Datagrid_Config
     */
    public function setRecordsPerPage($records)
    {
        $this->_recordsPerPage = $records;
        return $this;
    }
    
    /**
     * Sets a current page.
     * 
     * @param integer $page
     * @return BlueZ_Datagrid_Config
     */
    public function setPage($page)
    {
        $this->_page = $page;
        return $this;
    }

    /**
     * Sets an array of sorting options.
     * 
     * @param array $sortBy
     * @return BlueZ_Datagrid_Config
     */
    public function setSortBy($sortBy)
    {
        $this->_sortBy = $sortBy;
        return $this;
    }

    /**
     * Gets a prefix for datagrid parameters.
     * 
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * Sets a prefix for datagrid parameters.
     * 
     * @param string $_prefix
     * @return BlueZ_Datagrid_Config
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this->load();
    }

    /**
     * Load a parameters from request.
     */
    public function load()
    {
        $prefix = $this->getPrefix();
        
        foreach ($_REQUEST as $param => $value) {
            // if starts with...
            if (strpos($param, $prefix . self::FILTER) === 0) {
                $this->_filters[substr($param, strlen($prefix . self::FILTER))] = $value;
            } elseif (strpos($param, $prefix . self::OPTION) === 0) {
                $this->_options[substr($param, strlen($prefix . self::OPTION ))] = $value;
            }
        }

        if (isset($_REQUEST[$prefix . self::RECORDS_PER_PAGE]) && is_numeric($_REQUEST[$prefix . self::RECORDS_PER_PAGE])) {
            $this->_recordsPerPage = $_REQUEST[$prefix . self::RECORDS_PER_PAGE];
        }
        if (isset($_REQUEST[$prefix . self::PAGE]) && is_numeric($_REQUEST[$prefix . self::PAGE])) {
            $this->_page = $_REQUEST[$prefix . self::PAGE];
        }
        if (isset($_REQUEST[$prefix . self::SORT_BY])) {
            if (is_array($_REQUEST[$prefix . self::SORT_BY])) {
                $this->_sortBy = $this->parseSorts($_REQUEST[$prefix . self::SORT_BY]);
            } else {
                $this->_sortBy = $this->parseSorts(array($_REQUEST[$prefix . self::SORT_BY]));
            }
        }
        return $this;
    }
    
    /**
     * Build an URL using an exictiong configuration and passed parameters.
     * 
     * @var $perpage    if is set overrides a records per page option
     * @var $page       if is set overrides a current page option
     * @var $sortby     if is set overrides a sort options
     * @var $filters    if is set overrides a filter conditions set
     * @var $custom     custom parameters
     */
    public function buildUrl($perpage = null, $page = null, $sortby = null, $filters = null, $custom = null)
    {
        $prefix = $this->getPrefix();
        $params = $_GET;
        
        if (is_array($custom)) {
            $params = array_merge($params, $custom);
        }
        
        if (is_null($perpage)) {
            $params[$prefix . self::RECORDS_PER_PAGE] = $this->getRecordsPerPage();
        } else {
            $params[$prefix . self::RECORDS_PER_PAGE] = $perpage;
        }
        
        if (is_null($page)) {
            $params[$prefix . self::PAGE] = $this->getPage();
        } else {
            $params[$prefix . self::PAGE] = $page;
        }

        if (!is_null($sortby)) {
            $params[$prefix . self::SORT_BY] = $sortby;
        } elseif (is_array($this->getSortBy())) {
            $sorts = array();
            foreach ($this->getSortBy() as $sort) {
                $sorts[] = $sort['raw'];
            }
            $params[$prefix . self::SORT_BY] = $sorts;
        }
        
        /*if (!is_null($filters)) {
            $params[$prefix . self::FILTERS] = $filters;
        } elseif (is_array($this->getFilters())) {
            $params[$prefix . self::FILTERS] = $this->getFilters();
        }*/
        
        if (is_array($custom)) {
            $params = array_merge($params, $custom);
        }
        
        // Bug ?
//        if(isset($_SERVER['REDIRECT_URL'])) {
//            $url = $_SERVER['REDIRECT_URL'] . '?';
//        } else {
//            $url = $_SERVER['SCRIPT_NAME'] . '?';
//        }
        
        $url = '?';
        
        foreach ($params as $param => $value) {
            if(is_array($value)) {
                foreach ($value as $value2) {
                    $url .= $param . '[]=' . $value2 . '&';
                }
            } else {
                $url .= $param . '=' . $value . '&';
            }
        }
        
        $url = substr($url, 0, -1);
             
        return $url;
    }   

}
