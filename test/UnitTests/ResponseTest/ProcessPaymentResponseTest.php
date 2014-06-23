<?php
use YandexMoney\Response\ProcessPaymentResponse;

class ProcessPaymentResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ProcessPaymentResponse
     */
    public $processPaymentResponse;

    public function setUp()
    {

        $this->processPaymentResponse = new ProcessPaymentResponse(array(
            'status' => "success",
            'error' => "contract_not_found",
            'payment_id' => "2ABCDE123456789",
            'balance' => 100.50,
            'invoice_id' => "1233fds232",
            'payer' => "qweq12211",
            'payee' => "asd23r2r",
            'credit_amount' => 120.50,
            'account_unblock_uri' => "http://www.example.com/unblock",
            'hold_for_pickup_link' => "hold_for_pickup_link",
            'acs_uri' => "http://www.example.com/acs/uri",
            'acs_params' => array(
                'MD' => "723613-7431F11492F4F2D0",
                'PaReq' => "eJxVUl1T2zAQ/CsZv8f6tCR7LmLSGiidJjAldMpTR7XVxAN2gmynSX59JeNAebu9O93u7QkuDvXzZG9dW22bWURiHE1sU2zLqlnPoofV1VRFFxpWG2dtfm+L3lkNC9u2Zm0nVTmLVvn9r7v5d/uS/UkYt4b8tjibUiGVxazICMeSSkmtwBmlhYw="
            ),
            'next_retry' => 5,
            'digital_goods' => array(
                'article' => array(
                    'merchantArticleId' => "1234567",
                    'serial' => "EAV-0087182017",
                    'secret' => "87actmdbsv"
                )
            )
        ));
    }

    public function testProcessPaymentResponse()
    {
        $this->assertNotNull($this->processPaymentResponse);
    }

    public function testGetStatus()
    {
        $this->assertNotNull($this->processPaymentResponse->getStatus());
        $this->assertEquals("success", $this->processPaymentResponse->getStatus());
    }

    public function testGetError()
    {
        $this->assertNotNull($this->processPaymentResponse->getError());
        $this->assertEquals("contract_not_found", $this->processPaymentResponse->getError());
    }

    public function testGetPaymentId()
    {
        $this->assertNotNull($this->processPaymentResponse->getPaymentId());
        $this->assertEquals("2ABCDE123456789", $this->processPaymentResponse->getPaymentId());
    }

    public function testGetBalance()
    {
        $this->assertNotNull($this->processPaymentResponse->getBalance());
        $this->assertEquals(100.50, $this->processPaymentResponse->getBalance());
    }

    public function testGetInvoiceId()
    {
        $this->assertNotNull($this->processPaymentResponse->getInvoiceId());
        $this->assertEquals("1233fds232", $this->processPaymentResponse->getInvoiceId());
    }

    public function testGetPayer()
    {
        $this->assertNotNull($this->processPaymentResponse->getPayer());
        $this->assertEquals("qweq12211", $this->processPaymentResponse->getPayer());
    }

    public function testGetPayee()
    {
        $this->assertNotNull($this->processPaymentResponse->getPayee());
        $this->assertEquals("asd23r2r", $this->processPaymentResponse->getPayee());
    }

    public function testGetCreditAmount()
    {
        $this->assertNotNull($this->processPaymentResponse->getCreditAmount());
        $this->assertEquals(120.50, $this->processPaymentResponse->getCreditAmount());
    }

    public function testGetAccountUnblockUri()
    {
        $this->assertNotNull($this->processPaymentResponse->getAccountUnblockUri());
        $this->assertEquals("http://www.example.com/unblock", $this->processPaymentResponse->getAccountUnblockUri());
    }

    public function testGetHoldForPickupLink()
    {
        $this->assertNotNull($this->processPaymentResponse->getHoldForPickupLink());
        $this->assertEquals("hold_for_pickup_link", $this->processPaymentResponse->getHoldForPickupLink());
    }

    public function testGetAcsUri()
    {
        $this->assertNotNull($this->processPaymentResponse->getAcsUri());
        $this->assertEquals("http://www.example.com/acs/uri", $this->processPaymentResponse->getAcsUri());
    }

    public function testGetAcsParams()
    {
        $this->assertNotNull($this->processPaymentResponse->getAcsParams());
        $this->assertTrue(is_array($this->processPaymentResponse->getAcsParams()));
    }

    public function testGetNextRetry()
    {
        $this->assertNotNull($this->processPaymentResponse->getNextRetry());
        $this->assertEquals(5, $this->processPaymentResponse->getNextRetry());
    }

    public function testGetDigitalGoods()
    {
        $this->assertNotNull($this->processPaymentResponse->getDigitalGoods());
        $this->assertTrue(is_array($this->processPaymentResponse->getDigitalGoods()));
    }

    public function tearDown()
    {

    }
} 