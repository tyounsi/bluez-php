<?php 
require_once 'BlueZ/Datagrid/Pager/Abstract.php';

/**
 * A class for an page navigation.
 * 
 * @author Pavel Machekhin
 */
class BlueZ_Datagrid_Pager extends BlueZ_Datagrid_Pager_Abstract
{
    public function getNumbers()
    {        
        
        $totalPages  = $this->getPagesCount();
        
        $pages = array();
        
        for($i = 1; $i <= $totalPages; $i++)
        {
            $pages[] = $i;
        }
        
        return $pages;
    }
}