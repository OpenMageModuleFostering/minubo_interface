<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$begintime = $time;
?>
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
// hostname
$host= '127.0.0.1';
// if store in /magento change /shop, if store in root remove /shop
$client= new SoapClient('http://'.$host.'/magento/index.php/api/soap/?wsdl');

// Can be added in Magento-Admin -> Web Services with role set to admin
$apiuser= 'soap';
// API key is password
$apikey = '******';
$sess_id= $client->login($apiuser, $apikey);
echo "<html>";
echo "<head>";
echo "<LINK REL=StyleSheet HREF=\"style.css\" TYPE=\"text/css\" MEDIA=screen>";
echo "</head>";
echo "<body>";

$result= $client->call($sess_id, 'sales_order.list',  array(array('status'=>array('='=>'Pending'))));
echo '<pre>';
print_r($result);
echo '<pre>';


?>
<?php
// Let's see how long this took?
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$endtime = $time;
$totaltime = ($endtime - $begintime);
echo '<br /><br /><em>This Magento SOAP API script took ' .$totaltime. ' seconds, precisely.</em>';
// ...and close the HTML document
echo "</body>";
echo "</html>";
?>