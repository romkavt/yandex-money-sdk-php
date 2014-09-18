<?php 
namespace YandexMoney;

require_once __DIR__ . "/exceptions.php";

class API {

    const MONEY_URL = "https://money.yandex.ru";
    const SP_MONEY_URL = "https://sp-money.yandex.ru";

    function __construct($access_token) {
        $this->access_token = $access_token;
    }
    function sendRequest($url, $options=array()) {
        $this->checkToken();
        $full_url= self::MONEY_URL . $url;
        $result = \Requests::post($full_url, array(
            "Authorization" => sprintf("Bearer %s", $this->access_token),
            ), $options);
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
    function checkToken() {
        if($this->access_token == NULL) {
            throw Exception("obtain access_token first");
        }
    }
    function accountInfo() {
        return $this->sendRequest("/api/account-info");
    }
    function operationHistory($options=NULL) {
        return $this->sendRequest("/api/operation-history", $options);
    }
    function operationDetails($operation_id) {
        return $this->sendRequest("/api/operation-details",
            array("operation_id" => $operation_id)
        );
    }
    function requestPayment($options) {
        return $this->sendRequest("/api/request-payment", $options);
    }
    function processPayment($options) {
        return $this->sendRequest("/api/process-payment", $options);
    }
    function getInstanceId() {

    }
    function incomingTransferAccept($operation_id, $protection_code=NULL) {

    }
    function incomingTransferReject($operation_id) {

    }
    function request_external_payment($payment_options) {

    }
    function process_external_payment($payment_options) {

    }

    public static function buildObtainTokenUrl($client_id, $redirect_uri,
            $client_secret=NULL, $scope) {
        $data = array(
            "client_id" => $client_id,
            "response_type" => "code",
            "redirect_uri" => $redirect_uri,
            "scope" => implode(" ", $scope),
        );
        $params = http_build_query($data);
        return sprintf("%s/oauth/authorize/?%s", self::SP_MONEY_URL, $params);
    }
}