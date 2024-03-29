<?php
/**
 * Magento Minubo Interface Export Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Minubo
 * @package    Minubo_Interface
 * @copyright  Copyright (c) 2013 Minubo (http://www.minubo.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Sven Rothe <sven@minubo.com>
 * */

class Minubo_Interface_Model_Tables extends Minubo_Interface_Model_Read_Common
{
    protected function _construct()
    {
        parent::_construct();
    }

	public function init($ent)
	{
		$this->_init('minubo_interface/'.$ent);
    }

    public function read($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id)
    {
		$data = $this->getResource()->loadFiltered($lastChangeDate, $lastOrderID, $maxOrderID, $limit);
	    return $data;
    }

    public function readAll()
    {
		$data = $this->getResource()->loadAll();
	    return $data;
    }
    public function readAllByStoreId($storeId='1')
    {
		$data = $this->getResource()->loadAllByStoreId($storeId);
	    return $data;
    }

    public function readLimited($limit, $offset)
    {
    	$data = $this->getResource()->loadLimited($limit, $offset);
    	return $data;
    }
		public function readLimitedByStoreId($limit, $offset, $storeId='1')
    {
    	$data = $this->getResource()->loadLimitedByStoreId($limit, $offset, $storeId);
    	return $data;
    }

}
?>