<?php

abstract class BlueZ_Datagrid_Pager_Abstract {
    
    /**
     * Number of current page.
     * 
     * @var integer
     */
    protected $_currentPage;
    
    protected $_rowsCount = null;
    
    /**
     * Parent datagrid instance.
     *
     * @var BlueZ_Datagrid
     */
    protected $_datagrid = null;
    
    /**
     * Pager constructor.
     *
     * @param BlueZ_Datagrid $datagrid
     */
    public function __construct($datagrid = null)
    {
        $this->setDatagrid($datagrid);
    }
    
    public function setDatagrid($datagrid)
    {
        $this->_datagrid = $datagrid;
    }
    
    public function getDatagrid()
    {
        return $this->_datagrid;
    }

    public function getCurrentPage()
    {        
        return $this->_datagrid->getConfig()->getPage();
    }  

    public function getRowsCount()
    {
        if ($this->_rowsCount === null) {
            $this->_rowsCount = $this->_datagrid->getSource()->count();
        }
        
        return $this->_rowsCount;
    }
    
    /**
     * Gets the number of pages.
     *
     * @return unknown
     */
    public function getPagesCount()
    {  
        
        $config = $this->_datagrid->getConfig();

        $rowsCount = $this->getRowsCount();
        $recordsPerPage = $config->getRecordsPerPage();
        
        if ($recordsPerPage == 0)  {
            return 1;
        }
        
        $pagesCount = (int) (($rowsCount - 1) / $recordsPerPage) + 1;
        
        return $pagesCount;
    }
    
    public function getLinks()
    {
        $config = $this->_datagrid->getConfig();
        
        $numbers = $this->getNumbers();
        
        $links = array();
        
        foreach ($numbers as $number) {
            if (is_numeric($number)) {
        	   $links[] = $config->buildUrl(null, $number);
            } else {
                $links[] = null;
            }
        }
        
        return $links;
    }
    
    abstract public function getNumbers();
}
