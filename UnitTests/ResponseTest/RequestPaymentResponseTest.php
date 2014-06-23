<?php
use YandexMoney\Response\RequestPaymentResponse;

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/1/14
 * Time: 9:02 PM
 */
class RequestPaymentResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var RequestPaymentResponse
     */
    protected $requestPaymentResponse;

    public function setUp()
    {
        $this->requestPaymentResponse = new RequestPaymentResponse(array(
            'status' => "success",
            'error' => "illegal_params",
            'money_source' => array(
                'allowed' => true
            ),
            'request_id' => "123456789",
            'contract' => "Some text",
            'contract_amount' => 100.50,
            'balance' => 200.30,
            'recipient_account_status' => "anonymous",
            'recipient_account_type' => "professional",
            'protection_code' => "0123",
            'account_unblock_uri' => "http://www.example.com/unblock",
            'ext_action_uri' => "http://www.example.com/ext-action"
        ));
    }

    public function testRequestPaymentResponse()
    {
        $this->assertNotNull($this->requestPaymentResponse);
    }

    public function testGetStatus()
    {
        $this->assertNotNull($this->requestPaymentResponse->getStatus());
        $this->assertEquals("success", $this->requestPaymentResponse->getStatus());
    }

    public function testGetError()
    {
        $this->assertNotNull($this->requestPaymentResponse->getError());
        $this->assertEquals("illegal_params", $this->requestPaymentResponse->getError());
    }

    public function getMoneySource()
    {
        $this->assertNotNull($this->requestPaymentResponse->getMoneySource());
        $this->assertInstanceOf(
            '\Yandex\Domain\ProcessPaymentMoneySource',
            $this->requestPaymentResponse->getMoneySource()
        );
        $this->assertTrue($this->requestPaymentResponse->getMoneySource()->isWalletAllowed());
    }

    public function testGetRequestId()
    {
        $this->assertNotNull($this->requestPaymentResponse->getRequestId());
        $this->assertEquals("123456789", $this->requestPaymentResponse->getRequestId());
    }

    public function testGetContract()
    {
        $this->assertNotNull($this->requestPaymentResponse->getContract());
        $this->assertEquals("Some text", $this->requestPaymentResponse->getContract());
    }

    public function testGetContractAmount()
    {
        $this->assertNotNull($this->requestPaymentResponse->getContractAmount());
        $this->assertEquals(100.50, $this->requestPaymentResponse->getContractAmount());
    }

    public function testGetBalance()
    {
        $this->assertNotNull($this->requestPaymentResponse->getBalance());
        $this->assertEquals(200.30, $this->requestPaymentResponse->getBalance());
    }

    public function testGetRecipientAccountType()
    {
        $this->assertNotNull($this->requestPaymentResponse->getRecipientAccountType());
        $this->assertEquals("professional", $this->requestPaymentResponse->getRecipientAccountType());
    }

    public function testGetRecipientAccountStatus()
    {
        $this->assertNotNull($this->requestPaymentResponse->getRecipientAccountStatus());
        $this->assertEquals("anonymous", $this->requestPaymentResponse->getRecipientAccountStatus());
    }

    public function testGetProtectionCode()
    {
        $this->assertNotNull($this->requestPaymentResponse->getProtectionCode());
        $this->assertEquals("0123", $this->requestPaymentResponse->getProtectionCode());
    }

    public function testGetAccountUnblockUri()
    {
        $this->assertNotNull($this->requestPaymentResponse->getAccountUnblockUri());
        $this->assertEquals("http://www.example.com/unblock", $this->requestPaymentResponse->getAccountUnblockUri());
    }

    public function testGetExtActionUri() {
        $this->assertNotNull($this->requestPaymentResponse->getExtActionUri());
        $this->assertEquals("http://www.example.com/ext-action", $this->requestPaymentResponse->getExtActionUri());
    }

    public function tearDown()
    {
        $this->requestPaymentResponse = null;
    }

} 