<?php
use YandexMoney\Domain\OperationDetails;
use YandexMoney\Response\OperationHistoryResponse;

class OperationHistoryResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var OperationHistoryResponse
     */
    protected $operationHistoryResponseTest;

    public function setUp()
    {
        $this->operationHistoryResponseTest = new OperationHistoryResponse(array(
            'error' => "illegal_param_type",
            'next_record' => "4",
            'operations' => array(
                array()
            )
        ));
    }

    public function testOperationHistoryResponse()
    {
        $this->assertNotNull($this->operationHistoryResponseTest);
    }

    public function testGetError()
    {
        $this->assertNotNull($this->operationHistoryResponseTest->getError());
        $this->assertEquals("illegal_param_type", $this->operationHistoryResponseTest->getError());
    }

    public function testGetNextRecord()
    {
        $this->assertNotNull($this->operationHistoryResponseTest->getNextRecord());
        $this->assertEquals("4", $this->operationHistoryResponseTest->getNextRecord());
    }

    public function testGetOperations()
    {
        $this->assertNotNull($this->operationHistoryResponseTest->getOperations());
        $this->assertTrue(is_array($this->operationHistoryResponseTest->getOperations()));

        $operations = $this->operationHistoryResponseTest->getOperations();

        $this->assertInstanceOf('YandexMoney\Domain\OperationDetails', $operations[0]);
    }

    public function tearDown()
    {
        $this->operationHistoryResponseTest = null;
    }

} 