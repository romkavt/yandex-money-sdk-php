<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../sample/consts.php';


use YandexMoney\YandexMoney;


class ApiFacadeTest extends PHPUnit_Framework_TestCase
{

    public function testAuthorizeApplication()
    {
        $apiFacade = YandexMoney::getApiFacade();
        $this->assertInstanceOf('YandexMoney\Utils\ApiFacade', $apiFacade);
    }

    public function testPutIfNotNull()
    {

        $apiFacade = YandexMoney::getApiFacade();

        $testArray = array();

        $apiFacade->putIfNotNull("1", $testArray, "one");

        $this->assertArrayHasKey("one", $testArray);
        $this->assertEquals("1", $testArray["one"]);

        $testArray = array();

        $apiFacade->putIfNotNull(null, $testArray, "one");

        $this->assertCount(0, $testArray);

    }

} 