<?php 

require "tests/constants.php";
require "lib/api.php";

class MainTest extends PHPUnit_Framework_TestCase {
	public function testAccountInfo() {
		$api = new \YandexMoney\API;
		$api->access_token = ACCESS_TOKEN;
		$result = $api->accountInfo();

		$this->assertObjectHasAttribute("account", $result);
		$this->assertObjectHasAttribute("currency", $result);
	}
	public function testOperationHistory() {

	}
}
?>