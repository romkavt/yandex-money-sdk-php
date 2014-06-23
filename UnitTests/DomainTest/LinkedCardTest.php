<?php
use YandexMoney\Domain\LinkedCard;

class LinkedCardTest extends PHPUnit_Framework_TestCase
{

    protected $linkedCard;

    public function setUp()
    {
        $this->linkedCard = new LinkedCard(
            array(
                LinkedCard::PAN_FRAGMENT => "510000******9999",
                LinkedCard::TYPE => "MasterCard"
            )
        );
    }

    public function testLinkedCard()
    {
        $this->assertNotNull($this->linkedCard);
    }

    public function testGetPanFragment()
    {
        $this->assertNotNull($this->linkedCard->getPanFragment());
        $this->assertEquals("510000******9999", $this->linkedCard->getPanFragment());
    }

    public function testGetType()
    {
        $this->assertNotNull($this->linkedCard->getType());
        $this->assertEquals("MasterCard", $this->linkedCard->getType());
    }

    public function tearDown()
    {
        $this->linkedCard = null;
    }
} 