<?php

namespace YandexMoney\Utils;

use YandexMoney\Domain\AccountInfo;
use YandexMoney\Domain\OperationDetails;
use YandexMoney\Exception as Exceptions;
use YandexMoney\Presets\ApiKey;
use YandexMoney\Presets\ApiValue;
use YandexMoney\Presets\Uri;
use YandexMoney\Request\ExternalP2pPaymentRequest;
use YandexMoney\Request\OperationHistoryRequest;
use YandexMoney\Request\P2pPaymentRequest;
use YandexMoney\Request\ProcessExternalPaymentRequest;
use YandexMoney\Request\ProcessPaymentByCardRequest;
use YandexMoney\Request\ProcessPaymentByWalletRequest;
use YandexMoney\Response as Responses;
use YandexMoney\Response\InstanceIdResponse;

/**
 * Class ApiFacade {@link http://api.yandex.ru/money/doc/dg/concepts/About.xml }
 * @package YandexMoney\Utils
 */
class ApiFacade
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $logFile;

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        self::validateClientId($clientId);
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $logFile
     */
    public function setLogFile($logFile)
    {
        $this->logFile = $logFile;
    }

    /**
     * @return string
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     * This method provides authorization of your application on Yandex server. After invoking it will provide redirect
     * to Yandex authorization web page
     * @param Object | \YandexMoney\Utils\AuthRequestBuilder $authRequest instance of AuthRequestBuilder required
     * @return \YandexMoney\Response\OriginalServerResponse
     */
    public function authorizeApplication(AuthRequestBuilder $authRequest)
    {
        $postParamsArray = array();

        $postParamsArray[ApiKey::CLIENT_ID] = $authRequest->getClientId();
        $postParamsArray[ApiKey::RESPONSE_TYPE] = AuthRequestBuilder::RESPONSE_TYPE;
        $postParamsArray[ApiKey::REDIRECT_URI] = $authRequest->getRedirectUri();
        $postParamsArray[ApiKey::SCOPE] = $authRequest->getRights();

        $apiNetworkClient = new ApiNetworkClient(null, null, false);
        return $apiNetworkClient->request(Uri::AUTH, http_build_query($postParamsArray));
    }

    /**
     * @param string $code
     * @param string $redirectUri
     * @param string $clientSecret
     * @return \YandexMoney\Response\ReceiveTokenResponse
     */
    public function getOAuthToken($code, $redirectUri, $clientSecret = null)
    {
        $paramArray[ApiKey::GRANT_TYPE] = ApiValue::AUTHORIZATION_CODE;
        $paramArray[ApiKey::CLIENT_ID] = $this->clientId;
        $paramArray[ApiKey::CODE] = $code;
        $paramArray[ApiKey::REDIRECT_URI] = $redirectUri;

        if (isset($clientSecret)) {
            $paramArray[ApiKey::CLIENT_SECRET] = $clientSecret;
        }

        $apiNetworkClient = new ApiNetworkClient(null, null, false);
        $response = $apiNetworkClient->request(Uri::TOKEN, http_build_query($paramArray));

        return new Responses\ReceiveTokenResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param string $accessToken
     * @return boolean
     */
    public function revokeOAuthToken($accessToken)
    {
        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $apiNetworkClient->request($this->getApiUri(Uri::REVOKE));

        return true;
    }

    /**
     * @param string $accessToken
     * @return \YandexMoney\Domain\AccountInfo
     */
    public function accountInfo($accessToken)
    {
        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::ACCOUNT_INFO));

        return new AccountInfo($response->getBodyJsonDecoded());
    }

    /**
     * @param string $accessToken
     * @param \YandexMoney\Request\OperationHistoryRequest $operationHistoryRequest
     * @return \YandexMoney\Response\OperationHistoryResponse
     */

    public function operationHistory($accessToken, OperationHistoryRequest $operationHistoryRequest)
    {
        $postParamsArray = $operationHistoryRequest->getDefinedParams();

        if (count($postParamsArray) > 0) {
            $params = http_build_query($postParamsArray);
        } else {
            $params = '';
        }

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::OPERATION_HISTORY), $params);

        return new Responses\OperationHistoryResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param string $accessToken
     * @param string $operationId
     * @return \YandexMoney\Domain\OperationDetails
     */
    public function operationDetail($accessToken, $operationId)
    {
        $paramArray[ApiKey::OPERATION_ID] = $operationId;
        $params = http_build_query($paramArray);

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::OPERATION_DETAILS), $params);

        return new OperationDetails($response->getBodyJsonDecoded());
    }


    /**
     * @param string $accessToken
     * @param string $shopParams
     * @return \YandexMoney\Response\RequestPaymentResponse
     */
    public function requestPaymentShop($accessToken, $shopParams)
    {
        $params = http_build_query($shopParams);
        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::REQUEST_PAYMENT), $params);

        return new Responses\RequestPaymentResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param string $accessToken
     * @param \YandexMoney\Request\P2pPaymentRequest $p2pPaymentRequest
     * @return \YandexMoney\Response\RequestPaymentResponse
     */
    public function requestPaymentP2P($accessToken, P2pPaymentRequest $p2pPaymentRequest)
    {
        $paramArray = $this->prepareRequestPaymentP2pParams($p2pPaymentRequest);
        $params = http_build_query($paramArray);
        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::REQUEST_PAYMENT), $params);

        return new Responses\RequestPaymentResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param $accessToken string
     * @param string $phoneNumber
     * @param float $amount
     * @return \YandexMoney\Response\RequestPaymentResponse
     */
    public function requestPaymentPhone($accessToken, $phoneNumber, $amount)
    {

        $paramArray = array();
        $paramArray[ApiKey::PATTERN_ID] = ApiValue::PHONE_TOPUP;
        $paramArray[ApiKey::PHONE_NUMBER] = $phoneNumber;
        $paramArray[ApiKey::AMOUNT] = $amount;

        $params = http_build_query($paramArray);

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::REQUEST_PAYMENT), $params);

        return new Responses\RequestPaymentResponse($response->getBodyJsonDecoded());
    }

    public function requestPaymentTest($accessToken, $testResult, $testCard = null)
    {

        $paramArray = array();
        $paramArray[ApiKey::TEST_PAYMENT] = true;
        $paramArray[ApiKey::TEST_RESULT] = $testResult;

        $this->putIfNotNull($testCard, $paramArray, ApiKey::TEST_CARD);

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::REQUEST_PAYMENT), $paramArray);

        return new Responses\RequestPaymentResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param string $accessToken
     * @param \YandexMoney\Request\ProcessPaymentByWalletRequest $paymentByWalletRequest
     * @internal param string|\YandexMoney\Request\ProcessPaymentByWalletRequest $requestId
     * @return \YandexMoney\Response\ProcessPaymentResponse
     */
    public function processPaymentByWallet($accessToken, ProcessPaymentByWalletRequest $paymentByWalletRequest)
    {

        $paramArray = $paymentByWalletRequest->getDefinedParams();
        $paramArray[ApiKey::MONEY_SOURCE] = ApiValue::WALLET;

        $params = http_build_query($paramArray);

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::PROCESS_PAYMENT), $params);

        return new Responses\ProcessPaymentResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param string $accessToken
     * @param \YandexMoney\Request\ProcessPaymentByCardRequest $processPaymentByCardRequest
     * @internal param string $requestId
     * @internal param string $csc
     * @return \YandexMoney\Response\ProcessPaymentResponse
     */
    public function processPaymentByCard($accessToken, ProcessPaymentByCardRequest $processPaymentByCardRequest)
    {
        $paramArray = array();
        $paramArray[ApiKey::REQUEST_ID] = $processPaymentByCardRequest->getRequestId();
        $paramArray[ApiKey::MONEY_SOURCE] = $processPaymentByCardRequest->getMoneySource();

        $this->putIfNotNull($processPaymentByCardRequest->getCsc(), $paramArray, ApiKey::CSC);
        $this->putIfNotNull(
            $processPaymentByCardRequest->getExtAuthSuccessUri(),
            $paramArray,
            ApiKey::EXT_AUTH_SUCCESS_URI
        );

        $this->putIfNotNull($processPaymentByCardRequest->getExtAuthFailUri(), $paramArray, ApiKey::EXT_AUTH_FAIL_URI);

        if ($processPaymentByCardRequest->getTestPayment() == true) {
            $paramArray[ApiKey::TEST_PAYMENT] = true;
            $paramArray[ApiKey::TEST_RESULT] = $processPaymentByCardRequest->getTestResult();
        }

        $params = http_build_query($paramArray);

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::PROCESS_PAYMENT), $params);

        return new Responses\ProcessPaymentResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param $accessToken string
     * @param $requestId string
     * @return Responses\ProcessPaymentResponse
     */
    public function processPaymentAfter3dSecureAuth($accessToken, $requestId)
    {
        $paramArray = array();
        $paramArray[ApiKey::REQUEST_ID] = $requestId;

        $params = http_build_query($paramArray);

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::PROCESS_PAYMENT), $params);

        return new Responses\ProcessPaymentResponse($response->getBodyJsonDecoded());
    }


    /**
     * @param $accessToken string
     * @param $operationId string
     * @param $protectionCode string
     * @return Responses\IncomingTransferAcceptResponse
     */
    public function incomingTransferAccept($accessToken, $operationId, $protectionCode)
    {
        $paramArray = array();
        $paramArray[ApiKey::OPERATION_ID] = $operationId;
        $paramArray[ApiKey::PROTECTION_CODE] = $protectionCode;

        $params = http_build_query($paramArray);

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::INCOMING_TRANSFER_ACCEPT), $params);

        return new Responses\IncomingTransferAcceptResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param $accessToken string
     * @param $operationId string
     * @return Responses\IncomingTransferRejectResponse
     */
    public function incomingTransferReject($accessToken, $operationId)
    {
        $paramArray = array();
        $paramArray[ApiKey::OPERATION_ID] = $operationId;

        $params = http_build_query($paramArray);

        $apiNetworkClient = new ApiNetworkClient($accessToken, $this->logFile);
        $response = $apiNetworkClient->request($this->getApiUri(Uri::INCOMING_TRANSFER_REJECT), $params);

        return new Responses\IncomingTransferRejectResponse($response->getBodyJsonDecoded());
    }

    /**
     * @param string $clientId application identifier
     * @return InstanceIdResponse
     */
    public function getInstanceId($clientId)
    {

        $postParamsArray = array();
        $postParamsArray[ApiKey::CLIENT_ID] = $clientId;

        $apiNetworkClient = new ApiNetworkClient(null, $this->logFile, false);
        $response = $apiNetworkClient->request(
            $this->getApiUri(Uri::INSTANCE_ID),
            http_build_query($postParamsArray),
            true
        );

        return new Responses\InstanceIdResponse($response->getBodyJsonDecoded());
    }

    public function requestExternalPaymentShop($patternId, $instanceId, array $userExtraParams = array())
    {
        $postParamsArray = array();
        $postParamsArray[ApiKey::PATTERN_ID] = $patternId;
        $postParamsArray[ApiKey::INSTANCE_ID] = $instanceId;

        $postParamsArray = array_merge($userExtraParams);
        $apiNetworkClient = new ApiNetworkClient();
        $apiResponse = $apiNetworkClient->request(
            $this->getApiUri(Uri::REQUEST_EXTERNAL_PAYMENT),
            http_build_query($postParamsArray),
            true
        );

        return new Responses\ExternalPaymentResponse($apiResponse->getBodyJsonDecoded());
    }

    public function requestExternalPaymentP2p($instanceId, ExternalP2pPaymentRequest $externalP2pPaymentRequest)
    {
        $postParamsArray = array();
        $postParamsArray[ApiKey::PATTERN_ID] = ApiValue::P2P;
        $postParamsArray[ApiKey::INSTANCE_ID] = $instanceId;
        $postParamsArray[ApiKey::TO] = $externalP2pPaymentRequest->getTo();
        $postParamsArray[ApiKey::MESSAGE] = $externalP2pPaymentRequest->getMessage();
        if ($externalP2pPaymentRequest->getAmount() > 0) {
            $postParamsArray[ApiKey::AMOUNT] = $externalP2pPaymentRequest->getAmount();
        } else {
            if ($externalP2pPaymentRequest->getAmountDue() > 0) {
                $postParamsArray[ApiKey::AMOUNT_DUE] = $externalP2pPaymentRequest->getAmountDue();
            } else {
                throw new \ErrorException ("Sum for payment must be more than 0! Please check your params");
            }
        }

        $apiNetworkClient = new ApiNetworkClient();

        $apiResponse = $apiNetworkClient->request(
            $this->getApiUri(Uri::REQUEST_EXTERNAL_PAYMENT),
            http_build_query($postParamsArray)
        );

        return new Responses\ExternalPaymentResponse($apiResponse->getBodyJsonDecoded());
    }

    /**
     * @param ProcessExternalPaymentRequest $processExternalPaymentRequest
     * @return Responses\ExternalProcessPaymentResponse
     */
    public function processExternalPayment(ProcessExternalPaymentRequest $processExternalPaymentRequest)
    {

        $postParamsArray = $processExternalPaymentRequest->getDefinedParams();
        $apiNetworkClient = new ApiNetworkClient();
        $apiResponse = $apiNetworkClient->request(
            $this->getApiUri(Uri::PROCESS_EXTERNAL_PAYMENT),
            http_build_query($postParamsArray)
        );

        return new Responses\ExternalProcessPaymentResponse($apiResponse->getBodyJsonDecoded());
    }

    /**
     * @param ProcessExternalPaymentRequest $processExternalPaymentRequest
     * @return Responses\ExternalProcessPaymentResponse
     */
    public function processExternalPaymentWithToken(ProcessExternalPaymentRequest $processExternalPaymentRequest)
    {

        $postParamsArray = $processExternalPaymentRequest->getDefinedParams();
        $apiNetworkClient = new ApiNetworkClient();
        $apiResponse = $apiNetworkClient->request(
            $this->getApiUri(Uri::PROCESS_EXTERNAL_PAYMENT),
            http_build_query($postParamsArray)
        );

        return new Responses\ExternalProcessPaymentResponse($apiResponse->getBodyJsonDecoded());
    }

    /**
     * Prepare full api request Uri
     * @param $uri
     * @return string
     */
    private function getApiUri($uri)
    {
        return Uri::API . $uri;
    }

    /**
     * @param $value
     * @param array $targetArray
     * @param null $index
     */
    public function putIfNotNull($value, array &$targetArray, $index = null)
    {
        if ($value !== null) {
            if ($index == null) {
                array_push($targetArray, $value);
            } else {
                $targetArray[$index] = $value;
            }
        }

    }


    /**
     * @param string $clientId
     * @throws \YandexMoney\Exception\Exception
     */
    private static function validateClientId($clientId)
    {
        if (($clientId == null) || ($clientId === '')) {
            throw new Exceptions\Exception('You must pass a valid application client_id');
        }
    }

    /**
     * @param P2pPaymentRequest $p2pPaymentRequest
     * @return array
     */
    private function prepareRequestPaymentP2pParams(P2pPaymentRequest $p2pPaymentRequest)
    {
        $paramArray = array();
        $paramArray[ApiKey::PATTERN_ID] = ApiValue::P2P;
        $paramArray[ApiKey::TO] = $p2pPaymentRequest->getTo();

        if ($p2pPaymentRequest->isAmountUsed()) {
            $paramArray[ApiKey::AMOUNT] = $p2pPaymentRequest->getAmount();
        }

        if ($p2pPaymentRequest->isAmountDueUsed()) {
            $paramArray[ApiKey::AMOUNT_DUE] = $p2pPaymentRequest->getAmountDue();
        }

        $paramArray[ApiKey::COMMENT] = $p2pPaymentRequest->getComment();
        $paramArray[ApiKey::MESSAGE] = $p2pPaymentRequest->getMessage();

        $this->putIfNotNull($p2pPaymentRequest->getLabel(), $paramArray, ApiKey::LABEL);
        $this->putIfNotNull($p2pPaymentRequest->getCodepro(), $paramArray, ApiKey::CODEPRO);
        $this->putIfNotNull($p2pPaymentRequest->getExpirePeriod(), $paramArray, ApiKey::EXPIRE_PERIOD);

        $this->putIfNotNull($p2pPaymentRequest->getTestPayment(), $paramArray, ApiKey::TEST_PAYMENT);
        $this->putIfNotNull($p2pPaymentRequest->getTestCard(), $paramArray, ApiKey::TEST_CARD);
        $this->putIfNotNull($p2pPaymentRequest->getTestResult(), $paramArray, ApiKey::TEST_RESULT);

        return $paramArray;
    }
}
