<?php
use YandexMoney\Domain\Avatar;

class AvatarTest extends PHPUnit_Framework_TestCase
{

    protected $avatar;

    public function setUp()
    {
        $this->avatar = new Avatar(array(
            Avatar::URL => "http://www.example.com/avatar.jpg",
            Avatar::TIMESTAMP => "2014-06-01 06:35:45"
        ));
    }

    public function testAvatar()
    {
        $this->assertNotNull($this->avatar);
    }

    public function testGetUrl()
    {
        $this->assertNotNull($this->avatar->getUrl());
        $this->assertEquals("http://www.example.com/avatar.jpg", $this->avatar->getUrl());
    }

    public function testGetTimestamp()
    {
        $this->assertNotNull($this->avatar->getTimestamp());
        $this->assertEquals("2014-06-01 06:35:45", $this->avatar->getTimestamp());
    }

    public function tearDown()
    {
        $this->avatar = null;
    }

} 