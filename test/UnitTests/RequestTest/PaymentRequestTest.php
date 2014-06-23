<?php

namespace YandexMoney\Request;


use YandexMoney\Presets\ApiKey;

class PaymentRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PaymentRequest
     */
    protected $paymentRequest;

    public function setUp()
    {
        $this->paymentRequest = new PaymentRequest();

        $this->paymentRequest->setTo("410011161616877");
        $this->paymentRequest->setAmount("0.05");
        $this->paymentRequest->setMessage("Message");
        $this->paymentRequest->setExpirePeriod(30);
        $this->paymentRequest->setCodepro(true);

    }

    public function testPaymentRequest()
    {
        $this->assertNotNull($this->paymentRequest);
    }

    public function testGetTo()
    {
        $this->assertEquals("410011161616877", $this->paymentRequest->getTo());
    }

    public function testGetAmount()
    {
        $this->assertEquals("0.05", $this->paymentRequest->getAmount());
    }

    public function testGetMessage()
    {
        $this->assertEquals("Message", $this->paymentRequest->getMessage());
    }

    public function testGetExpirePeriod()
    {
        $this->assertEquals(30, $this->paymentRequest->getExpirePeriod());
    }

    public function testGetCodePro()
    {
        $this->assertTrue($this->paymentRequest->getCodepro());
    }

    public function testIsAmoutUsed()
    {
        $this->assertTrue($this->paymentRequest->isAmountUsed());
    }

    public function testIsAmountDueUsed()
    {
        $this->assertFalse($this->paymentRequest->isAmountDueUsed());
    }

    public function tearDown()
    {
        $this->paymentRequest = null;
    }


} 