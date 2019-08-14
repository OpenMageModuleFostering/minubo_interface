<?php
class Minubo_Interface_Model_Mysql4_Orderaddresses extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/orderaddresses', 'entity_id');
    }

    protected function getColumns() {
    		return array('oa.entity_id','oa.customer_address_id','oa.region_id','oa.region','oa.postcode','oa.city','oa.country_id','oa.address_type','MD5(oa.email) AS Customer_HashCode','o.store_id');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $table5 = $this->getTable('sales_flat_order');
        $cond5 = $this->_getReadAdapter()->quoteInto('oa.parent_id = o.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from(array('oa'=>$table))
																										->join(array('o'=>$table5), $cond5)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where);
        $id = $this->_getReadAdapter()->fetchOne($sql);
        return $id;
    }

    public function loadAll(){
        $table = $this->getMainTable();
        $table5 = $this->getTable('sales_flat_order');
        $cond5 = $this->_getReadAdapter()->quoteInto('oa.parent_id = o.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("oa.entity_id > ?", 0);
				$select = $this->_getReadAdapter()->select()->from(array('oa'=>$table))
																										->join(array('o'=>$table5), $cond5)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->order('MD5(oa.email), entity_id desc');
				return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimited($limit, $offset){
        $table = $this->getMainTable();
        $table5 = $this->getTable('sales_flat_order');
        $cond5 = $this->_getReadAdapter()->quoteInto('oa.parent_id = o.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("oa.entity_id > ?", 0);
				$select = $this->_getReadAdapter()->select()->from(array('oa'=>$table))
																										->join(array('o'=>$table5), $cond5)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->limit($limit, $offset)
        																						->order('MD5(oa.email), entity_id desc');
				return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>