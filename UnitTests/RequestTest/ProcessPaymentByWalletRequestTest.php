<?php

namespace YandexMoney\Request;


class ProcessPaymentByWalletRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ProcessPaymentByWalletRequest
     */
    protected $processPaymentByWalletRequest;

    public function setUp()
    {
        $this->processPaymentByWalletRequest = new ProcessPaymentByWalletRequest();
        $this->processPaymentByWalletRequest->setRequestId("REQUEST");
    }

    public function testProcessPaymentByWalletRequest()
    {
        $this->assertNotNull($this->processPaymentByWalletRequest);
    }

    public function testGetRequestId()
    {
        $this->assertEquals("REQUEST", $this->processPaymentByWalletRequest->getRequestId());
    }

    public function tearDown()
    {

    }
} 