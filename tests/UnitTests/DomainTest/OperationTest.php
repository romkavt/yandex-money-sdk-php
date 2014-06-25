<?php
use YandexMoney\Domain\Operation;

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/1/14
 * Time: 12:26 PM
 */
class OperationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Operation
     */
    protected $operation;

    public function setUp()
    {
        $this->operation = new Operation(array(
            "operation_id" => "1234567",
            "status" => "success",
            "pattern_id" => "2904",
            "direction" => "out",
            "amount" => 500.00,
            "datetime" => "2011-07-11T20:43:00.000+03:00",
            "title" => "Оплата ADSL-доступа компании XXX",
            "type" => "payment-shop"
        ));
    }

    public function testOperation()
    {
        $this->assertNotNull($this->operation);
    }

    public function testGetOperationId()
    {
        $this->assertNotNull($this->operation->getOperationId());
        $this->assertEquals("1234567", $this->operation->getOperationId());
    }

    public function testGetStatus()
    {
        $this->assertNotNull($this->operation->getStatus());
        $this->assertEquals("success", $this->operation->getStatus());
    }

    public function testGetPatternId()
    {
        $this->assertNotNull($this->operation->getPatternId());
        $this->assertEquals("2904", $this->operation->getPatternId());
    }

    public function testGetDirection()
    {
        $this->assertNotNull($this->operation->getDirection());
        $this->assertEquals("out", $this->operation->getDirection());
    }

    public function testGetAmount()
    {
        $this->assertNotNull($this->operation->getAmount());
        $this->assertEquals(500.00, $this->operation->getAmount());
    }

    public function testGetDatetime()
    {
        $this->assertNotNull($this->operation->getDatetime());
        $this->assertEquals("2011-07-11T20:43:00.000+03:00", $this->operation->getDatetime());
    }

    public function testGetTitle()
    {
        $this->assertNotNull($this->operation->getTitle());
        $this->assertEquals("Оплата ADSL-доступа компании XXX", $this->operation->getTitle());
    }

    public function testGetType()
    {
        $this->assertNotNull($this->operation->getType());
        $this->assertEquals("payment-shop", $this->operation->getType());
    }

    public function tearDown()
    {
        $this->operation = null;
    }

} 