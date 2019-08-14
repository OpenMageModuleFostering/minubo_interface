<?php
class Minubo_Interface_Model_Mysql4_Invoiceitems extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/invoiceitems', 'entity_id');
    }

    protected function getColumns() {
    		return array('ip.entity_id','ip.parent_id','ip.base_price','ip.base_weee_tax_row_disposition','ip.weee_tax_applied_row_amount','ip.base_weee_tax_applied_amount','ip.tax_amount','ip.base_row_total','ip.discount_amount','ip.row_total','ip.weee_tax_row_disposition','ip.base_discount_amount','ip.base_weee_tax_disposition','ip.price_incl_tax','ip.weee_tax_applied_amount','ip.base_tax_amount','ip.base_price_incl_tax','ip.qty','ip.weee_tax_disposition','ip.base_cost','ip.base_weee_tax_applied_row_amnt','ip.price','ip.base_row_total_incl_tax','ip.row_total_incl_tax','ip.product_id','ip.order_item_id','ip.additional_data','ip.description','ip.weee_tax_applied','ip.sku','ip.name','ip.hidden_tax_amount','ip.base_hidden_tax_amount','i.store_id');
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
        $tableHeader = $this->getTable('invoices');
        $condHeader = $this->_getReadAdapter()->quoteInto('ip.parent_id = i.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("i.store_id = ?", $store_id);
				$select = $this->_getReadAdapter()->select()->from(array('ip'=>$table))
                                        						->join(array('i'=>$tableHeader), $condHeader)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->order('ip.entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimitedByStoreId($limit, $offset, $store_id){
        $table = $this->getMainTable();
        $tableHeader = $this->getTable('invoices');
        $condHeader = $this->_getReadAdapter()->quoteInto('ip.parent_id = i.entity_id','');
        $where = $this->_getReadAdapter()->quoteInto("i.store_id = ?", $store_id);
				$select = $this->_getReadAdapter()->select()->from(array('ip'=>$table))
                                       							->join(array('i'=>$tableHeader), $condHeader)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->limit($limit, $offset)
        																						->order('ip.entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>