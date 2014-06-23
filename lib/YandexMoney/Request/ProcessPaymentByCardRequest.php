<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/30/14
 * Time: 9:33 PM
 */

namespace YandexMoney\Request;


use YandexMoney\Presets\ApiKey;

class ProcessPaymentByCardRequest extends BaseRequest {

    /**
     * @param mixed $csc
     */
    public function setCsc($csc)
    {
        $this->paramsArray[ApiKey::CSC] = $csc;
    }

    /**
     * @return mixed
     */
    public function getCsc()
    {
        return $this->checkAndReturn(ApiKey::CSC);
    }

    /**
     * @param mixed $extAuthSuccessUri
     */
    public function setExtAuthSuccessUri($extAuthSuccessUri)
    {
        $this->paramsArray[ApiKey::EXT_AUTH_SUCCESS_URI] = $extAuthSuccessUri;
    }

    /**
     * @return mixed
     */
    public function getExtAuthSuccessUri()
    {
        return $this->checkAndReturn(ApiKey::EXT_AUTH_SUCCESS_URI);
    }

    /**
     * @param mixed $extFailUri
     */
    public function setExtAuthFailUri($extFailUri)
    {
        $this->paramsArray[ApiKey::EXT_AUTH_FAIL_URI] = $extFailUri;
    }

    /**
     * @return mixed
     */
    public function getExtAuthFailUri()
    {
        return $this->checkAndReturn(ApiKey::EXT_AUTH_FAIL_URI);
    }

    /**
     * @param mixed $moneySource
     */
    public function setMoneySource($moneySource)
    {
        $this->paramsArray[ApiKey::MONEY_SOURCE] = $moneySource;
    }

    /**
     * @return mixed
     */
    public function getMoneySource()
    {
        return $this->checkAndReturn(ApiKey::MONEY_SOURCE);
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId)
    {
        $this->paramsArray[ApiKey::REQUEST_ID] = $requestId;
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->checkAndReturn(ApiKey::REQUEST_ID);
    }

} 