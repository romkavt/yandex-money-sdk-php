<?php

namespace YandexMoney\Request;


use YandexMoney\Presets\ApiKey;

class ProcessExternalPaymentRequest extends BaseRequest {


    /**
     * @param string $csc
     */
    public function setCsc($csc)
    {
        $this->paramsArray[ApiKey::CSC] = $csc;
    }

    /**
     * @return string
     */
    public function getCsc()
    {
        return $this->checkAndReturn(ApiKey::CSC);
    }

    /**
     * @param string $extAuthFailUri
     */
    public function setExtAuthFailUri($extAuthFailUri)
    {
        $this->paramsArray[ApiKey::EXT_AUTH_FAIL_URI] = $extAuthFailUri;
    }

    /**
     * @return string
     */
    public function getExtAuthFailUri()
    {
        return $this->checkAndReturn(ApiKey::EXT_AUTH_FAIL_URI);
    }

    /**
     * @param string $extAuthSuccessUri
     */
    public function setExtAuthSuccessUri($extAuthSuccessUri)
    {
        $this->paramsArray[ApiKey::EXT_AUTH_SUCCESS_URI] = $extAuthSuccessUri;
    }

    /**
     * @return string
     */
    public function getExtAuthSuccessUri()
    {
        return $this->checkAndReturn(ApiKey::EXT_AUTH_SUCCESS_URI);
    }

    /**
     * @param string $instanceId
     */
    public function setInstanceId($instanceId)
    {
        $this->paramsArray[ApiKey::INSTANCE_ID] = $instanceId;
    }

    /**
     * @return string
     */
    public function getInstanceId()
    {
        return $this->checkAndReturn(ApiKey::INSTANCE_ID);
    }

    /**
     * @param string $moneySourceToken
     */
    public function setMoneySourceToken($moneySourceToken)
    {
        $this->paramsArray[ApiKey::MONEY_SOURCE_TOKEN] = $moneySourceToken;
    }

    /**
     * @return string
     */
    public function getMoneySourceToken()
    {
        return $this->checkAndReturn(ApiKey::MONEY_SOURCE_TOKEN);
    }

    /**
     * @param string $requestId
     */
    public function setRequestId($requestId)
    {
        $this->paramsArray[ApiKey::REQUEST_ID] = $requestId;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->checkAndReturn(ApiKey::REQUEST_ID);
    }

    /**
     * @param string $requestToken
     */
    public function setRequestToken($requestToken)
    {
        $this->paramsArray[ApiKey::REQUEST_TOKEN] = $requestToken;
    }

    /**
     * @return string
     */
    public function getRequestToken()
    {
        return $this->checkAndReturn(ApiKey::REQUEST_TOKEN);
    }

} 