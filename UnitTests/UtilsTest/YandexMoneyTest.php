<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use YandexMoney\YandexMoney;

class YandexMoneyTest extends PHPUnit_Framework_TestCase {

    public function testGetAuthRequestBuilder() {

       $authRequestBuilder = YandexMoney::getAuthRequestBuilder();

       $this->assertNotNull($authRequestBuilder);
       $this->assertInstanceOf('YandexMoney\Utils\AuthRequestBuilder' , $authRequestBuilder);
    }

    public function testGetRightsConfigurator() {

        $rightsConfigurator = YandexMoney::getRightsConfigurator();

        $this->assertNotNull($rightsConfigurator);
        $this->assertInstanceOf('YandexMoney\Utils\RightsConfigurator', $rightsConfigurator);
    }

    public function testGetApiNetworkClient() {
        $apiNetworkClient = YandexMoney::getApiNetworkClient();

        $this->assertNotNull($apiNetworkClient);
        $this->assertInstanceOf('YandexMoney\Utils\ApiNetworkClient', $apiNetworkClient);
    }

    public function testGetApiFacade() {
        $apiFacade = YandexMoney::getApiFacade();

        $this->assertNotNull($apiFacade);
        $this->assertInstanceOf('YandexMoney\Utils\ApiFacade', $apiFacade);
    }

    public function testGetNotificationReceiver() {
        $notificationReceiver = YandexMoney::getNotificationReceiver(NOTIFICATION_SECRET);

        $this->assertNotNull($notificationReceiver);
        $this->assertInstanceOf('YandexMoney\Utils\NotificationReceiver', $notificationReceiver);
    }
}