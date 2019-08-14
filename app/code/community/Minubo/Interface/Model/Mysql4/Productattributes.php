<?php
class Minubo_Interface_Model_Mysql4_Productattributes extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('minubo_interface/productattributes', 'entity_id');
    }

    protected function getColumns() {
    		return array('e.attribute_set_id','eas.attribute_set_name','eav.attribute_id','eav.attribute_code','eav.backend_type','eav.is_required','var.value','var.value_id');
    }

    public function loadByField($field,$value){
        $table = $this->getMainTable();
        $table2 = $this->getTable('eav_attribute');
        $cond2 = $this->_getReadAdapter()->quoteInto('e.entity_type_id = eav.entity_type_id AND ','').$this->_getReadAdapter()->quoteInto('eav.backend_type = "varchar"', '');
        $table3 = $this->getTable('catalog_product_entity_varchar');
        $cond3 = $this->_getReadAdapter()->quoteInto('eav.attribute_id = var.attribute_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
        $table4 = $this->getTable('eav_attribute_set');
        $cond4 = $this->_getReadAdapter()->quoteInto('e.attribute_set_id = eas.attribute_set_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
        $where = $this->_getReadAdapter()->quoteInto("$field = ? AND eav.attribute_code in ('name','color')", $value);
        $select = $this->_getReadAdapter()->select()->from(array('e'=>$table))
                                        ->join(array('eav'=>$table2), $cond2)
                                        ->join(array('var'=>$table3), $cond3)
                                        ->join(array('eas'=>$table4), $cond4)
                                        ->reset('columns')
                                        ->columns($this->getColumns())
                                        ->where($where);
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }

    public function loadAll(){
    		$table = $this->getMainTable();
        $table2 = $this->getTable('eav_attribute');
        $cond2 = $this->_getReadAdapter()->quoteInto('e.entity_type_id = eav.entity_type_id AND ','').$this->_getReadAdapter()->quoteInto('eav.backend_type = "varchar"', '');
        $table3 = $this->getTable('catalog_product_entity_varchar');
        $cond3 = $this->_getReadAdapter()->quoteInto('eav.attribute_id = var.attribute_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
        $table4 = $this->getTable('eav_attribute_set');
        $cond4 = $this->_getReadAdapter()->quoteInto('e.attribute_set_id = eas.attribute_set_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
        $where = $this->_getReadAdapter()->quoteInto("e.entity_id > ? AND eav.attribute_code in ('name','color')", 0);
				$select = $this->_getReadAdapter()->select()->from(array('e'=>$table))
																										->join(array('eav'=>$table2), $cond2)
																										->join(array('var'=>$table3), $cond3)
																										->join(array('eas'=>$table4), $cond4)
																										->reset('columns')
																										->columns($this->getColumns())
																										->where($where)
																										->order('e.entity_id');
        /*
        echo $select."<br/>";
        
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("category_id > ?", 0);
				// $select = $this->_getReadAdapter()->select()->from($table)->columns(array('entity_id','parent_id','name'))->where($where)->limit(10,5)->order('created_at');
				$select = $this->_getReadAdapter()->select()->from($table)->where($where)->order('category_id');
				*/
				
				return $this->_getReadAdapter()->fetchAll($select);
				
				/*
				SELECT e.entity_id AS product_id, var.value AS product_name
				FROM catalog_product_entity e, eav_attribute eav, catalog_product_entity_varchar var
				WHERE 
				   e.entity_type_id = eav.entity_type_id 
				   AND eav.attribute_code = 'name'
				   AND eav.attribute_id = var.attribute_id
				   AND var.entity_id = e.entity_id
				*/
				
        
    }

    public function loadLimited($limit, $offset){
        $table = $this->getMainTable();
        $table2 = $this->getTable('eav_attribute');
        $cond2 = $this->_getReadAdapter()->quoteInto('e.entity_type_id = eav.entity_type_id','');
        $table3 = $this->getTable('catalog_product_entity_varchar');
        $cond3 = $this->_getReadAdapter()->quoteInto('eav.attribute_id = var.attribute_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
        $table4 = $this->getTable('eav_attribute_set');
        $cond4 = $this->_getReadAdapter()->quoteInto('e.attribute_set_id = eas.attribute_set_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
        $where = $this->_getReadAdapter()->quoteInto("e.entity_id > ? AND eav.attribute_code in ('name','color')", 0);
				$select = $this->_getReadAdapter()->select()->from(array('e'=>$table))->reset('columns')
																										->join(array('eav'=>$table2), $cond2)
																										->join(array('var'=>$table3), $cond3)
																										->join(array('eas'=>$table4), $cond4)
																										->reset('columns')
																										->columns($this->getColumns())
																										->where($where)
																										->limit($limit, $offset)
																										->order('e.entity_id');
				
				return $this->_getReadAdapter()->fetchAll($select);
    }
}
?>