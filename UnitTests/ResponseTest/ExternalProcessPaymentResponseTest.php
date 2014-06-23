<?php
use YandexMoney\Response\ExternalProcessPaymentResponse;

class ExternalProcessPaymentResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ExternalProcessPaymentResponse
     */
    public $externalProcessPaymentResponse;

    public function setUp()
    {
        $this->externalProcessPaymentResponse = new ExternalProcessPaymentResponse(array(
            'money_source' => array(
                'type' => "payment-card",
                'payment_card_type' => "VISA",
                'pan_fragment' => "**** **** **** 0334",
                'money_source_token' => "B6AE719BAF712404E08EF8A430B0F58CD8F2C592452CA5205F7E52B1FC72BD3D42745714D60B4E75BD742F22E8120F0861ED99B69EC01C6194CF5D425C89598B959DE0E9EDB13AFD710CF74ACE08DBFBE2A4289CE2456EB50EF7DFE6D22E466D417ACD1BF8DE33B5C93BDA9AAA8C4D693DCD2E9AA2A31A51C185"
            )
        ));
    }

    public function testExternalProcessPaymentResponse()
    {
        $this->assertNotNull($this->externalProcessPaymentResponse);
    }

    public function testGetMoneySource()
    {
        $this->assertNotNull($this->externalProcessPaymentResponse->getMoneySource());
        $this->assertTrue(is_array($this->externalProcessPaymentResponse->getMoneySource()));
        $this->assertCount(4, $this->externalProcessPaymentResponse->getMoneySource());
    }

    public function tearDown()
    {

    }

} 