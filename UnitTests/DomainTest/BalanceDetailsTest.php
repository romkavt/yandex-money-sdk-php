<?php
use YandexMoney\Domain\BalanceDetails;

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/1/14
 * Time: 10:14 AM
 */
class BalanceDetailsTest extends PHPUnit_Framework_TestCase
{

    protected $balanceDetails;

    public function setUp()
    {
        $this->balanceDetails = new BalanceDetails(array(
            BalanceDetails::TOTAL => 200.50,
            BalanceDetails::AVAILABLE => 120.40,
            BalanceDetails::DEPOSITION_PENDING => 140.40,
            BalanceDetails::BLOCKED => 50.55,
            BalanceDetails::DEBT => 15.15
        ));
    }

    public function testBalanceDetails()
    {
        $this->assertNotNull($this->balanceDetails);
    }

    public function testGetTotal()
    {
        $this->assertNotNull($this->balanceDetails->getTotal());
        $this->assertEquals(200.50, $this->balanceDetails->getTotal());
    }

    public function testGetAvailable()
    {
        $this->assertNotNull($this->balanceDetails->getAvailable());
        $this->assertEquals(120.40, $this->balanceDetails->getAvailable());
    }

    public function testGetDepositionPending()
    {
        $this->assertNotNull($this->balanceDetails->getDepositionPending());
        $this->assertEquals(140.40, $this->balanceDetails->getDepositionPending());
    }

    public function testGetBlocked()
    {
        $this->assertNotNull($this->balanceDetails->getBlocked());
        $this->assertEquals(50.55, $this->balanceDetails->getBlocked());
    }

    public function testGetDebt()
    {
        $this->assertNotNull($this->balanceDetails->getDebt());
        $this->assertEquals(15.15, $this->balanceDetails->getDebt());
    }

} 