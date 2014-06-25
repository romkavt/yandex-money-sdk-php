<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/17/14
 * Time: 1:50 PM
 */

require_once __DIR__ . '/../../../vendor/autoload.php';

use YandexMoney\YandexMoney;
use YandexMoney\Utils\AuthRequestBuilder;

class AuthRequestBuilderTest extends PHPUnit_Framework_TestCase
{

    public function testIsAuthRequestBuilderAvailable()
    {
        $authRequestBuilder = YandexMoney::getAuthRequestBuilder();
        $this->assertNotNull($authRequestBuilder);

    }

    public function testResponseTypeConstant()
    {
        $this->assertEquals(AuthRequestBuilder::RESPONSE_TYPE, "code");
    }

    public function testClientIdMethod()
    {
        $authRequestBuilder = YandexMoney::getAuthRequestBuilder();
        $authRequestBuilder->setClientId("YOUR_APP_CLIENT_ID");
        $this->assertEquals($authRequestBuilder->getClientId(), "YOUR_APP_CLIENT_ID");
    }

    public function testRedirectUriMethod()
    {
        $authRequestBuilder = YandexMoney::getAuthRequestBuilder();
        $authRequestBuilder->setRedirectUri("YOUR_APP_REDIRECT_URI");
        $this->assertEquals($authRequestBuilder->getRedirectUri(), "YOUR_APP_REDIRECT_URI");
    }

} 