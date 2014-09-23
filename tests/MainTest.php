<?php 

require_once __DIR__ . "/constants.php";
require_once __DIR__ . "/../lib/api.php";

abstract class BaseTest extends PHPUnit_Framework_TestCase {
    public $api;

    function setUp() {
        $this->api = new \YandexMoney\API(ACCESS_TOKEN);
    }

}

class TokenUrlTest extends PHPUnit_Framework_TestCase {
    function testTokenBuilder() {
        $url = \YandexMoney\API::buildObtainTokenUrl(
            CLIENT_ID,
            "http://localhost:8000",
            CLIENT_SECRET,
            array("account-info operation-history operation-details")
            );
        // TODO: check url
        // var_dump($url);
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
        // var_dump($result);
        $this->assertObjectHasAttribute("operations", $result);
    }
    function testOperationDetailsError() {
        $result = $this->api->operationDetails("12345");
        $this->assertEquals($result->error, "illegal_param_operation_id");
    }
}

class PaymentTest extends BaseTest {
    function setUp() {
        $this->options = array(
            "pattern_id" => "p2p",
            "to" => "410011161616877",
            "amount_due" => "0.02",
            "comment" => "test payment comment from yandex-money-php",
            "message" => "test payment message from yandex-money-php",
            "label" => "testPayment",
            "test_payment" => true,
            "test_result" => "success"
        );
        parent::setUp();
    }
    function makeRequestPayment() {
        $response = $this->api->requestPayment($this->options);
        $this->assertEquals($response->status, "success");
        return $response;
    }
    function testRequestPaymant() {
        $this->makeRequestPayment();
    }
    function testProcessPayment() {
        $requestResult = $this->makeRequestPayment();
        $processResult = $this->api->processPayment(array(
            "request_id" => $requestResult->request_id,
            "test_payment" => true,
            "test_result" => "success"
        ));
        $this->assertEquals($processResult->status, "success");
    }
}