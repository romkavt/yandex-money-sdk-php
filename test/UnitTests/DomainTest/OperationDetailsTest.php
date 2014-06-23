<?php
use YandexMoney\Domain\OperationDetails;

class OperationDetailsTest extends PHPUnit_Framework_TestCase
{


    /**
     * @var OperationDetails
     */
    protected $operationDetails;


    public function setUp()
    {
        $this->operationDetails = new OperationDetails(array(
            "operation_id" => "1234567",
            "status" => "success",
            "pattern_id" => "p2p",
            "direction" => "out",
            "amount" => 50.25,
            "datetime" => "2011-07-11T20:43:00.000+04:00",
            "title" => "Перевод на счет 4100123456789",
            "recipient" => "4100123456789",
            "recipient_type" => "account",
            "message" => "Купите бублики",
            "comment" => "Перевод от пользователя Яндекс.Денег",
            "codepro" => false,
            "details" => "Счет получателя:\n4100123456789\nСумма к получению: 50,00 руб.",
            "type" => "payment-shop"
        ));

        /*$this->operationDetails = new OperationDetails(array(
            "error" => "illegal_param_operation_id"
        ));*/
    }

    public function testGetError() {
        $this->assertNull($this->operationDetails->getError());
        //$this->assertEquals("illegal_param_operation_id", $this->operationDetails->getError());
    }

    public function testIsSuccess() {
        $this->assertNotNull($this->operationDetails->isSuccess());
        $this->assertEquals(true, $this->operationDetails->isSuccess());
    }

    public function testGetOperationId()
    {
        $this->assertNotNull($this->operationDetails->getOperationId());
        $this->assertEquals("1234567", $this->operationDetails->getOperationId());
    }

    public function testGetStatus()
    {
        $this->assertNotNull($this->operationDetails->getStatus());
        $this->assertEquals("success", $this->operationDetails->getStatus());
    }

    public function testGetPatternId()
    {
        $this->assertNotNull($this->operationDetails->getPatternId());
        $this->assertEquals("p2p", $this->operationDetails->getPatternId());
    }

    public function testGetDirection()
    {
        $this->assertNotNull($this->operationDetails->getDirection());
        $this->assertEquals("out", $this->operationDetails->getDirection());
    }

    public function testGetAmount()
    {
        $this->assertNotNull($this->operationDetails->getAmount());
        $this->assertEquals(50.25, $this->operationDetails->getAmount());
    }

    public function testGetDatetime()
    {
        $this->assertNotNull($this->operationDetails->getDatetime());
        $this->assertEquals("2011-07-11T20:43:00.000+04:00", $this->operationDetails->getDatetime());
    }

    public function testGetTitle()
    {
        $this->assertNotNull($this->operationDetails->getTitle());
        $this->assertEquals("Перевод на счет 4100123456789", $this->operationDetails->getTitle());
    }

    public function testGetType()
    {
        $this->assertNotNull($this->operationDetails->getType());
        $this->assertEquals("payment-shop", $this->operationDetails->getType());
    }

    public function testGetRecipient()
    {
        $this->assertNotNull($this->operationDetails->getRecipient());
        $this->assertEquals("4100123456789", $this->operationDetails->getRecipient());
    }

    public function testGetRecipientType()
    {
        $this->assertNotNull($this->operationDetails->getRecipientType());
        $this->assertEquals("account", $this->operationDetails->getRecipientType());
    }

    public function testGetMessage()
    {
        $this->assertNotNull($this->operationDetails->getMessage());
        $this->assertEquals("Купите бублики", $this->operationDetails->getMessage());
    }

    public function testGetComment()
    {
        $this->assertNotNull($this->operationDetails->getComment());
        $this->assertEquals("Перевод от пользователя Яндекс.Денег", $this->operationDetails->getComment());
    }

    public function testGetCodepro()
    {
        $this->assertNotNull($this->operationDetails->getCodepro());
        $this->assertEquals(false, $this->operationDetails->getCodepro());
    }

    public function testGetDetails()
    {
        $this->assertNotNull($this->operationDetails->getDetails());
        $this->assertEquals(
            "Счет получателя:\n4100123456789\nСумма к получению: 50,00 руб.",
            $this->operationDetails->getDetails()
        );
    }

    public function tearDown()
    {
        $this->operationDetails = null;

    }


}