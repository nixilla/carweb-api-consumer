<?php

use Carweb\Cache\TempFileCache;

require_once './vendor/autoload.php';

$strUserName = 'your user name';
$strPassword = 'your password';
$strKey1 = 'your key';
$vrm = 'VRM - Vehicle Registration Mark to be checked';
$vin = 'VIN - Vehicle Identification Number to be checked';

$client = new Buzz\Browser(new Buzz\Client\Curl());
$consumer = new Carweb\Consumer($client, $strUserName, $strPassword, $strKey1, new TempFileCache());

try
{
    $xmlstring1 = $consumer->findByVRM($vrm);
    $xmlstring2 = $consumer->findByVIN($vin);
    $xmlstring3 = $consumer->findByVRM($vin); // throws exception
}
catch (\Carweb\Exception\ApiException $e)
{
    printf("%s\n", $e->getMessage());
}
