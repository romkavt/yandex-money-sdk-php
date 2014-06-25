<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/10/14
 * Time: 8:45 PM
 */

namespace YandexMoney\Request;


class ProcessPaymentByWalletRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ProcessPaymentByWalletRequest
     */
    protected $processPaymentByWalletRequest;

    public function setUp()
    {
        $this->processPaymentByWalletRequest = new ProcessPaymentByWalletRequest();
        $this->processPaymentByWalletRequest->setRequestId("REQUEST");
    }

    public function testProcessPaymentByWalletRequest()
    {
        $this->assertNotNull($this->processPaymentByWalletRequest);
    }

    public function testGetRequestId()
    {
        $this->assertEquals("REQUEST", $this->processPaymentByWalletRequest->getRequestId());
    }

    public function tearDown()
    {

    }
} 