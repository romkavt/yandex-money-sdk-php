<?php

require_once __DIR__ . '/consts.php';
require_once __DIR__ . '/vendor/autoload.php';

use YandexMoney\Presets\MoneySource;
use YandexMoney\Presets\PaymentIdentifier;
use YandexMoney\Presets\Rights;
use YandexMoney\Request\ExternalP2pPaymentRequest;
use YandexMoney\Request\OperationHistoryRequest;
use YandexMoney\Request\P2pPaymentRequest;
use YandexMoney\Request\ProcessExternalPaymentRequest;
use YandexMoney\Request\ProcessPaymentByCardRequest;
use YandexMoney\Request\ProcessPaymentByWalletRequest;
use YandexMoney\YandexMoney;

ini_set('display_errors', 1);
date_default_timezone_set("Europe/Moscow"); 

if (!YandexMoney::authorizationCodeReceived()) {

    $rightsConfigurator = YandexMoney::getRightsConfigurator();
    $rightsConfigurator->addRight(Rights::ACCOUNT_INFO);
    $rightsConfigurator->addRight(Rights::OPERATION_HISTORY);
    $rightsConfigurator->addRight(Rights::OPERATION_DETAILS);
    $rightsConfigurator->paymentToAccount("410011161616877", PaymentIdentifier::ACCOUNT, 0, 30);
    $rightsConfigurator->paymentToPattern("337", 0, 30);
    $rightsConfigurator->setMoneySource(MoneySource::CARD);


    $authRequestBuilder = YandexMoney::getAuthRequestBuilder();
    $authRequestBuilder->setClientId(CLIENT_ID);
    $authRequestBuilder->setRedirectUri(REDIRECT_URI);
    $authRequestBuilder->setRights($rightsConfigurator->toString());

    $apiFacade = YandexMoney::getApiFacade();
    $apiFacade->setLogFile(__DIR__ . '/ym.log');

    $originalServerResponse = null;

    try {
        $originalServerResponse = $apiFacade->authorizeApplication($authRequestBuilder);
    } catch (\Exception $e) {
        echo $e->getMessage();
        exit;
    }

    header("Location: " . $originalServerResponse->getHeader('Location'));

} else {
    $authCode = YandexMoney::getAuthorizationCode();

    $apiFacade = YandexMoney::getApiFacade();
    $apiFacade->setClientId(CLIENT_ID);
    $oAuthTokenResponse = $apiFacade->getOAuthToken($authCode, REDIRECT_URI, CLIENT_SECRET);

    $oAuthToken = "";
    $tokenError = null;
    if ($oAuthTokenResponse->isSuccess()) {
        $oAuthToken = $oAuthTokenResponse->getAccessToken();
    } else {
        $tokenError = $oAuthTokenResponse->getError();
    }

    ?>

    <html>
    <head>
        <title>Yandex Money PHP SDK sample</title>
    </head>
    <body>
    <h1>Yandex Money PHP SDK sample</h1>
    <?php if ($tokenError != null) : ?>
        <h2>Error: <?= $tokenError ?></h2>
    <?php else : ?>
        <h2>Success</h2>
        <p>Your token is: <span style="color: red"><?= $oAuthToken ?></span></p>

        <h3>Operations history</h3>
        <?php
        $operationHistoryRequest = new OperationHistoryRequest();
        $operationHistoryRequest->setStartRecord(0);
        $operationHistoryRequest->setRecords(3);

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $response = null;
        $operationCount = -1;
        try {
            $response = $apiFacade->operationHistory($oAuthToken, $operationHistoryRequest);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

        <ul>
            <?php foreach ($response->getOperations() as $operationDetails) : ?>
                <li><?= $operationDetails->getOperationId() . " " . $operationDetails->getTitle() ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Operation details</h3>

        <?php
        try {
            $operationDetails = $apiFacade->operationDetail($oAuthToken, "OPERATION_ID");

            if ($operationDetails->isSuccess()) {
                echo "<p>Operation: " . $operationDetails->getOperationId() . " " . $operationDetails->getTitle(
                    ) . "</p>";
            } else {
                echo "<p>Operation details error: " . $operationDetails->getError() . "</p>";
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }?>

        <h3>Account info</h3>

        <?php

        $accountInfo = null;
        try {
            $accountInfo = $apiFacade->accountInfo($oAuthToken);

            if ($accountInfo->isSuccess()) {
                echo "<p>Account info: " . $accountInfo->getAccount() . " " . $accountInfo->getAccountStatus() . "</p>";
            } else {
                echo "<p>Account info error: " . $accountInfo->getError() . "</p>";
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

        <h3>Process payment</h3>

        <?php
        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "success";

        $response = null;
        try {
            $response = $apiFacade->requestPaymentShop($oAuthToken, $params);
            $processPaymentByWalletRequest = new ProcessPaymentByWalletRequest();
            $processPaymentByWalletRequest->setRequestId($response->getRequestId());
            $processPaymentByWalletRequest->setTestPayment("true");
            $processPaymentByWalletRequest->setTestResult("success");
            $response = $apiFacade->processPaymentByWallet($oAuthToken, $processPaymentByWalletRequest);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        ?>

        <h3>Process p2p payment</h3>

        <?php
        $p2pPaymentRequest = new P2pPaymentRequest();
        $p2pPaymentRequest->setTo("410011161616877");
        $p2pPaymentRequest->setAmount("0.05");
        $p2pPaymentRequest->setComment("Comment");
        $p2pPaymentRequest->setMessage("Message");

        $p2pPaymentRequest->setTestPayment(true);
        $p2pPaymentRequest->setTestResult('success');

        $response = null;
        try {
            ;
            $response = $apiFacade->requestPaymentP2P($oAuthToken, $p2pPaymentRequest);
            $processPaymentByWalletRequest = new ProcessPaymentByWalletRequest();
            $processPaymentByWalletRequest->setRequestId($response->getRequestId());
            $response = $apiFacade->processPaymentByWallet($oAuthToken, $processPaymentByWalletRequest);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

        <h3>Payment by card</h3>

        <?php

        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "success";

        $response = null;

        try {
            $response = $apiFacade->requestPaymentShop($oAuthToken, $params);
            $requestId = $response->getRequestId();

            $processPaymentByCardRequest = new ProcessPaymentByCardRequest();
            $processPaymentByCardRequest->setRequestId($requestId);
            $processPaymentByCardRequest->setMoneySource('1222233334444555');
            $processPaymentByCardRequest->setCsc('222');
            $processPaymentByCardRequest->setExtAuthSuccessUri('http://example.com/success');
            $processPaymentByCardRequest->setExtAuthFailUri('http://example.com/fail');

            $response = $apiFacade->processPaymentByCard($oAuthToken, $processPaymentByCardRequest);

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

        <h3>Incoming transfer accept</h3>

        <?php
        try {
            $incomingTransferAcceptResponse = $apiFacade->incomingTransferAccept(
                $oAuthToken,
                "OPERATION_ID",
                "PROTECTION_CODE"
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

        <h3>Incoming transfer reject</h3>

        <?php
        try {
            $incomingTransferRejectResponse = $apiFacade->incomingTransferReject($oAuthToken, "OPERATION_ID");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

        <h3>Revoke token</h3>

        <?php
        try {
            $wasRevoked = $apiFacade->revokeOAuthToken($oAuthToken);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

        <h3>Instance id</h3>

        <?php
        $instanceIdResponse = null;
        try {
            $instanceIdResponse = $apiFacade->getInstanceId(CLIENT_ID);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

        <h3>Process external payment</h3>
        <?php
        try {

            $instanceIdResponse = $apiFacade->getInstanceId(CLIENT_ID);

            $externalPaymentP2pRequest = new ExternalP2pPaymentRequest();
            $externalPaymentP2pRequest->setTo("410011161616877");
            $externalPaymentP2pRequest->setAmount("20.50");
            $externalPaymentP2pRequest->setMessage("Message");

            $externalPaymentResponse = $apiFacade->requestExternalPaymentP2p(
                $instanceIdResponse->getInstanceId(),
                $externalPaymentP2pRequest
            );

            $processExternalPaymentRequest = new ProcessExternalPaymentRequest();
            $processExternalPaymentRequest->setRequestId($externalPaymentResponse->getRequestId());
            $processExternalPaymentRequest->setInstanceId($instanceIdResponse->getInstanceId());
            $processExternalPaymentRequest->setExtAuthSuccessUri("http://somewhere.com/success");
            $processExternalPaymentRequest->setExtAuthFailUri("http://somewhere.com/fail");

            $externalProcessPaymentResponse = $apiFacade->processExternalPayment($processExternalPaymentRequest);

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        ?>

    <?php endif // token received ?>
    </body>
    </html>

<?php
}
