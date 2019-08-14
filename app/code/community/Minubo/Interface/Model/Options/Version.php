<?php
class Minubo_Interface_Model_Options_Options
{

	public function toOptionArray()
    {
        return (string) Mage::getConfig()->getNode()->modules->Minubo_Interface->version;
    }

}