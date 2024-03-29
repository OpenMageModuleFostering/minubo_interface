<?php
class Minubo_Interface_Model_Mysql4_Orderaddresses extends Mage_Core_Model_Mysql4_Abstract
{
public function _construct() {
	$this->_init('minubo_interface/orderaddresses', 'entity_id');
}

protected function getColumns() {
	$r = array('oab.entity_id as customerId','o.created_at','oab.customer_address_id as incrementKey','o.store_id',
		'o.store_id as websiteKey','o.store_name','o.customer_group_id','o.customer_dob','oab.prefix',
		"ifnull(oab.customer_address_id,concat('g_',oab.entity_id)) as customerNumber",
		'oab.entity_id as billingCustomerAddressKey','oab.entity_id as billingIncrementKey','oab.city',
		'oab.country_id','oab.postcode','oab.region','oab.region_id','oab.address_type',
		'oas.entity_id as shippingIncrementKey','oas.city as shippingCity','oas.country_id as shippingCountry',
		'oas.postcode as shippingPostcode','oas.region as shippingRegion','oas.region_id as shippingRegionId',
		'oas.address_type as shippingAddressType','md5(lower(o.customer_email)) as Customer_HashCode');
	$showemail = Mage::getStoreConfig('minubo_interface/settings/showemail',Mage::app()->getStore());
	if($showemail) 
		$field1 = 'email';
	else
		$field1 = 'substring(oab.email,1,0) as email';
	$fields2 = array('cg.customer_group_code',"ifnull(oab.customer_id,md5(lower(o.customer_email))) as customerKey");
	return array_merge($r, array($field1), $fields2);
}

/*
	select
		oab.entity_id as customerId, o.created_at, oab.customer_address_id as incrementKey, o.store_id,
		o.store_id as websiteKey, o.store_name,o.customer_group_id, o.customer_dob, oab.prefix,
		ifnull(oab.customer_address_id,concat(g_,oab.entity_id)) as customerNumber,
		oab.entity_id as billingCustomerAddressKey, oab.entity_id as billingIncrementKey,oab.city,
		oab.country_id, oab.postcode, oab.region, oab.region_id, oab.address_type,
		oas.entity_id as shippingIncrementKey, oas.city as shippingCity, oas.country_id as shippingCountry,
		oas.postcode as shippingPostcode, oas.region as shippingRegion,oas.region_id as shippingRegionId,
		oas.address_type as shippingAddressType, md5(lower(o.customer_email)) as Customer_HashCode,
		email, cg.customer_group_code, ifnull(oab.customer_id, md5(lower(o.customer_email))) as customerKey
	from sales_flat_order_address oab
	left outer join sales_flat_order o on o.billing_address_id = oab.entity_id
	left outer join sales_flat_order_address oas on o.shipping_address_id = oas.entity_id 
	left outer join customer_group cg on o.customer_group_id = cg.customer_group_id 
	where o.store_id = 1
*/

public function loadByField($field,$value){
	$table = $this->getMainTable();
	$table2 = $this->getTable('sales_flat_order');
	$cond2 = $this->_getReadAdapter()->quoteInto('o.billing_address_id = oab.entity_id','');
	$table3 = $this->getTable('sales_flat_order_address');
	$cond3 = $this->_getReadAdapter()->quoteInto("o.shipping_address_id = oas.entity_id ",'');
	$table4 = $this->getTable('customer_group');
	$cond4 = $this->_getReadAdapter()->quoteInto('o.customer_group_id = cg.customer_group_id','');
	$where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
	$select = $this->_getReadAdapter()->select()
		->from(array('oab'=>$table))
		->join(array('o'=>$table2), $cond2)
		->join(array('oas'=>$table3), $cond3)
		->join(array('cg'=>$table4), $cond4)
		->reset('columns')
		->columns($this->getColumns())
		->where($where);
	$id = $this->_getReadAdapter()->fetchOne($select);
	return $id;
}

public function loadAllByStoreId($store_id){
	$table = $this->getMainTable();
	$table2 = $this->getTable('sales_flat_order');
	$cond2 = $this->_getReadAdapter()->quoteInto('o.billing_address_id = oab.entity_id','');
	$table3 = $this->getTable('sales_flat_order_address');
	$cond3 = $this->_getReadAdapter()->quoteInto("o.shipping_address_id = oas.entity_id ",'');
	$table4 = $this->getTable('customer_group');
	$cond4 = $this->_getReadAdapter()->quoteInto('o.customer_group_id = cg.customer_group_id','');
	$where = $this->_getReadAdapter()->quoteInto("o.store_id = ?", $store_id);
	$select = $this->_getReadAdapter()->select()
		->from(array('oab'=>$table))
		->join(array('o'=>$table2), $cond2)
		->join(array('oas'=>$table3), $cond3)
		->join(array('cg'=>$table4), $cond4)
		->reset('columns')
		->columns($this->getColumns())
		->where($where)
		->order('o.entity_id');
	return $this->_getReadAdapter()->fetchAll($select);
	}

public function loadLimitedByStoreId($limit, $offset, $store_id){
	$table = $this->getMainTable();
	$table2 = $this->getTable('sales_flat_order');
	$cond2 = $this->_getReadAdapter()->quoteInto('o.billing_address_id = oab.entity_id','');
	$table3 = $this->getTable('sales_flat_order_address');
	$cond3 = $this->_getReadAdapter()->quoteInto("o.shipping_address_id = oas.entity_id ",'');
	$table4 = $this->getTable('customer_group');
	$cond4 = $this->_getReadAdapter()->quoteInto('o.customer_group_id = cg.customer_group_id','');
	$where = $this->_getReadAdapter()->quoteInto("o.store_id = ?", $store_id);
	$select = $this->_getReadAdapter()->select()
		->from(array('oab'=>$table))
		->join(array('o'=>$table2), $cond2)
		->join(array('oas'=>$table3), $cond3)
		->join(array('cg'=>$table4), $cond4)
		->reset('columns')
		->columns($this->getColumns())
		->where($where)
		->limit($limit, $offset)
		->order('o.entity_id');
	return $this->_getReadAdapter()->fetchAll($select);
}

}
?>