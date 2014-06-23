<?php
use YandexMoney\Response\ExternalPaymentResponse;

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/1/14
 * Time: 8:54 PM
 */
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