<?php
class Minubo_Interface_Model_Mysql4_Creditmemoitems extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/creditmemoitems', 'entity_id');
    }

    protected function getColumns() {
    		return array('cp.entity_id','cp.parent_id','cp.weee_tax_applied_row_amount','cp.base_price','cp.base_weee_tax_row_disposition','cp.tax_amount','cp.base_weee_tax_applied_amount','cp.weee_tax_row_disposition','cp.base_row_total','cp.discount_amount','cp.row_total','cp.weee_tax_applied_amount','cp.base_discount_amount','cp.base_weee_tax_disposition','cp.price_incl_tax','cp.base_tax_amount','cp.weee_tax_disposition','cp.base_price_incl_tax','cp.qty','cp.base_cost','cp.base_weee_tax_applied_row_amnt','cp.price','cp.base_row_total_incl_tax','cp.row_total_incl_tax','cp.product_id','cp.order_item_id','cp.additional_data','cp.description','cp.weee_tax_applied','cp.sku','cp.name','cp.hidden_tax_amount','cp.base_hidden_tax_amount','c.store_id');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from($table)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }

    public function loadAllByStoreId($store_id){
        $table = $this->getMainTable();
        $tableHeader = $this->getTable('creditmemos');
        $condHeader = $this->_getReadAdapter()->quoteInto('cp.parent_id = c.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("c.store_id = ?", $store_id);
				$select = $this->_getReadAdapter()->select()->from(array('cp'=>$table))
                                        						->join(array('c'=>$tableHeader), $condHeader)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->order('cp.entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimitedByStoreId($limit, $offset, $store_id){
        $table = $this->getMainTable();
        $tableHeader = $this->getTable('creditmemos');
        $condHeader = $this->_getReadAdapter()->quoteInto('cp.parent_id = c.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("c.store_id = ?", $store_id);
				$select = $this->_getReadAdapter()->select()->from(array('cp'=>$table))
                                        						->join(array('c'=>$tableHeader), $condHeader)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->limit($limit, $offset)
        																						->order('cp.entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>