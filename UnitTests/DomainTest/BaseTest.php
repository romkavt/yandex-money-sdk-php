<?php
use YandexMoney\Domain\Base;

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/1/14
 * Time: 10:30 AM
 */
class BaseTest extends PHPUnit_Framework_TestCase
{

    protected $base;

    public function setUp()
    {
        $this->base = new Base(array(
            'key2' => 'value2',
            'key3' => array(),
            'key4' => new stdClass()
        ));
    }

    public function testBase()
    {
        $this->assertNotNull($this->base);
    }

    public function testCheckAndReturn()
    {
        $this->assertNull($this->base->checkAndReturn('key1'));
        $this->assertNotNull($this->base->checkAndReturn('key2'));
        $this->assertEquals($this->base->checkAndReturn('key2'), 'value2');
        $this->assertTrue(is_array($this->base->checkAndReturn('key3')));
        $this->assertTrue(is_object($this->base->checkAndReturn('key4')));
    }

    public function tearDown() {
        $this->base = null;
    }
} 