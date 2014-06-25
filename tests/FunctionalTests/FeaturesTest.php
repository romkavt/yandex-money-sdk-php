<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use YandexMoney\Request\OperationHistoryRequest;
use YandexMoney\Request\ProcessPaymentByWalletRequest;
use YandexMoney\YandexMoney;

define('TOKEN', "410011124546815.E75AA8800D45AF9ADDAF91CD97BCCE7B62316D870EA748D8F49B99047208EC168FE4832B82DC70B408F234738BFA9FD22DA2E8BF8F848CB80525EEBD1E2CDAB8BA2135BB325544FE6E3A1971FAAB69DB9712CACB905C5B4A171B66991DD2400AC002BC30D22848BDDB5821260D450D818EC93A286C1A03CBAF0830A99086B7B0");

class FeaturesTest extends PHPUnit_Framework_TestCase
{

    public function testOperationHistory()
    {

        $operationHistoryRequest = new OperationHistoryRequest();
        $operationHistoryRequest->setStartRecord(0);
        $operationHistoryRequest->setRecords(3);

        $apiFacade = YandexMoney::getApiFacade();

        $response = null;

        $response = $apiFacade->operationHistory(TOKEN, $operationHistoryRequest);

        $this->assertInstanceOf('YandexMoney\Response\OperationHistoryResponse', $response);
        $this->assertEquals(0, count($response->getOperations()));
    }

    public function testGetOperationDetails()
    {
        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $operationDetails = $apiFacade->operationDetail(TOKEN, "OPERATION_ID");

        $this->assertInstanceOf('YandexMoney\Domain\OperationDetails', $operationDetails);
        $this->assertEquals("illegal_param_operation_id", $operationDetails->getError());
    }

    public function testGetAccountInfo()
    {
        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $accountInfo = $apiFacade->accountInfo(TOKEN);

        $this->assertInstanceOf('YandexMoney\Domain\AccountInfo', $accountInfo);
        $this->assertEquals("410011124546815", $accountInfo->getAccount());
        $this->assertEquals(0, $accountInfo->getBalance());
        $this->assertEquals("643", $accountInfo->getCurrency());
        $this->assertEquals("personal", $accountInfo->getAccountType());
        $this->assertEquals("anonymous", $accountInfo->getAccountStatus());
    }

    public function testRequestPayment()
    {
        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "success";

        $apiFacade = YandexMoney::getApiFacade();
        $response = $apiFacade->requestPaymentShop(TOKEN, $params);

        $this->assertInstanceOf('YandexMoney\Response\RequestPaymentResponse', $response);
        $this->assertEquals("success", $response->getStatus());
        $this->assertNotNull($response->getContract());
        $this->assertEquals(0, $response->getBalance());
        $this->assertEquals("test-shop", $response->getRequestId());
        $this->assertEquals(2, $response->getContractAmount());
        $this->assertNull($response->getMoneySource());

    }

    public function testProcessPayment()
    {
        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "success";

        $apiFacade = YandexMoney::getApiFacade();

        $response = $apiFacade->requestPaymentShop(TOKEN, $params);

        $processPaymentByWalletRequest = new ProcessPaymentByWalletRequest();
        $processPaymentByWalletRequest->setRequestId($response->getRequestId());
        $processPaymentByWalletRequest->setTestPayment("true");
        $processPaymentByWalletRequest->setTestResult("success");

        $response = $apiFacade->processPaymentByWallet(TOKEN, $processPaymentByWalletRequest);

        $this->assertEquals("refused", $response->getStatus());
        $this->assertEquals("money_source_not_available", $response->getError());
    }

    public function tearDown() {
        YandexMoney::getApiFacade()->revokeOAuthToken(TOKEN);
    }
} 