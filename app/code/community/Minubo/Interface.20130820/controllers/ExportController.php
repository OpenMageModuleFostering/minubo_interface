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
class Minubo_Interface_ExportController extends Mage_Core_Controller_Front_Action
{
	
	public function indexAction () {
		$this->loadLayout(array('default'));
		$this->renderLayout();
	}
	public function mymethodeAction () {
		$this->loadLayout(array('default'));
		$this->renderLayout();
	}

	function getParam(&$lastChangeDate, &$maxChangeDate, &$lastOrderID, &$maxOrderID, &$limit, &$offset, &$debug, &$pdata, &$store_id) {
		
		$enabled = Mage::getStoreConfig('minubo_interface/settings/active',Mage::app()->getStore()); 
		if(!$enabled) die('Minubo Interface is disabled.');
		
		$login = $this->getRequest()->getPost('login');
		$hash = Mage::getStoreConfig('minubo_interface/settings/hash',Mage::app()->getStore()); 
		if($login!=$hash) die('You are not allowed to access this stuff.');

		$lastChangeDate = $this->getRequest()->getPost('last_change_date');
		// if(!$lastChangeDate) $lastChangeDate='2000-01-01';

		$maxChangeDate = $this->getRequest()->getPost('max_change_date');
		// if(!$maxChangeDate) $maxChangeDate='2099-12-31';

		$lastOrderID = $this->getRequest()->getPost('last_order_id');
		// if(!$lastOrderID) $lastOrderID=0;

		$maxOrderID = $this->getRequest()->getPost('max_order_id');
		// if(!$maxOrderID) $maxOrderID=9999999999;

		$limit = $this->getRequest()->getPost('limit');
		if(!$limit) $limit=1000;

		$offset = $this->getRequest()->getPost('offset');
		if(!$offset) $offset=0;
		
		$store_id = $this->getRequest()->getPost('store_id');
		if(!$store_id) $store_id=1;
		
		$debug = $this->getRequest()->getPost('debug');
		$pdata = $this->getRequest()->getPost('pdata');
		
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportstartdate', date('Y.m.d H:i:s'), 'default', 0);
		
	}

	public function ordersAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$orders = Mage::getModel('minubo_interface/read_collections')->read($lastChangeDate, '', $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
				$file = Mage::getModel('minubo_interface/export_csv')->exportOrders($orders);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}
	public function orderCustomersAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		$maxChangeDate = (string) Mage::getStoreConfig('minubo_interface/settings/lastchangedate',Mage::app()->getStore());

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$orders = Mage::getModel('minubo_interface/read_collections')->read($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
				$file = Mage::getModel('minubo_interface/export_csv')->exportOrderCustomers($orders, $pdata);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}
	public function orderItemsAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		$maxChangeDate = (string) Mage::getStoreConfig('minubo_interface/settings/lastchangedate',Mage::app()->getStore());
		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$orders = Mage::getModel('minubo_interface/read_collections')->read($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
				$file = Mage::getModel('minubo_interface/export_csv')->exportOrderItems($orders);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}
	public function productsAction2 ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		$maxChangeDate = (string) Mage::getStoreConfig('minubo_interface/settings/lastchangedate',Mage::app()->getStore());

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$products = Mage::getModel('minubo_interface/read_collections')->readProducts($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
				$file = Mage::getModel('minubo_interface/export_csv')->exportProducts($products);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}

	public function customersAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$customers = Mage::getModel('minubo_interface/read_collections')->readCustomers($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
				$file = Mage::getModel('minubo_interface/export_csv')->exportCustomers($customers, $pdata);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}

	public function countriesAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$countries = Mage::getModel('minubo_interface/read_collections')->readCountries($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
				$file = Mage::getModel('minubo_interface/export_csv')->exportCountries($countries);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}

	public function productsAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('products'.($store_id=='1'?'':$store_id));
				$products = $model->readLimited($limit, $offset);
				$file = Mage::getModel('minubo_interface/export_csv')->exportProducts($products);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}

	public function categoriesAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('categories'.($store_id=='1'?'':$store_id));
				$categories = $model->readLimited($limit, $offset);
				$file = Mage::getModel('minubo_interface/export_csv')->exportCategories($categories);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}

	public function productcategoriesAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		
		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('productcategories');
				$productcategories = $model->readLimited($limit, $offset);
				
				$file = Mage::getModel('minubo_interface/export_csv')->exportProductcategories($productcategories);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}

	public function productattributesAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		
		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('productattributes');
				$productattributes = $model->readLimited($limit, $offset);
				
				$file = Mage::getModel('minubo_interface/export_csv')->exportProductattributes($productattributes);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}

	public function regionsAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('regions');
				$regions = $model->readLimited($limit, $offset);
				$file = Mage::getModel('minubo_interface/export_csv')->exportRegions($regions);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}

	public function creditmemoAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('creditmemo'.($store_id=='1'?'':$store_id));
				$creditmemos = $model->readLimited($limit, $offset);
				$file = Mage::getModel('minubo_interface/export_csv')->exportProducts($creditmemos);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}
	public function creditmemoItemsAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('creditmemoitem'.($store_id=='1'?'':$store_id));
				$creditmemoitems = $model->readLimited($limit, $offset);
				$file = Mage::getModel('minubo_interface/export_csv')->exportProducts($creditmemoitems);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}
	
	public function invoicesAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('invoice'.($store_id=='1'?'':$store_id));
				$invoices = $model->readLimited($limit, $offset);
				$file = Mage::getModel('minubo_interface/export_csv')->exportProducts($invoices);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}
	public function invoiceItemsAction ()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init('invoiceitem'.($store_id=='1'?'':$store_id));
				$invoiceitems = $model->readLimited($limit, $offset);
				$file = Mage::getModel('minubo_interface/export_csv')->exportProducts($invoiceitems);
				echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				break;
		}
		$config = new Mage_Core_Model_Config(); 
		$config->saveConfig('minubo_interface/settings/lastexportenddate', date('Y.m.d H:i:s'), 'default', 0);
	}
	
	
	/**
	 * Exports orders defined by id in post param "order_ids" to csv and offers file directly for download
	 * when finished.
	 */
	public function ordersDownloadAction()
	{
		// $orders = $this->getRequest()->getPost('order_ids', array());
		$orders = Mage::getModel('sales/order')->getCollection();

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$file = Mage::getModel('minubo_interface/export_csv')->exportOrders($orders);
				$this->_prepareDownloadResponse($file, file_get_contents(Mage::getBaseDir('export').'/'.$file));
				break;
		}
	}

	public function counterAction($qtd = 30)
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('products'.($store_id=='1'?'':$store_id));
		$products = $model->readAll();
		echo '# Products: '.count($products).'<br>';
		
		$model = Mage::getModel('minubo_interface/tables');
		$model->init('categories'.($store_id=='1'?'':$store_id));
		$categories = $model->readAll();
		echo '# Categories: '.count($categories).'<br>';
		
		$model = Mage::getModel('minubo_interface/tables');
		$model->init('productattributes');
		$productattributes = $model->readAll();
		echo '# ProductAttributes: '.count($productattributes).'<br>';
		
		$model = Mage::getModel('minubo_interface/tables');
		$model->init('productcategories');
		$productcategories = $model->readAll();
		echo '# ProductCategories: '.count($productcategories).'<br>';
		
		$countries = Mage::getModel('minubo_interface/read_collections')->readCountries($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		echo '# Countries: '.count($countries).'<br>';
		
		$model = Mage::getModel('minubo_interface/tables');
		$model->init('regions');
		$regions = $model->readAll();
		echo '# Regions: '.count($regions).'<br>';
		
		$orders = Mage::getModel('minubo_interface/read_collections')->read($lastChangeDate, '', $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		echo '# Orders: '.count($orders).'<br>';
		
	}

	public function getHashAction($qtd = 30)
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
		$anz = strlen($chars);
		$anz--;
		$hash=NULL;
		for($x=1;$x<=$qtd;$x++){
			$c = rand(0,$anz);
			$hash .= substr($chars,$c,1);
		}
		Mage::getConfig()->setNode('minubo_interface/settings/hash', $hash);
		echo $hash;
	}

	public function newHashAction($qtd = 30)
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
		$anz = strlen($chars);
		$anz--;
		$hash=NULL;
		for($x=1;$x<=$qtd;$x++){
			$c = rand(0,$anz);
			$hash .= substr($chars,$c,1);
		}
		Mage::getConfig()->setNode('minubo_interface/settings/hash', $hash);
		Mage::app()->getResponse()->setBody($hash);
	}

	public function versionAction()
	{
		// echo Mage::getStoreConfig('minubo_interface/settings/version',Mage::app()->getStore());
		echo (string) Mage::getConfig()->getNode()->modules->Minubo_Interface->version;
	}

}
?>