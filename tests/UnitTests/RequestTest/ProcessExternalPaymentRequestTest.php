<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/24/14
 * Time: 8:03 PM
 */

namespace YandexMoney\Request;


use PHPUnit_Framework_TestCase;
use YandexMoney\Presets\ApiKey;

class ProcessExternalPaymentRequestTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ProcessExternalPaymentRequest
     */
    protected $processExternalPaymentRequest;

    public function setUp()
    {
        $this->processExternalPaymentRequest = new ProcessExternalPaymentRequest();
        $this->processExternalPaymentRequest->setRequestId("request_id");
        $this->processExternalPaymentRequest->setInstanceId("instance_id");
        $this->processExternalPaymentRequest->setExtAuthSuccessUri("http://somewhere.com/success");
        $this->processExternalPaymentRequest->setExtAuthFailUri("http://somewhere.com/fail");
        $this->processExternalPaymentRequest->setCsc("000");
        $this->processExternalPaymentRequest->setRequestToken("request_token");
        $this->processExternalPaymentRequest->setMoneySourceToken("money_source_token");
    }

    public function testProcessExternalPaymentRequest()
    {
        $this->assertNotNull($this->processExternalPaymentRequest);
    }

    public function testGetRequestId()
    {
        $this->assertEquals("request_id", $this->processExternalPaymentRequest->getRequestId());
    }

    public function testGetInstanceId()
    {
        $this->assertEquals("instance_id", $this->processExternalPaymentRequest->getInstanceId());
    }

    public function testGetExtAuthSuccessUri()
    {
        $this->assertEquals(
            "http://somewhere.com/success",
            $this->processExternalPaymentRequest->getExtAuthSuccessUri()
        );
    }

    public function testGetAuthFailUri()
    {
        $this->assertEquals("http://somewhere.com/fail", $this->processExternalPaymentRequest->getExtAuthFailUri());
    }

    public function testGetCsc()
    {
        $this->assertEquals("000", $this->processExternalPaymentRequest->getCsc());
    }

    public function testGetRequestToken()
    {
        $this->assertEquals("request_token", $this->processExternalPaymentRequest->getRequestToken());
    }

    public function testMoneySourceToken()
    {
        $this->assertEquals("money_source_token", $this->processExternalPaymentRequest->getMoneySourceToken());
    }

    public function tearDown()
    {
        $this->processExternalPaymentRequest = null;
    }

} 