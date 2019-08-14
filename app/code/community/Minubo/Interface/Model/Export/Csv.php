<?php
/**
 * Magento Minubo Interface Export Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Minubo
 * @package    Minubo_Interface
 * @copyright  Copyright (c) 2013 Minubo (http://www.minubo.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Sven Rothe <sven@minubo.com>
 * */

class Minubo_Interface_Model_Export_Csv extends Minubo_Interface_Model_Export_Abstractcsv

{
    const ENCLOSURE = '"';
    const DELIMITER = ';';

    public function exportCountries($rows, $filename, $type, $pdata = '')
    {
        $fileName = $filename.'_export_'.date("Ymd_His").'.csv';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $colTitles = array_flip(array('country_id','country_name','country_iso2','country_Iso3'));
        $this->writeHeadRow($fp, $type, $pdata, $colTitles);
        foreach ($rows as $row) {
        	$this->writeCountry($row, $fp, $type);
        }

        fclose($fp);

        return $fileName;
    }

    public function exportTable($rows, $filename, $type, $colTitles = Array(), $skipCols = Array(), $renameCols = Array())
    {
        $fileName = $filename.'_export_'.date("Ymd_His").'.csv';
        $fp = fopen(Mage::getBaseDir('export').'/'.$fileName, 'w');

        $this->writeHeadRow($fp, $type, '', $colTitles, $skipCols, $renameCols);
        foreach ($rows as $row) {
        	// fputcsv wird nicht mehr verwendet um LF entfernen zu können
        	// if (count($skipCols)==0) {
	        // 	fputcsv($fp, $row, self::DELIMITER, self::ENCLOSURE);
	        // } else {
	        	// mit bereinigung von LF, CRLF, "
	        	$this->writeCollection($row, $fp, $type, $skipCols);
	        // }
        }

        fclose($fp);

        return $fileName;
    }

    /**
	 * Writes the head row with the column names in the csv file.
	 *
	 * @param $fp The file handle of the csv file
     * @param $group
     * @param $pdata
     * @param $cols
     * @param $skipCols
     * @param $renameCols
     */
    protected function writeHeadRow($fp, $group, $pdata='', $cols=array(), $skipCols=array(), $renameCols=array())
   {
        $r = array();
        if($cols) {
            foreach ($cols as $col => $val) if(!in_array($col, $skipCols)) { if(array_key_exists($col, $renameCols)) $r[] = $renameCols[$col]; else $r[] = $col; }
        }
        fputcsv($fp, $r, self::DELIMITER, self::ENCLOSURE);
    }

    protected function writeCollection($entries, $fp, $group, $skipCols=array())
    {
        $data = array();
        foreach ($entries as $col => $value) {
      	    if(count($skipCols)>0) {
			    if(!in_array($col, $skipCols)) $data[$col] = str_replace(chr(10),' ',str_replace(chr(13).chr(10),' ',str_replace('"',"'",$value)));
            } else {
                $data[$col] = str_replace(chr(10),' ',str_replace(chr(13).chr(10),' ',str_replace('"',"'",$value)));
            }
        }
        fputcsv($fp, $data, self::DELIMITER, self::ENCLOSURE);
    }

    protected function writeCountry($country, $fp, $group)
    {
    	$common = array(
            $country->getCountryId(),
            $country->getName(),
            $country->getIso2Code(),
            $country->getIso3Code());
     	fputcsv($fp, $common, self::DELIMITER, self::ENCLOSURE);
    }

}

?>