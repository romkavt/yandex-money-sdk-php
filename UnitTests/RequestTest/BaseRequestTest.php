<?php

namespace YandexMoney\Request;


use YandexMoney\Presets\ApiKey;

class BaseRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BaseRequest
     */
    protected $baseRequest;

    public function setUp()
    {
        $this->baseRequest = new BaseRequest();
        $this->baseRequest->setTestPayment(true);
        $this->baseRequest->setTestCard('available');
        $this->baseRequest->setTestResult('success');
    }

    public function testBaseRequest()
    {
        $this->assertNotNull($this->baseRequest);
    }

    public function testGetDefinedParams()
    {
        $this->assertTrue(is_array($this->baseRequest->getDefinedParams()));
    }

    public function testGetTestCard()
    {
        $this->assertEquals('available', $this->baseRequest->getTestCard());
    }

    public function testGetTestPayment()
    {
        $this->assertTrue($this->baseRequest->getTestPayment());
    }

    public function testGetTestResult()
    {
        $this->assertEquals('success', $this->baseRequest->getTestResult());
    }

    public function tearDown()
    {
        $this->baseRequest = null;
    }

} 