<?php 

require_once "tests/constants.php";
require_once "lib/api.php";

abstract class BaseTest extends PHPUnit_Framework_TestCase {
    public $api;

    function setUp() {
        $this->api = new \YandexMoney\API;
        $this->api->access_token = ACCESS_TOKEN;
    }

}

class AccountTest extends BaseTest {
    function testAccountInfo() {
        $result = $this->api->accountInfo();
        $this->assertObjectHasAttribute("account", $result);
        $this->assertObjectHasAttribute("currency", $result);
    }
    function testOperationHistory() {
        $options = array(
            "type" => "deposition",
            "records" => 1,
        );
        $result = $this->api->operationHistory($options);
        $this->assertObjectHasAttribute("operations", $result);
    }
}

class PaymentTest extends BaseTest {
    function testRequestPaymant() {
        $options = array(
            "pattern_id" => "phone-topup",
            "phone-number" => "79233630564",
            "amount" => 1,
            "test_payment" => true,
            "test_result" => "success"
        );
        $response = $this->api->requestPayment($options);
        $this->assertObjectHasAttribute("status", $response);
    }
}