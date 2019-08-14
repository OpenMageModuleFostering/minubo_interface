<?php
class Minubo_Interface_Block_Adminhtml_Version extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return Mage::getConfig()->getNode()->modules->Minubo_Interface->version;
    }
}
?>