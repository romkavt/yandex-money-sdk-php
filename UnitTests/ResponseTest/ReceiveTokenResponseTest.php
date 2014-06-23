<?php
use YandexMoney\Response\ReceiveTokenResponse;

class ReceiveTokenResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ReceiveTokenResponse
     */
    private $receiveTokenResponse;

    public function setUp()
    {
        $this->receiveTokenResponse = new ReceiveTokenResponse(array(
            'access_token' => "12345TOKEN",
            'error' => "unauthorized_client"
        ));
    }

    public function testReceiveTokenResponse()
    {
        $this->assertNotNull($this->receiveTokenResponse);
    }

    public function testGetAccessToken()
    {
        $this->assertNotNull($this->receiveTokenResponse->getAccessToken());
        $this->assertEquals("12345TOKEN", $this->receiveTokenResponse->getAccessToken());
    }

    public function testGetError()
    {
        $this->assertNotNull($this->receiveTokenResponse->getError());
        $this->assertEquals("unauthorized_client", $this->receiveTokenResponse->getError());
    }

    public function testIsSuccess()
    {
        $this->assertFalse($this->receiveTokenResponse->isSuccess());
    }

    public function tearDown()
    {
        $this->receiveTokenResponse = null;
    }

} 