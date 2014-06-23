<?php

namespace YandexMoney;

use YandexMoney\Presets\ApiKey;
use YandexMoney\Utils\AccessTokenRequestBuilder;
use YandexMoney\Utils\ApiFacade;
use YandexMoney\Utils\AuthRequestBuilder;
use YandexMoney\Utils\NotificationReceiver;
use YandexMoney\Utils\RightsConfigurator;
use YandexMoney\Utils\ScopeConfigurator;
use YandexMoney\Utils\ApiNetworkClient;

/**
 * Class YandexMoney {@link http://api.yandex.ru/money/doc/dg/concepts/About.xml}
 * @package YandexMoney
 */
class YandexMoney
{

    const VERSION = '2.0.1'; // was '1.3.0'

    public static function getAuthRequestBuilder()
    {
        return new AuthRequestBuilder();
    }

    public static function getRightsConfigurator()
    {
        return new RightsConfigurator();
    }

    public static function getApiNetworkClient()
    {
        return new ApiNetworkClient();
    }

    public static function getApiFacade()
    {
        return new ApiFacade();
    }

    public static function getNotificationReceiver($notificationSecret)
    {
        return new NotificationReceiver($notificationSecret);
    }

    public static function authorizationCodeReceived()
    {
        return array_key_exists(ApiKey::CODE, $_GET) && !empty($_GET[ApiKey::CODE]);
    }

    public static function getAuthorizationCode()
    {
        return $_GET[ApiKey::CODE];
    }

    public static function errorCodeReceived()
    {
        return array_key_exists(ApiKey::ERROR, $_GET) && !empty($_GET[ApiKey::ERROR]);
    }

    public static function getErrorCode()
    {
        return $_GET[ApiKey::ERROR];
    }

    public static function getErrorDescription()
    {
        return $_GET[ApiKey::ERROR_DESCRIPTION];
    }

} 