<?php

// Tested on PHP 5.2, 5.3

// This is what we exactly need to make requests to API
if (!function_exists('curl_init')) {
  throw new Exception('Yandex.Money API SDK needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Yandex.Money API SDK needs the JSON PHP extension.');
}

class YandexMoney {
    
    const VERSION = '1.2.5';

    private $clientId;
    private $logFile;

    const YM_URI_API = 'https://money.yandex.ru/api';
    const YM_URI_AUTH = 'https://sp-money.yandex.ru/oauth/authorize';
    const YM_URI_TOKEN = 'https://sp-money.yandex.ru/oauth/token';

    public function __construct($clientId, $logFile = null) {
        self::_validateClientId($clientId);
        $this->clientId = $clientId;
        $this->logFile = $logFile;
    }

    public function getClientId() {
        return $this->clientId;
    }

    public static function authorizeUri($clientId, $redirectUri, $scope = null) {
        self::_validateClientId($clientId);

        if (!isset($scope) || $scope === '') {
            $scope = 'account-info operation-history';
        }
        $scope = trim(strtolower($scope));

        $res = self::YM_URI_AUTH . "?client_id=$clientId&response_type=code&scope=" .
                urlencode($scope) . "&redirect_uri=" . urlencode($redirectUri);
        return $res;
    }

    public function receiveOAuthToken($code, $redirectUri, $client_secret = null) {
        $paramArray['grant_type'] = 'authorization_code';
        $paramArray['client_id'] = $this->clientId;
        $paramArray['code'] = $code;
        $paramArray['redirect_uri'] = $redirectUri;
        if ($client_secret) {
            $paramArray['client_secret'] = $client_secret;
        }
        $params = http_build_query($paramArray);

        $requestor = new YM_ApiRequestor(null, $this->logFile);
        $resp = $requestor->request(self::YM_URI_TOKEN, $params);
        return new YM_ReceiveTokenResponse($resp);
    }

    public function revokeOAuthToken($accessToken) {
        $requestor = new YM_ApiRequestor($accessToken, $this->logFile);
        $resp = $requestor->request(self::YM_URI_API . '/revoke', null, false);
        return true;
    }

    public function accountInfo($accessToken) {
        $requestor = new YM_ApiRequestor($accessToken, $this->logFile);
        $resp = $requestor->request(self::YM_URI_API . '/account-info');
        return new YM_AccountInfoResponse($resp);
    }

    public function operationHistory($accessToken, $startRecord = null, $records = null, $type = null, $from = null, $till = null, $label = null) {
        $paramArray = Array();
        if (isset($type))
            $paramArray['type'] = $type;
        if (isset($startRecord))
            $paramArray['start_record'] = $startRecord;
        if (isset($records))
            $paramArray['records'] = $records;
        if (isset($label))
            $paramArray['label'] = $label;
        if (isset($from))
            $paramArray['from'] = $from;
        if (isset($till))
            $paramArray['till'] = $till;
        if (count($paramArray) > 0)
            $params = http_build_query($paramArray);
        else
            $params = '';

        $requestor = new YM_ApiRequestor($accessToken, $this->logFile);
        $resp = $requestor->request(self::YM_URI_API . '/operation-history', $params);
        return new YM_OperationHistoryResponse($resp);
    }

    public function operationDetail($accessToken, $operationId) {
        $paramArray['operation_id'] = $operationId;
        $params = http_build_query($paramArray);

        $requestor = new YM_ApiRequestor($accessToken, $this->logFile);
        $resp = $requestor->request(self::YM_URI_API . '/operation-details', $params);
        return new YM_OperationDetail($resp);
    }

    public function requestPaymentP2P($accessToken, $to, $amount, $comment = null, $message = null, $label = null) {
        $paramArray['pattern_id'] = 'p2p';
        $paramArray['to'] = $to;
        $paramArray['amount'] = $amount;
        $paramArray['comment'] = $comment;
        $paramArray['message'] = $message;
        $paramArray['label'] = $label;
        $params = http_build_query($paramArray);

        $requestor = new YM_ApiRequestor($accessToken, $this->logFile);
        $resp = $requestor->request(self::YM_URI_API . '/request-payment', $params);
        return new YM_RequestPaymentResponse($resp);
    }

    public function processPaymentByWallet($accessToken, $requestId) {
        $paramArray['request_id'] = $requestId;
        $paramArray['money_source'] = 'wallet';
        $params = http_build_query($paramArray);

        $requestor = new YM_ApiRequestor($accessToken, $this->logFile);
        $resp = $requestor->request(self::YM_URI_API . '/process-payment', $params);
        return new YM_ProcessPaymentResponse($resp);
    }

    public function requestPaymentShop($accessToken, $shopParams) {
        $params = http_build_query($shopParams);

        $requestor = new YM_ApiRequestor($accessToken, $this->logFile);
        $resp = $requestor->request(self::YM_URI_API . '/request-payment', $params);
        return new YM_RequestPaymentResponse($resp);
    }


    public function processPaymentByCard($accessToken, $requestId, $csc) {
        $paramArray['request_id'] = $requestId;
        $paramArray['money_source'] = 'card';
        $paramArray['csc'] = $csc;
        $params = http_build_query($paramArray);

        $requestor = new YM_ApiRequestor($accessToken, $this->logFile);
        $resp = $requestor->request(self::YM_URI_API . '/process-payment', $params);
        return new YM_ProcessPaymentResponse($resp);
    }

    private static function _validateClientId($clientId) {
        if (($clientId == null) || ($clientId === '')) {
            throw new YM_Error("You must pass a valid application client_id");
        }
    }
}

// Errors
require(dirname(__FILE__) . '/YandexMoney/Error.php');
require(dirname(__FILE__) . '/YandexMoney/ApiConnectionError.php');
require(dirname(__FILE__) . '/YandexMoney/ApiError.php');
require(dirname(__FILE__) . '/YandexMoney/InsufficientScopeError.php');
require(dirname(__FILE__) . '/YandexMoney/InternalServerError.php');
require(dirname(__FILE__) . '/YandexMoney/InvalidTokenError.php');

// Plumbing
require(dirname(__FILE__) . '/YandexMoney/ApiRequestor.php');

// Yandex.Money API Resources
require(dirname(__FILE__) . '/YandexMoney/ReceiveTokenResponse.php');
require(dirname(__FILE__) . '/YandexMoney/AccountInfoResponse.php');
require(dirname(__FILE__) . '/YandexMoney/OperationHistoryResponse.php');
require(dirname(__FILE__) . '/YandexMoney/Operation.php');
require(dirname(__FILE__) . '/YandexMoney/OperationDetail.php');
require(dirname(__FILE__) . '/YandexMoney/RequestPaymentResponse.php');
require(dirname(__FILE__) . '/YandexMoney/ProcessPaymentResponse.php');
