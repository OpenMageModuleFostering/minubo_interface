<?php
class Minubo_Interface_Model_Options_Options
{

	public function toOptionArray()
    {
        return array(
            array('value' => 'Standard', 'label'=>Mage::helper('adminhtml')->__('Standard'))
        );
    }

}