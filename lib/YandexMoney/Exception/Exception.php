<?php

namespace YandexMoney\Exception;

/**
 * 
 */
class Exception extends \Exception
{
    /**
     * @param string $message
     * @param int $httpStatus
     * @param string $httpBody
     * @param string $jsonBody
     */
    public function __construct($message = null, $httpStatus = null, $httpBody = null, $jsonBody = null)
    {
        parent::__construct($message);

        $this->httpStatus = $httpStatus;
        $this->httpBody = $httpBody;
        $this->jsonBody = $jsonBody;
    }

    /**
     * @return string
     */
    public function getHttpBody()
    {
        return $this->httpBody;
    }

    /**
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * @return string
     */
    public function getJsonBody()
    {
        return $this->jsonBody;
    }
}