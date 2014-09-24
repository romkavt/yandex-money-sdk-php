<?php
require_once __DIR__ . "/../lib/external_payment.php";
require_once __DIR__ . "/constants.php";

class ExternalPaymentTest extends PHPUnit_Framework_TestCase {

    function testGetInstanceId() {
        $response = \YandexMoney\ExternalPayment::getInstanceId(CLIENT_ID);
        $this->assertEquals($response->status, "success");
    }

}