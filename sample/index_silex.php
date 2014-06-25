<?php
/**
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/2/14
 * Time: 9:12 PM
 */

ini_set('display_errors', 1);

require_once __DIR__ . '/../sample/consts.php';
date_default_timezone_set("Europe/Moscow");

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->add('YandexMoney', __DIR__ . '/../lib/');

$app = new Silex\Application();

$app->register(
    new Silex\Provider\SessionServiceProvider()
);
$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => __DIR__ . '/views',
    )
);

$app->get(
    '/',
    function () use ($app) {

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
        }

        return new Response('', $originalServerResponse->getCode(
        ), array('Location' => $originalServerResponse->getHeader('Location')));
    }
);

$app->get(
    '/get-token',
    function (Request $request) use ($app) {

        $code = $request->query->get('code');
        $error = $request->query->get('error');


        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setClientId(CLIENT_ID);
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $oAuthTokenResponse = null;
        try {
            $oAuthTokenResponse = $apiFacade->getOAuthToken($code, REDIRECT_URI, CLIENT_SECRET);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $result = "Empty result";
        if ($oAuthTokenResponse != null) {
            if ($oAuthTokenResponse->isSuccess()) {
                $result = $oAuthTokenResponse->getAccessToken();
                $app['session']->set('token', $oAuthTokenResponse->getAccessToken());
            } else {
                $result = $oAuthTokenResponse->getError();
            }


        }

        return $app['twig']->render(
            'get_token.twig',
            array(
                'result' => $result,
            )
        );
    }
);

$app->get(
    '/operation-history',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $operationHistoryRequest = new OperationHistoryRequest();
        $operationHistoryRequest->setStartRecord(0);
        $operationHistoryRequest->setRecords(3);

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $response = null;
        $operationCount = -1;
        try {
            $response = $apiFacade->operationHistory($token, $operationHistoryRequest);

            $operationCount = count($response->getOperations());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return new Response(http_build_query(array("operations_amount" => "$operationCount")), 200);
    }
);

$app->get(
    '/operation-details',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');
        $operationId = $request->query->get('operation_id');

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $operationDetails = null;
        try {
            $operationDetails = $apiFacade->operationDetail($token, $operationId);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return new Response(http_build_query($operationDetails->getDefinedParams()), 200);
    }
);


$app->get(
    '/account-info',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $accountInfo = null;
        try {
            $accountInfo = $apiFacade->accountInfo($token);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return new Response(http_build_query($accountInfo->getDefinedParams()), 200);
    }
);

$app->get(
    '/request-payment',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "success";


        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $response = null;

        try {
            $response = $apiFacade->requestPaymentShop($token, $params);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return new Response(http_build_query($response->getDefinedParams()), 200);
    }
);

$app->get(
    '/process-payment',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "success";


        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $response = null;
        try {
            $response = $apiFacade->requestPaymentShop($token, $params);

            $processPaymentByWalletRequest = new ProcessPaymentByWalletRequest();
            $processPaymentByWalletRequest->setRequestId($response->getRequestId());
            $processPaymentByWalletRequest->setTestPayment("true");
            $processPaymentByWalletRequest->setTestResult("success");

            $response = $apiFacade->processPaymentByWallet($token, $processPaymentByWalletRequest);

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return new Response(http_build_query($response->getDefinedParams()), 200);
    }
);

$app->get(
    '/request-payment-error',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "illegal_param_amount_due";


        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $response = null;

        try {
            $response = $apiFacade->requestPaymentShop($token, $params);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return new Response(http_build_query($response->getDefinedParams()), 200);
    }
);

$app->get(
    '/process-payment-error',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "success";


        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $response = null;
        try {
            $response = $apiFacade->requestPaymentShop($token, $params);

            $processPaymentByWalletRequest = new ProcessPaymentByWalletRequest();
            $processPaymentByWalletRequest->setRequestId($response->getRequestId());
            $processPaymentByWalletRequest->setTestPayment("true");
            $processPaymentByWalletRequest->setTestResult("illegal_param_amount_due");

            $response = $apiFacade->processPaymentByWallet($token, $processPaymentByWalletRequest);

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return new Response(http_build_query($response->getDefinedParams()), 200);
    }
);

$app->get(
    '/p2p-payment',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $p2pPaymentRequest = new P2pPaymentRequest();
        $p2pPaymentRequest->setTo("410011161616877");
        $p2pPaymentRequest->setAmount("0.05");
        $p2pPaymentRequest->setComment("Comment");
        $p2pPaymentRequest->setMessage("Message");

        $p2pPaymentRequest->setTestPayment(true);
        $p2pPaymentRequest->setTestResult('success');

        $response = null;
        try {
            $apiFacade = YandexMoney::getApiFacade();
            $apiFacade->setLogFile(__DIR__ . '/ym.log');
            $response = $apiFacade->requestPaymentP2P($token, $p2pPaymentRequest);

            $processPaymentByWalletRequest = new ProcessPaymentByWalletRequest();
            $processPaymentByWalletRequest->setRequestId($response->getRequestId());

            $response = $apiFacade->processPaymentByWallet($token, $processPaymentByWalletRequest);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $result = 'Empty result';

        if ($response != null) {
            $result = ($response->isSuccess()) ? $response->getStatus() : $response->getError();
        }

        return $app['twig']->render(
            'p2p-payment.twig',
            array(
                'result' => $result
            )
        );
    }
);

$app->get(
    '/shop-payment',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $params = array();
        $params["pattern_id"] = "337";
        $params["property_1"] = "921";
        $params["property_2"] = "3020052";
        $params["sum"] = "2.00";

        $params["test_payment"] = "true";
        $params["test_result"] = "success";


        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $response = null;

        try {
            $response = $apiFacade->requestPaymentShop($token, $params);
            $requestId = $response->getRequestId();

            $processPaymentByCardRequest = new ProcessPaymentByCardRequest();
            $processPaymentByCardRequest->setRequestId($requestId);
            $processPaymentByCardRequest->setMoneySource('1222233334444555');
            $processPaymentByCardRequest->setCsc('222');
            $processPaymentByCardRequest->setExtAuthSuccessUri('http://abracadabra.fafa.by/success');
            $processPaymentByCardRequest->setExtAuthFailUri('http://abracadabra.fafa.by/fail');

            $response = $apiFacade->processPaymentByCard($token, $processPaymentByCardRequest);

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $result = 'Empty result';
        if ($response != null) {
            $result = ($response->isSuccess()) ? 'status=' . $response->getStatus() : 'error=' . $response->getError();
        }

        return new Response($result, 200);
    }
);

$app->get(
    '/incoming-transfer-accept',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $incomingTransferAcceptResponse = null;
        try {
            $incomingTransferAcceptResponse = $apiFacade->incomingTransferAccept($token, "1234567", "0123");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $result = 'Empty result';
        if ($incomingTransferAcceptResponse != null) {
            $result = ($incomingTransferAcceptResponse->isSuccess()) ? $incomingTransferAcceptResponse->getStatus()
                : $incomingTransferAcceptResponse->getError();
        }
        return $result;
    }
);

$app->get(
    '/incoming-transfer-reject',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $incomingTransferRejectResponse = null;
        try {
            $incomingTransferRejectResponse = $apiFacade->incomingTransferReject($token, "1234567");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $result = 'Empty result';
        if ($incomingTransferRejectResponse != null) {
            $result = ($incomingTransferRejectResponse->isSuccess()) ? $incomingTransferRejectResponse->getStatus()
                : $incomingTransferRejectResponse->getError();
        }
        return $result;
    }
);

$app->get(
    '/instance-id',
    function (Request $request) use ($app) {

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $instanceIdResponse = null;
        try {
            $instanceIdResponse = $apiFacade->getInstanceId(CLIENT_ID);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $instanceIdResponse->getInstanceId();
    }
);


$app->get(
    '/request-external-payment',
    function (Request $request) use ($app) {

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $externalPaymentResponse = null;
        $externalProcessPaymentResponse = null;
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

        $response = array();

        if ($externalProcessPaymentResponse != null) {
            $response['result'] = ($externalProcessPaymentResponse->isSuccess(
            )) ? $externalProcessPaymentResponse->getStatus() : $externalProcessPaymentResponse->getError();
        }

        return $app->json(
            $response
        );

    }
);

$app->get(
    '/revoke-token',
    function (Request $request) use ($app) {

        $token = $app['session']->get('token');

        $apiFacade = YandexMoney::getApiFacade();
        $apiFacade->setLogFile(__DIR__ . '/ym.log');

        $wasRevoked = false;
        try {
            $wasRevoked = $apiFacade->revokeOAuthToken($token);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $app['session']->set('token', null);

        return $app['twig']->render(
            'revoke-token.twig',
            array(
                'token_revoked' => $wasRevoked
            )
        );
    }
);

