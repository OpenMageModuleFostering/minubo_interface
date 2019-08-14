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
 * @author     Sven Rothe <srothe@minubo.com>
 * */
class Minubo_Interface_IndexController extends Mage_Core_Controller_Front_Action
{
		public function indexAction () {
			$this->loadLayout(array('default'));
			$this->renderLayout();
		}
		public function mymethodeAction () {
			$this->loadLayout(array('default'));
			$this->renderLayout();
		}

		function getParam(&$lastChangeDate, &$lastOrderID, &$maxOrderID, &$limit) {
			$login = $this->getRequest()->getPost('login');
			if(!$login) $login='bxZ2vbChmellAibG2w1uWMmE87R65G';
			if($login!='bxZ2vbChmellAibG2w1uWMmE87R65G') die('You are not allowed to access this stuff.');

			$lastChangeDate = $this->getRequest()->getPost('order_id');
			if(!$lastChangeDate) $lastChangeDate='2000-01-01';

			$lastOrderID = $this->getRequest()->getPost('last_order_id');
			if(!$lastOrderID) $lastOrderID=100000000;

			$maxOrderID = $this->getRequest()->getPost('max_order_id');
			if(!$maxOrderID) $maxOrderID=999999999;

			$limit = $this->getRequest()->getPost('limit');
			if(!$limit) $limit=1000;
		}

		public function ordersAction ()
		{
			$this->getParam($lastChangeDate, $lastOrderID, $maxOrderID, $limit);

			switch(Mage::getStoreConfig('order_export/export_orders/output_type')){
				case 'Standard':
					$orders = Mage::getModel('minubo_interface/read_orders')->read($lastChangeDate, $lastOrderID, $maxOrderID, $limit);
					$file = Mage::getModel('minubo_interface/export_csv')->exportOrders($orders);
					echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
					break;
			}
		}
		public function orderCustomersAction ()
		{
			$this->getParam($lastChangeDate, $lastOrderID, $maxOrderID, $limit);

			switch(Mage::getStoreConfig('order_export/export_orders/output_type')){
			case 'Standard':
					$orders = Mage::getModel('minubo_interface/read_orders')->read($lastChangeDate, $lastOrderID, $maxOrderID, $limit);
					$file = Mage::getModel('minubo_interface/export_csv')->exportOrderCustomers($orders);
					echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
					break;
			}
		}
		public function orderItemsAction ()
		{
			$this->getParam($lastChangeDate, $lastOrderID, $maxOrderID, $limit);

			switch(Mage::getStoreConfig('order_export/export_orders/output_type')){
				case 'Standard':
					$orders = Mage::getModel('minubo_interface/read_orders')->read($lastChangeDate, $lastOrderID, $maxOrderID, $limit);
					$file = Mage::getModel('minubo_interface/export_csv')->exportOrderItems($orders);
					echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
					break;
			}
		}
		public function orderProductsAction ()
		{
			$this->getParam($lastChangeDate, $lastOrderID, $maxOrderID, $limit);

			switch(Mage::getStoreConfig('order_export/export_orders/output_type')){
				case 'Standard':
					$orders = Mage::getModel('minubo_interface/read_orders')->read($lastChangeDate, $lastOrderID, $maxOrderID, $limit);
					$file = Mage::getModel('minubo_interface/export_csv')->exportOrderProducts($orders);
					echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
					break;
			}
		}

		public function productsAction ()
		{
			$this->getParam($lastChangeDate, $lastOrderID, $maxOrderID, $limit);

			switch(Mage::getStoreConfig('order_export/export_orders/output_type')){
				case 'Standard':
					$products = Mage::getModel('minubo_interface/read_products')->read($lastChangeDate, $lastOrderID, $maxOrderID, $limit);
					$file = Mage::getModel('minubo_interface/export_csv')->exportProducts($products);
					echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
					break;
			}
		}

		/**
	   * Exports orders defined by id in post param "order_ids" to csv and offers file directly for download
	   * when finished.
	   */
	  public function ordersDownloadAction()
	  {
	  	// $orders = $this->getRequest()->getPost('order_ids', array());
	  	$orders = Mage::getModel('sales/order')->getCollection();

			switch(Mage::getStoreConfig('order_export/export_orders/output_type')){
				case 'Standard':
					$file = Mage::getModel('minubo_interface/export_csv')->exportOrders($orders);
					$this->_prepareDownloadResponse($file, file_get_contents(Mage::getBaseDir('export').'/'.$file));
					break;
			}
	  }

	  public function getHashAction($qtd = 30){
			$chars = '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
			$anz = strlen($chars);
			$anz--;
			$hash=NULL;
			    for($x=1;$x<=$qtd;$x++){
			        $c = rand(0,$anz);
			        $hash .= substr($chars,$c,1);
			    }
			echo $hash;
		}

}
?>