<?php
class Minubo_Interface_Model_Options_Version
{

	public function toOptionArray()
    {
        return (string) Mage::getConfig()->getNode()->modules->Minubo_Interface->version;
    }

}