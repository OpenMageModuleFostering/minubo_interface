<?php
class Minubo_Interface_Model_Mysql4_Categories extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('minubo_interface/categories', 'entity_id');
    }

    protected function getColumns() {
    		return array('entity_id','parent_id','position','name','level','image','url_key','url_path');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()->select()
                                        ->from($table)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }

    public function loadAllByStoreId($store_id){
        $table = str_replace('_1','_'.$store_id,$this->getMainTable());
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
				$select = $this->_getReadAdapter()->select()->from($table)
										->reset('columns')
										->columns($this->getColumns())
										->where($where)
										->order('created_at');
			return $this->_getReadAdapter()->fetchAll($select);
    }

	public function loadLimitedByStoreId($limit, $offset, $store_id){
        $table = str_replace('_1','_'.$store_id,$this->getMainTable());
        $where = $this->_getReadAdapter()->quoteInto("entity_id > ?", 0);
				$select = $this->_getReadAdapter()->select()->from($table)
										->reset('columns')
										->columns($this->getColumns())
										->where($where)
										->limit($limit, $offset)
										->order('created_at');
				return $this->_getReadAdapter()->fetchAll($select);
    }
}
?>