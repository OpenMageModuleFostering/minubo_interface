<?php
class Minubo_Interface_Model_Mysql4_Productattributes extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
	$this->_init('minubo_interface/productattributes', 'entity_id');
    }

    /*
	SELECT *
	FROM `catalog_product_entity` e
	inner join eav_attribute eav on e.entity_type_id = eav.entity_type_id
	inner join catalog_product_entity_varchar var on eav.attribute_id = var.attribute_id AND e.entity_id = var.entity_id
	inner join eav_attribute_set eas on e.attribute_set_id = eas.attribute_set_id AND e.entity_id = var.entity_id
	where e.entity_id > 0 AND  eav.attribute_code in ('name','color')
     */

    protected function getColumns() {
    		return array('e.attribute_set_id','eas.attribute_set_name','eav.attribute_id','eav.attribute_code','eav.backend_type','eav.is_required','var.value','var.value_id','e.entity_id');
    }

    protected function getAttributes() {
	$fields = str_replace(' ','',Mage::getStoreConfig('minubo_interface/settings/attributefields',Mage::app()->getStore()));
	$f = (strlen($fields)>0 ? ",'".str_replace(",","','",$fields)."'" : '');
	return "'name'".$f;
    }

    public function loadByField($field,$value){
	$table = $this->getMainTable();
	$table2 = $this->getTable('eav_attribute');
	$cond2 = $this->_getReadAdapter()->quoteInto('e.entity_type_id = eav.entity_type_id AND ','').$this->_getReadAdapter()->quoteInto('eav.backend_type = "varchar"', '');
	$table3 = $this->getTable('catalog_product_entity_varchar');
	$cond3 = $this->_getReadAdapter()->quoteInto('eav.attribute_id = var.attribute_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
	$table4 = $this->getTable('eav_attribute_set');
	$cond4 = $this->_getReadAdapter()->quoteInto('e.attribute_set_id = eas.attribute_set_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
	$where = $this->_getReadAdapter()->quoteInto("$field = ? AND eav.attribute_code in (".$this->getAttributes().")", $value);
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
	$where = $this->_getReadAdapter()->quoteInto("e.entity_id > ? AND eav.attribute_code in (".$this->getAttributes().")", 0);
				$select = $this->_getReadAdapter()->select()->from(array('e'=>$table))
					->join(array('eav'=>$table2), $cond2)
					->join(array('var'=>$table3), $cond3)
					->join(array('eas'=>$table4), $cond4)
					->reset('columns')
					->columns($this->getColumns())
					->where($where)
					->order('e.entity_id');
	return $this->_getReadAdapter()->fetchAll($select);
    }

    public function loadLimited($limit, $offset){
	$table = $this->getMainTable();
	$table2 = $this->getTable('eav_attribute');
	$cond2 = $this->_getReadAdapter()->quoteInto('e.entity_type_id = eav.entity_type_id','');
	$table3 = $this->getTable('catalog_product_entity_varchar');
	$cond3 = $this->_getReadAdapter()->quoteInto('eav.attribute_id = var.attribute_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
	$table4 = $this->getTable('eav_attribute_set');
	$cond4 = $this->_getReadAdapter()->quoteInto('e.attribute_set_id = eas.attribute_set_id AND ','').$this->_getReadAdapter()->quoteInto('e.entity_id = var.entity_id', '');
	$where = $this->_getReadAdapter()->quoteInto("e.entity_id > ? AND eav.attribute_code in (".$this->getAttributes().")", 0);
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