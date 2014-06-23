<?php

namespace YandexMoney\Request;


use YandexMoney\Presets\ApiKey;

class BaseRequest
{

    /**
     * @var array string
     */
    protected $paramsArray = array();

    /**
     * @return array of params
     */
    public function getDefinedParams()
    {
        return $this->paramsArray;
    }

    protected function checkAndReturn($key)
    {
        if (array_key_exists($key, $this->paramsArray)) {
            return $this->paramsArray[$key];
        } else {
            throw new \ErrorException("Key " . $key . " doesn't exists in defined params!");
        }
    }

    /**
     * @param string $testCard
     */
    public function setTestCard($testCard)
    {
        $this->paramsArray[ApiKey::TEST_CARD] = $testCard;
    }

    /**
     * @return string
     */
    public function getTestCard()
    {
        return $this->checkAndReturn(ApiKey::TEST_CARD);
    }

    /**
     * @param string $testPayment
     */
    public function setTestPayment($testPayment)
    {
        $this->paramsArray[ApiKey::TEST_PAYMENT] = $testPayment;
    }

    /**
     * @return string
     */
    public function getTestPayment()
    {
        return $this->checkAndReturn(ApiKey::TEST_PAYMENT);
    }

    /**
     * @param string $testResult
     */
    public function setTestResult($testResult)
    {
        $this->paramsArray[ApiKey::TEST_RESULT] = $testResult;
    }

    /**
     * @return string
     */
    public function getTestResult()
    {
        return $this->checkAndReturn(ApiKey::TEST_RESULT);
    }

} 