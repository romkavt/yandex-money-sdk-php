<?php

namespace YandexMoney\Request;


use PHPUnit_Framework_TestCase;
use YandexMoney\Presets\ApiKey;

class P2pPaymentRequestTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var P2pPaymentRequest
     */
    protected $p2pPaymentRequest;

    public function setUp()
    {
        $this->p2pPaymentRequest = new P2pPaymentRequest();
        $this->p2pPaymentRequest->setLabel("Label");
        $this->p2pPaymentRequest->setComment("Comment");
    }

    public function testP2pPaymentRequest()
    {
        $this->assertNotNull($this->p2pPaymentRequest);
    }

    public function testGetComment()
    {
        $this->assertEquals("Comment", $this->p2pPaymentRequest->getComment());
    }

    public function testGetLabel()
    {
        $this->assertEquals("Label", $this->p2pPaymentRequest->getLabel());
    }

    public function tearDown()
    {
        $this->p2pPaymentRequest = null;
    }

} 