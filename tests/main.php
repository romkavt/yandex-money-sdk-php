<?php 

require "tests/constants.php";
require "lib/api.php";

class MainTest extends PHPUnit_Framework_TestCase {
	// public function testAccountInfo() {
	// 	$api = new \YandexMoney\API;
	// 	$api->redirect_url = "http://localhost:8000/redirect";
	// 	$api->client_id = CLIENT_ID;
	// 	$api->code = CODE;
	// 	$api->obtainToken(CLIENT_SECRET);
	// }
	public function testAccountInfo() {
		$api = new \YandexMoney\API;
		$api->access_token = ACCESS_TOKEN;
		$result = $api->accountInfo();
		var_dump($result);
	}
}
?>