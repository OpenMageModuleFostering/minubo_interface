<?php
class Minubo_Interface_Model_Mysql4_Customeraddresses extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/customeraddresses', 'entity_id');
    }

    protected function getColumns() {
    		$r = array('oa.entity_id','oa.customer_address_id','oa.region_id','oa.region','oa.postcode','oa.city',
										'oa.country_id','oa.address_type','MD5(oa.email) AS Customer_HashCode','c.group_id',
										'cg.customer_group_code','o.store_id');
				$showemail = Mage::getStoreConfig('minubo_interface/settings/showemail',Mage::app()->getStore());
				if($showemail) 
					$field1 = 'email';
				else
					$field1 = "'' as email";        
        return array_merge($r, array($field1));
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $table2 = $this->getTable('customer_address_entity');
        $cond2 = $this->_getReadAdapter()->quoteInto('oa.customer_address_id = ca.entity_id','');
        $table3 = $this->getTable('customer_entity');
        $cond3 = $this->_getReadAdapter()->quoteInto('ca.parent_id = c.entity_id','');
        $table4 = $this->getTable('customer_group');
        $cond4 = $this->_getReadAdapter()->quoteInto('c.group_id = cg.customer_group_id','');
        $table5 = $this->getTable('sales_flat_order');
        $cond5 = $this->_getReadAdapter()->quoteInto('oa.parent_id = o.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from(array('oa'=>$table))
																										->join(array('ca'=>$table2), $cond2)
																										->join(array('c'=>$table3), $cond3)
																										->join(array('cg'=>$table4), $cond4)
																										->join(array('o'=>$table5), $cond5)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }

    public function loadAllByStoreId($store_id){
        $table = $this->getMainTable();
        $table2 = $this->getTable('customer_address_entity');
        $cond2 = $this->_getReadAdapter()->quoteInto('oa.customer_address_id = ca.entity_id','');
        $table3 = $this->getTable('customer_entity');
        $cond3 = $this->_getReadAdapter()->quoteInto('ca.parent_id = c.entity_id','');
        $table4 = $this->getTable('customer_group');
        $cond4 = $this->_getReadAdapter()->quoteInto('c.group_id = cg.customer_group_id','');
        $table5 = $this->getTable('sales_flat_order');
        $cond5 = $this->_getReadAdapter()->quoteInto('oa.parent_id = o.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("c.store_id = ?", $store_id);
				$select = $this->_getReadAdapter()->select()->from(array('oa'=>$table))
																										->join(array('ca'=>$table2), $cond2)
																										->join(array('c'=>$table3), $cond3)
																										->join(array('cg'=>$table4), $cond4)
																										->join(array('o'=>$table5), $cond5)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->order('entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimitedByStoreId($limit, $offset, $store_id){
        $table = $this->getMainTable();
        $table2 = $this->getTable('customer_address_entity');
        $cond2 = $this->_getReadAdapter()->quoteInto('oa.customer_address_id = ca.entity_id','');
        $table3 = $this->getTable('customer_entity');
        $cond3 = $this->_getReadAdapter()->quoteInto('ca.parent_id = c.entity_id','');
        $table4 = $this->getTable('customer_group');
        $cond4 = $this->_getReadAdapter()->quoteInto('c.group_id = cg.customer_group_id','');
        $table5 = $this->getTable('sales_flat_order');
        $cond5 = $this->_getReadAdapter()->quoteInto('oa.parent_id = o.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("c.store_id = ?", $store_id);
				$select = $this->_getReadAdapter()->select()->from(array('oa'=>$table))
																										->join(array('ca'=>$table2), $cond2)
																										->join(array('c'=>$table3), $cond3)
																										->join(array('cg'=>$table4), $cond4)
																										->join(array('o'=>$table5), $cond5)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->limit($limit, $offset)
        																						->order('entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>