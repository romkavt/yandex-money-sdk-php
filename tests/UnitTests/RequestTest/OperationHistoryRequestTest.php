<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/24/14
 * Time: 4:36 PM
 */

namespace YandexMoney\Request;

use PHPUnit_Framework_TestCase;

class OperationHistoryRequestTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var OperationHistoryRequest
     */
    protected $operationHistoryRequest;

    public function setUp()
    {
        $this->operationHistoryRequest = new OperationHistoryRequest();
        $this->operationHistoryRequest->setDetails(true);
        $this->operationHistoryRequest->setFrom("899998999");
        $this->operationHistoryRequest->setTill("899998999");
        $this->operationHistoryRequest->setLabel("label");
        $this->operationHistoryRequest->setStartRecord(1);
        $this->operationHistoryRequest->setRecords(20);
        $this->operationHistoryRequest->setType("type");
    }

    public function testOperationHistoryRequest()
    {
        $this->assertNotNull($this->operationHistoryRequest);
    }

    public function tearDown()
    {
        $this->operationHistoryRequest = null;
    }

    public function testGetDetails()
    {
        $this->assertTrue($this->operationHistoryRequest->getDetails());
    }

    public function testGetFrom()
    {
        $this->assertEquals("899998999", $this->operationHistoryRequest->getFrom());
    }

    public function testGetLabel()
    {
        $this->assertEquals("label", $this->operationHistoryRequest->getLabel());
    }

    public function testGetRecords()
    {
        $this->assertEquals(20, $this->operationHistoryRequest->getRecords());
    }

    public function testGetStartRecord()
    {
        $this->assertEquals(1, $this->operationHistoryRequest->getStartRecord());
    }

    public function testGetTill()
    {
        $this->assertEquals("899998999", $this->operationHistoryRequest->getTill());
    }

    public function testGetType()
    {
        $this->assertEquals("type", $this->operationHistoryRequest->getType());
    }


} 