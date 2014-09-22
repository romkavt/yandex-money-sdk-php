<?php 
namespace YandexMoney;

require_once __DIR__ . "/exceptions.php";

class API {

    const MONEY_URL = "https://money.yandex.ru";
    const SP_MONEY_URL = "https://sp-money.yandex.ru";

    function __construct($access_token) {
        $this->access_token = $access_token;
    }
    private static function processResult($result) {
        switch ($result->status_code) {
            case 400:
                throw new Exceptions\FormatError; 
                break;
            case 401:
                throw new Exceptions\TokenError; 
                break;
            case 403:
                throw new Exceptions\ScopeError; 
                break;
        }
        return json_decode($result->body);
    }
    public static function sendRequest($url, $options=array(), $access_token=NULL) {
        $full_url= self::MONEY_URL . $url;
        if($access_token != NULL) {
            $headers = array(
                "Authorization" => sprintf("Bearer %s", $access_token),
            );
        } 
        else {
            $headers = array();
        }
        $result = \Requests::post($full_url, $headers, $options);
        return self::processResult($result);
    }
    function sendAuthenticatedRequest($url, $options=array()) {
        $this->checkToken();
        return self::sendRequest($url, $options, $this->access_token);
    }
    function checkToken() {
        if($this->access_token == NULL) {
            throw new \Exception("obtain access_token first");
        }
    }
    function accountInfo() {
        return $this->sendAuthenticatedRequest("/api/account-info");
    }
    function operationHistory($options=NULL) {
        return $this->sendAuthenticatedRequest("/api/operation-history", $options);
    }
    function operationDetails($operation_id) {
        return $this->sendAuthenticatedRequest("/api/operation-details",
            array("operation_id" => $operation_id)
        );
    }
    function requestPayment($options) {
        return $this->sendAuthenticatedRequest("/api/request-payment", $options);
    }
    function processPayment($options) {
        return $this->sendAuthenticatedRequest("/api/process-payment", $options);
    }
    public static function getInstanceId($client_id) {
        return $this->sendRequest("/api/instance-id",
            array("client_id" => $client_id));
    }
    function incomingTransferAccept($operation_id, $protection_code=NULL) {
        return $this->sendAuthenticatedRequest("/api/incoming-transfer-accept",
            array(
                "operation_id" => $operation_id,
                "protection_code" => $protection_code
            ));
    }
    function incomingTransferReject($operation_id) {
        return $this->sendAuthenticatedRequest("/api/incoming-transfer-reject",
            array(
                "operation_id" => $operation_id,
            ));
    }
    function requestExternalPayment($payment_options) {
        return self::sendRequest("/api/request-external-payment",
            $payment_options);
    }
    function processExternalPayment($payment_options) {
        return self::sendRequest("/api/process-external-payment",
            $payment_options);
    }

    public static function buildObtainTokenUrl($client_id, $redirect_uri,
            $client_secret=NULL, $scope) {
        $params = sprintf(
            "client_id=%s&response_type=%s&redirect_uri=%s&scope=%s",
            $client_id, "code", $redirect_uri, implode(" ", $scope)
            );
        return sprintf("%s/oauth/authorize?%s", self::SP_MONEY_URL, $params);
    }
    public static function getAccessToken($client_id, $code, $redirect_uri,
            $client_secret=NULL) {
        $full_url = self::SP_MONEY_URL . "/oauth/token";
        $result = \Requests::post($full_url, array(), array(
            "code" => $code,
            "client_id" => $client_id,
            "grant_type" => "authorization_code",
            "redirect_uri" => $redirect_uri,
            "client_secret" => $client_secret
        ));
        return self::processResult($result);

    }
}