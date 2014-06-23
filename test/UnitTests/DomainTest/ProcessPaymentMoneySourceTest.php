<?php
use YandexMoney\Response\ProcessPaymentResponse;

class ProcessPaymentMoneySourceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ProcessPaymentResponse
     */
    protected $processPaymentMoneySource;

    public function setUp()
    {
        $this->processPaymentMoneySource = new ProcessPaymentResponse(array(
            'status' => "success",
            'error' => "error",
            'payment_id' => "1244440999",
            'balance' => 644.55,
            'invoice_id' => "2344577746",
            'payer' => "123124ger",
            'payee' => "4325234500",
            'credit_amount' => 100.00,
            'account_unblock_uri' => "http://example.com/unblock",
            'hold_for_pickup_link' => "http://example.com/hold/for/pickup/link",
            'acs_uri' => "http://example.com/acs/uri",
            'acs_params' => array(),
            'next_retry' => 2,
            'digital_goods' => array()
        ));
    }

    public function testProcessPaymentResponse()
    {
        $this->assertNotNull($this->processPaymentMoneySource);
    }

    public function testGetStatus()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getStatus());
        $this->assertEquals("success", $this->processPaymentMoneySource->getStatus());
    }

    public function testGetError()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getError());
        $this->assertEquals("error", $this->processPaymentMoneySource->getError());
    }

    public function testGetPaymentId()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getPaymentId());
        $this->assertEquals("1244440999", $this->processPaymentMoneySource->getPaymentId());
    }

    public function testGetBalance()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getBalance());
        $this->assertEquals(644.55, $this->processPaymentMoneySource->getBalance());
    }

    public function testGetInvoiceId()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getInvoiceId());
        $this->assertEquals("2344577746", $this->processPaymentMoneySource->getInvoiceId());
    }

    public function testGetPayer()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getPayer());
        $this->assertEquals("123124ger", $this->processPaymentMoneySource->getPayer());
    }

    public function testGetPayee()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getPayee());
        $this->assertEquals("4325234500", $this->processPaymentMoneySource->getPayee());
    }

    public function testGetCreditAmount()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getCreditAmount());
        $this->assertEquals(100.00, $this->processPaymentMoneySource->getCreditAmount());
    }

    public function testGetAccountUnblockUri()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getAccountUnblockUri());
        $this->assertEquals("http://example.com/unblock", $this->processPaymentMoneySource->getAccountUnblockUri());
    }

    public function testGetHoldForPickupLink()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getHoldForPickupLink());
        $this->assertEquals(
            "http://example.com/hold/for/pickup/link",
            $this->processPaymentMoneySource->getHoldForPickupLink()
        );
    }

    public function testGetAcsUri()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getAcsUri());
        $this->assertEquals("http://example.com/acs/uri", $this->processPaymentMoneySource->getAcsUri());
    }

    public function testGetAcsParams()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getAcsParams());
        $this->assertTrue(is_array($this->processPaymentMoneySource->getAcsParams()));
    }

    public function testGetNextRetry()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getNextRetry());
        $this->assertEquals(2, $this->processPaymentMoneySource->getNextRetry());
    }

    public function testGetDigitalGoods()
    {
        $this->assertNotNull($this->processPaymentMoneySource->getDigitalGoods());
        $this->assertTrue(is_array($this->processPaymentMoneySource->getDigitalGoods()));
    }


    public function tearDown()
    {
        $this->processPaymentMoneySource = null;
    }

} 