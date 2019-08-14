<?php
class Minubo_Interface_Model_Mysql4_Customers extends Mage_Core_Model_Mysql4_Abstract
{
public function _construct() {
		$this->_init('minubo_interface/customers', 'entity_id');
}

protected function getColumns() {
	$r = array('entity_id','entity_type_id','attribute_set_id','website_id','MD5(email) AS Customer_HashCode',
		'group_id','increment_id','store_id','created_at','updated_at','is_active','disable_auto_group_change',
		'cg.customer_group_id','cg.customer_group_code','cg.tax_class_id');
	$showemail = Mage::getStoreConfig('minubo_interface/settings/showemail',Mage::app()->getStore());
	if($showemail) 
		$field1 = 'email';
	else
		$field1 = "'' as email";        
        return array_merge($r, array($field1));
}

public function loadByField($field,$value){
	$table = $this->getMainTable();
	$table2 = $this->getTable('customer_group');
	$cond2 = $this->_getReadAdapter()->quoteInto('c.group_id = cg.customer_group_id','');
	$where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
	$select = $this->_getReadAdapter()->select()->from(array('c'=>$table))
	->join(array('cg'=>$table2), $cond2)
	->reset('columns')
	->columns($this->getColumns())
	->where($where);
	$id = $this->_getReadAdapter()->fetchOne($select);
	return $id;
}

public function loadAllByStoreId($store_id){
	$table = $this->getMainTable();
	$table2 = $this->getTable('customer_group');
	$cond2 = $this->_getReadAdapter()->quoteInto('c.group_id = cg.customer_group_id','');
	$where = $this->_getReadAdapter()->quoteInto("c.store_id = ?", $store_id);
	$select = $this->_getReadAdapter()->select()->from(array('c'=>$table))
	->join(array('cg'=>$table2), $cond2)
	->reset('columns')
	->columns($this->getColumns())
	->where($where)
	->order('c.entity_id');
	return $this->_getReadAdapter()->fetchAll($select);
}

public function loadLimitedByStoreId($limit, $offset, $store_id){
	$table = $this->getMainTable();
	$table2 = $this->getTable('customer_group');
	$cond2 = $this->_getReadAdapter()->quoteInto('c.group_id = cg.customer_group_id','');
	$where = $this->_getReadAdapter()->quoteInto("c.store_id = ?", $store_id);
	$select = $this->_getReadAdapter()->select()->from(array('c'=>$table))
	->join(array('cg'=>$table2), $cond2)
	->reset('columns')
	->columns($this->getColumns())
	->where($where)
	->limit($limit, $offset)
	->order('c.entity_id');
	return $this->_getReadAdapter()->fetchAll($select);
}

}
?>