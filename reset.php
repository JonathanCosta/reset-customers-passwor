<?php
error_reporting(E_ALL | E_STRICT);
$mageFilename = 'app/Mage.php';
if (!file_exists($mageFilename)) {
if (is_dir('downloader')) {
header("Location: downloader");
} else {
echo $mageFilename." was not found";
}
exit;
}
require_once $mageFilename;
Varien_Profiler::enable();
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app('default');
$passwordLength = 10;

/*
  Resetar apenas um customer_id:
*/

//$customer_id = 10;
//$customers = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('entity_id', array('eq' => $customer_id));

/*
  Resetar todos :
*/

$customers = Mage::getModel('customer/customer')->getCollection();

/*
 Agora percorrer os clientes e criar as senhas
*/
foreach ($customers as $customer){
    $customer->load($customer->getId());
    $customer->setPassword($customer->generatePassword($passwordLength))->save();
	$line_data = $customer->getEmail(). "\t". $customer->getPassword();
        $line[] = $line_data;
        echo $line_data."\n <br>";
    $customer->sendNewAccountEmail();
}
$content = implode("\n", $line);

// armazenar todas as senhas em um arquivo:
file_put_contents('./accounts.csv', $content);
echo "COMPLETE!";
?>
