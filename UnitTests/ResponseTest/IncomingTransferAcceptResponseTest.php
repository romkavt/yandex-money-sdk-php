<?php
use YandexMoney\Response\IncomingTransferAcceptResponse;

class IncomingTransferAcceptResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var IncomingTransferAcceptResponse
     */
    public $incomingTransferAcceptResponse;

    public function setUp()
    {
        $this->incomingTransferAcceptResponse = new IncomingTransferAcceptResponse(array(
            'status' => "success",
            'error' => "illegal_param_protection_code",
            'incoming-transfer-accept' => 2,
            'protection_code_attempts_available' => 2,
            'ext_action_uri' => "http://www.example.com"
        ));
    }

    public function testIncomingTransferAcceptResponse()
    {
        $this->assertNotNull($this->incomingTransferAcceptResponse);
    }

    public function testGetStatus()
    {
        $this->assertNotNull($this->incomingTransferAcceptResponse->getStatus());
        $this->assertEquals("success", $this->incomingTransferAcceptResponse->getStatus());
    }

    public function testGetIncomingTransferAccept()
    {
        $this->assertNotNull($this->incomingTransferAcceptResponse->getIncomingTransferAccept());
        $this->assertEquals(2, $this->incomingTransferAcceptResponse->getIncomingTransferAccept());
    }

    public function testGetExtActionUri()
    {
        $this->assertNotNull($this->incomingTransferAcceptResponse->getExtActionUri());
        $this->assertEquals("http://www.example.com", $this->incomingTransferAcceptResponse->getExtActionUri());
    }

    public function testGetError()
    {
        $this->assertNotNull($this->incomingTransferAcceptResponse->getError());
        $this->assertEquals("illegal_param_protection_code", $this->incomingTransferAcceptResponse->getError());
    }

    public function isSuccess()
    {
        $this->assertNotNull($this->incomingTransferAcceptResponse->isSuccess());
        $this->assertFalse($this->incomingTransferAcceptResponse->isSuccess());
    }

    public function tearDown()
    {
        $this->incomingTransferAcceptResponse = null;
    }

} 