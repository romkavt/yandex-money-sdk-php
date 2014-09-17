<?php 
namespace YandexMoney;

class API {
	public $code;
	public $client_id;
	public $redirect_url;

	public $access_token = NULL;

	const MONEY_URL = "https://money.yandex.ru";
	const SP_MONEY_URL = "https://sp-money.yandex.ru";

	function sendRequest($url, $params=array()) {
		$full_url= self::MONEY_URL . $url;
		$result = \Requests::post($full_url, array(
			"Authorization" => sprintf("Bearer %s", $this->access_token),
			), $params);
		return json_decode($result->body);
	}
	function CheckToken() {
		if($this->access_token == NULL) {
			throw Exception("obtain access_token first");
		}
	}
	function accountInfo() {
		$this->CheckToken();
		return $this->sendRequest("/api/account-info");
	}
	function operationHistory($options=NULL) {

	}
	function operation_details($operation_id) {

	}
	function requestPayment($options) {

	}
	function processPayment($options) {

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

	function obtainToken($client_secret=NULL) {
		$url = self::MONEY_URL . "/oauth/token";
		$data = array(
			'code' => $this->code,
			'client_id' => $this->client_id,
			'grant_type' => "authorization_code",
			"redirect_url" => $this->redirect_url
			);
		if($client_secret != NULL)
			$data['client_secret'] = $client_secret;
		echo $url . "\n";
		$response = \Requests::post("https://money.yandex.ru/oauth/token", array(), $data);
	}
}
?>
