<?php
class Minubo_Interface_Model_Mysql4_Invoiceitems extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/invoiceitems', 'entity_id');
    }

    protected function getColumns() {
    		return array('entity_id','parent_id','base_price','base_weee_tax_row_disposition','weee_tax_applied_row_amount','base_weee_tax_applied_amount','tax_amount','base_row_total','discount_amount','row_total','weee_tax_row_disposition','base_discount_amount','base_weee_tax_disposition','price_incl_tax','weee_tax_applied_amount','base_tax_amount','base_price_incl_tax','qty','weee_tax_disposition','base_cost','base_weee_tax_applied_row_amnt','price','base_row_total_incl_tax','row_total_incl_tax','product_id','order_item_id','additional_data','description','weee_tax_applied','sku','name','hidden_tax_amount','base_hidden_tax_amount');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()->from($table)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where);
        $id = $this->_getReadAdapter()->fetchOne($sql);
        return $id;
    }

    public function loadAll(){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
				$select = $this->_getReadAdapter()->select()->from($table)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)->order('entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimited($limit, $offset){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
				$select = $this->_getReadAdapter()->select()->from($table)
																										->reset('columns')
																										->columns($this->getColumns())
        																						->where($where)
        																						->limit($limit, $offset)
        																						->order('entity_id');
				return $this->_getReadAdapter()->fetchAll($select);
    }

}
?>