<?php

namespace YandexMoney\Request;


use PHPUnit_Framework_TestCase;
use YandexMoney\Presets\ApiKey;

class ProcessPaymentByCardRequestTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ProcessPaymentByCardRequest
     */
    protected $processPaymentByCardRequest;

    public function setUp()
    {
        $this->processPaymentByCardRequest = new ProcessPaymentByCardRequest();
        $this->processPaymentByCardRequest->setRequestId("request_id");
        $this->processPaymentByCardRequest->setExtAuthSuccessUri("http://somewhere.com/success");
        $this->processPaymentByCardRequest->setExtAuthFailUri("http://somewhere.com/fail");
        $this->processPaymentByCardRequest->setMoneySource('1222233334444555');
        $this->processPaymentByCardRequest->setCsc("000");

    }

    public function testProcessPaymentByCardRequest()
    {
        $this->assertNotNull($this->processPaymentByCardRequest);
    }

    public function testGetRequestId()
    {
        $this->assertEquals("request_id", $this->processPaymentByCardRequest->getRequestId());
    }

    public function testGetExtAuthSuccessUri()
    {
        $this->assertEquals(
            "http://somewhere.com/success",
            $this->processPaymentByCardRequest->getExtAuthSuccessUri()
        );
    }

    public function testGetAuthFailUri()
    {
        $this->assertEquals("http://somewhere.com/fail", $this->processPaymentByCardRequest->getExtAuthFailUri());
    }

    public function testGetCsc()
    {
        $this->assertEquals("000", $this->processPaymentByCardRequest->getCsc());
    }

    public function testGetMoneySource()
    {
        $this->assertEquals('1222233334444555', $this->processPaymentByCardRequest->getMoneySource());
    }

    public function tearDown()
    {
        $this->processPaymentByCardRequest = null;
    }


    /**
     * @param mixed $csc
     */
    public function setCsc($csc)
    {
        $this->paramsArray[ApiKey::CSC] = $csc;
    }

    /**
     * @return mixed
     */
    public function getCsc()
    {
        return $this->checkAndReturn(ApiKey::CSC);
    }

    /**
     * @param mixed $extAuthSuccessUri
     */
    public function setExtAuthSuccessUri($extAuthSuccessUri)
    {
        $this->paramsArray[ApiKey::EXT_AUTH_SUCCESS_URI] = $extAuthSuccessUri;
    }

    /**
     * @return mixed
     */
    public function getExtAuthSuccessUri()
    {
        return $this->checkAndReturn(ApiKey::EXT_AUTH_SUCCESS_URI);
    }

    /**
     * @param mixed $extFailUri
     */
    public function setExtAuthFailUri($extFailUri)
    {
        $this->paramsArray[ApiKey::EXT_AUTH_FAIL_URI] = $extFailUri;
    }

    /**
     * @return mixed
     */
    public function getExtAuthFailUri()
    {
        return $this->checkAndReturn(ApiKey::EXT_AUTH_FAIL_URI);
    }

    /**
     * @param mixed $moneySource
     */
    public function setMoneySource($moneySource)
    {
        $this->paramsArray[ApiKey::MONEY_SOURCE] = $moneySource;
    }

    /**
     * @return mixed
     */
    public function getMoneySource()
    {
        return $this->checkAndReturn(ApiKey::MONEY_SOURCE);
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId)
    {
        $this->paramsArray[ApiKey::REQUEST_ID] = $requestId;
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->checkAndReturn(ApiKey::REQUEST_ID);
    }

} 