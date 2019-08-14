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

	public function versionAction()
	{
		// echo Mage::getStoreConfig('minubo_interface/settings/version',Mage::app()->getStore());
		echo (string) Mage::getConfig()->getNode()->modules->Minubo_Interface->version;
	}

	public function counterAction()
	{
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id, $download);

		$countries = Mage::getModel('minubo_interface/read_collections')->readCountries($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		echo '# Countries: '.count($countries).'<br>';

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('regions');
		$regions = $model->readAll();
		echo '# Regions: '.count($regions).'<br>';

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('categories'.($store_id=='1'?'':$store_id));
		$categories = $model->readAll();
		echo '# Categories: '.count($categories).'<br>';

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('products'.($store_id=='1'?'':$store_id));
		$products = $model->readAll();
		echo '# Products: '.count($products).'<br>';

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('productattributes');
		$productattributes = $model->readAll();
		echo '# ProductAttributes: '.count($productattributes).'<br>';

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('productcategories');
		$productcategories = $model->readAll();
		echo '# ProductCategories: '.count($productcategories).'<br>';

		// $orders = Mage::getModel('minubo_interface/read_collections')->read($lastChangeDate, '', $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		$model = Mage::getModel('minubo_interface/tables');
		$model->init('orders');
		$orders = $model->readAll();
		echo '# Orders: '.count($orders).'<br>';
		$model = Mage::getModel('minubo_interface/tables');
		$model->init('orderitems');
		$orderitems = $model->readAll();
		echo '# OrderItems: '.count($orderitems).'<br>';

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('creditmemos');
		$creditmemos = $model->readAll();
		echo '# CreditMemos: '.count($creditmemos).'<br>';

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('creditmemoitems');
		$creditmemoitems = $model->readAll();
		echo '# CreditMemoItems: '.count($creditmemoitems).'<br>';

		$model = Mage::getModel('minubo_interface/tables');
		$model->init('invoices');
		$invoices = $model->readAll();
		echo '# Invoices: '.count($invoices).'<br>';

	}

	public function getMicrotime() {
    $mtime = microtime();
    $mtime = explode(' ', $mtime);
    return doubleval($mtime[1]) + doubleval($mtime[0]);
  }

  public function getStartlog() {
  	return $this->getMicrotime();
  }

	public function getEndlog($start) {
		return '<br># runtime: '.abs($this->getMicrotime()-$start).'<br>'.
					'# memory_get_usage(true): '.memory_get_usage(true).'<br>'.
					'# memory_get_usage(false): '.memory_get_usage(false).'<br>'.
					'# memory_get_peak_usage(true): '.memory_get_peak_usage(true).'<br>'.
					'# memory_get_peak_usage(false): '.memory_get_peak_usage(false).'<br>';
  }

	function getParam(&$lastChangeDate, &$maxChangeDate, &$lastOrderID, &$maxOrderID, &$limit, &$offset, &$debug, &$pdata, &$store_id, &$download) {

		$debug = $this->getRequest()->getPost('debug');
		if($debug) echo '# memory_get_usage(true): '.memory_get_usage(true).'<br>';
		if($debug) echo '# memory_get_usage(false): '.memory_get_usage(false).'<br>';

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

		$pdata = $this->getRequest()->getPost('pdata');

		$download = $this->getRequest()->getPost('download');

        $endtime = str_replace('.','-',Mage::getStoreConfig('minubo_interface/settings/lastexportenddate',Mage::app()->getStore()));
        if(time()-strtotime($endtime)>(600)) {
            $config = new Mage_Core_Model_Config();
            $config->saveConfig('minubo_interface/settings/lastexportstartdate', str_replace('.','-',date('Y.m.d H:i:s')), 'default', 0);
            $config = null;
        }
	}

	public function countriesAction ()
	{
		$start = $this->getStartlog();
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id, $download);
		$countries = Mage::getModel('minubo_interface/read_collections')->readCountries($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id);
		$this->handleCountries($countries,'country','countries','',$start,$download);
		if($debug) echo $this->getEndlog($start);
	}

	public function handleCountries (&$rows, $filename, $type, $pdata, $start, $download)
	{
		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$file = Mage::getModel('minubo_interface/export_csv')->exportCountries($rows, $filename, $type, $pdata);
				if (!$download) {
					echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				} else {
					$this->_prepareDownloadResponse($file, file_get_contents(Mage::getBaseDir('export').'/'.$file));
				}
				break;
		}
		$config = new Mage_Core_Model_Config();
		$config->saveConfig('minubo_interface/settings/lastexportenddate', str_replace('.','-',date('Y.m.d H:i:s')), 'default', 0);
		$config = null;

		$rows = null;
		$file = null;
	}
	
	/*
	 * load and export of data of type TABLE
	 * Read data directly from tables defined in config.xml and Model/Mysql4
	 */

	public function ordersAction ()
	{
		$renameCols = array('entity_id' => 'order_id');
		$this->handleTable ('orders', 'order', 'orders', Array(), Array(), $renameCols);
	}

	public function orderItemsAction ()
	{
		$renameCols = array('item_id' => 'orderitem_id');
		$this->handleTable ('orderitems', 'orderitem', 'orderitems', Array(), Array(), $renameCols);
	}

    public function orderCustomersAction ()
    {
        $this->handleTable ('orderaddresses', 'orderaddr', 'orderAddresses', Array(), Array(), Array());
    }

    public function customersAction ()
	{
		$renameCols = array('entity_id' => 'customer_id');
		$this->handleTable ('customers', 'customer', 'customers', Array(), Array(), $renameCols);
	}
	
	public function customerAddressesAction ()
	{
		$this->handleTable ('orderaddresses', 'orderaddr', 'orderaddresses', Array(), Array(), Array());
	}
	
	public function productsAction ()
	{
		$skipCols = array('description', 'in_depth', 'activation_information');
        $this->handleTable ('products', 'product', 'products', Array(), $skipCols, Array());
	}

	public function categoriesAction ()
	{
		$renameCols = array('entity_id' => 'category_id');
		$colTitles = array('Category_Id','Parent_Id','Position','Category_Name','level','image','url_key','url_path');
        $this->handleTable ('categories', 'category', 'categories', $colTitles, Array(), $renameCols);
 	}

	public function productcategoriesAction ()
	{
		$skipCols = array('is_parent');
		$colTitles = array('category_id','product_id','position','store_id','visibility');
        $this->handleTable ('productcategories', 'productcategory', 'productcategories', $colTitles, $skipCols, Array());
	}

	public function productattributesAction ()
	{
		$renameCols = array('attribute_set_id' => 'setKey',
												'attribute_set_name' => 'setName',
												'attribute_id' => 'attributeKey',
												'attribute_code' => 'attributeCode',
												'backend_type' => 'attributeType',
												'is_required' => 'attributeRequired',
												'value' => 'optionLabel',
												'value_id' => 'optionValue');
        $this->handleTable ('productattributes', 'productattribute', 'productattributes', Array(), Array(), $renameCols);
	}

	public function regionsAction ()
	{
		$colTitles = array('Region_Id','Country_Id','Region_Code','Region_Name');
        $this->handleTable ('regions', 'region', 'regions', $colTitles, Array(), Array());
	}

	public function creditmemosAction ()
	{
		$this->handleTable ('creditmemos', 'creditmemo', 'creditmemos', Array(), Array(), Array());
	}
	public function creditmemoItemsAction ()
	{
		$this->handleTable ('creditmemoitems', 'creditmemoitem', 'creditmemoitems', Array(), Array(), Array());
	}

	public function invoicesAction ()
	{
		$this->handleTable ('invoices', 'invoice', 'invoices', Array(), Array(), Array());
	}
	public function invoiceItemsAction ()
	{
		$this->handleTable ( 'invoiceitems', 'invoiceitem', 'invoiceitems', Array(), Array(), Array());
	}

	public function handleTable ($sqlinterface, $filename, $type, $colTitles = Array(), $skipCols = Array(), $renameCols = Array())
	{
		$start = $this->getMicrotime();
		$this->getParam($lastChangeDate, $maxChangeDate, $lastOrderID, $maxOrderID, $limit, $offset, $debug, $pdata, $store_id, $download);

		switch(Mage::getStoreConfig('minubo_interface/settings/output_type')){
			case 'Standard':
				$model = Mage::getModel('minubo_interface/tables');
				$model->init($sqlinterface.($store_id=='1'?'':$store_id));
				$rows = $model->readLimited($limit, $offset);

				if (count($colTitles)==0) {
					$colTitles = $rows[0]; // first data-row: Array ( [attribute_set_id] => 38 ...
				} else {
					$colTitles = array_flip($colTitles); // titles: Array ( [0] => Spalte ...
				}

				$file = Mage::getModel('minubo_interface/export_csv')->exportTable($rows, $filename, $type, $colTitles, $skipCols, $renameCols);
				if (!$download) {
					echo file_get_contents(Mage::getBaseDir('export').'/'.$file);
				} else {
					$this->_prepareDownloadResponse($file, file_get_contents(Mage::getBaseDir('export').'/'.$file));
				}
				break;
		}
		$config = new Mage_Core_Model_Config();
		$config->saveConfig('minubo_interface/settings/lastexportenddate', str_replace('.','-',date('Y.m.d H:i:s')), 'default', 0);
		$config = null;

		$model = null;
		$rows = null;
		$file = null;
		if($debug) echo $this->getEndlog($start);
	}

}
?>