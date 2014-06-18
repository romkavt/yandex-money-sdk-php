<?php
use YandexMoney\Response\IncomingTransferRejectResponse;

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/5/14
 * Time: 9:51 PM
 */
class IncomingTransferRejectResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var IncomingTransferRejectResponse
     */
    public $incomingTransferRejectResponse;

    public function setUp()
    {
        $this->incomingTransferRejectResponse = new IncomingTransferRejectResponse(array(
            'status' => "refused",
            'error' => "illegal_param_operation_id"
        ));
    }

    public function testIncomingTransferRejectResponse()
    {
        $this->assertNotNull($this->incomingTransferRejectResponse);
    }

    public function testGetStatus()
    {
        $this->assertNotNull($this->incomingTransferRejectResponse->getStatus());
        $this->assertEquals("refused", $this->incomingTransferRejectResponse->getStatus());
    }

    public function testGetError()
    {
        $this->assertNotNull($this->incomingTransferRejectResponse->getError());
        $this->assertEquals("illegal_param_operation_id", $this->incomingTransferRejectResponse->getError());
    }

    public function isSuccess()
    {
        $this->assertNotNull($this->incomingTransferRejectResponse->isSuccess());
        $this->assertFalse($this->incomingTransferRejectResponse->isSuccess());
    }

    public function tearDown()
    {
        $this->incomingTransferRejectResponse = null;
    }

} 