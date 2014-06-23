<?php
use YandexMoney\Response\ExternalPaymentResponse;

class ExternalPaymentResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ExternalPaymentResponse
     */
    protected $externalPaymentResponse;

    public function setUp()
    {
        $this->externalPaymentResponse = new ExternalPaymentResponse(array(
            ExternalPaymentResponse::TITLE => "Test title"
        ));
    }

    public function testExternalPaymentResponse()
    {
        $this->assertNotNull($this->externalPaymentResponse);
    }

    public function testGetTitle()
    {
        $this->assertNotNull($this->externalPaymentResponse->getTitle());
        $this->assertEquals("Test title", $this->externalPaymentResponse->getTitle());
    }

    public function tearDown()
    {
        $this->externalPaymentResponse = null;
    }

} 