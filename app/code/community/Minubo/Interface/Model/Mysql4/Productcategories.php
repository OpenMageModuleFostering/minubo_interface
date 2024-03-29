<?php
class Minubo_Interface_Model_Mysql4_Productcategories extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/productcategories', 'entity_id');
    }

    protected function getColumns() {
    		return array('product_id','category_id','position','store_id','visibility');
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
        $where = $this->_getReadAdapter()->quoteInto("store_id = ?", $store_id);
        $select = $this->_getReadAdapter()->select()->from($table)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->order('category_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimitedByStoreId($limit, $offset, $store_id){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("store_id = ?", $store_id);
        $select = $this->_getReadAdapter()->select()->from($table)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where)
                                        ->limit($limit, $offset)
                                        ->order('category_id');
        return $this->_getReadAdapter()->fetchAll($select);
    }
}
?>