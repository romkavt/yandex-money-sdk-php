<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/18/14
 * Time: 9:49 PM
 */

require_once __DIR__ . '/../../../vendor/autoload.php';



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