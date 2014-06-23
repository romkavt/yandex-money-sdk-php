<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/4/14
 * Time: 8:51 PM
 */

namespace YandexMoney\Response;

use YandexMoney\Exception\ApiException;

class OriginalServerResponse
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $headersRaw;

    /**
     * @var array string
     */
    private $headersArray;

    /**
     * @var string
     */
    private $bodyRaw;

    /**
     * @var array
     */
    private $bodyJsonDecoded;

    /**
     * @var int
     */
    private $errorCode;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @throws \ErrorException
     * @throws \YandexMoney\Exception\ApiException
     * @return array
     */
    public function getBodyJsonDecoded()
    {
        $this->checkBodyRaw();
        $this->decodeJsonToArray();
        return $this->bodyJsonDecoded;
    }

    /**
     * @param $bodyRaw
     */
    public function setBodyRaw($bodyRaw)
    {
        $this->bodyRaw = $bodyRaw;
    }

    /**
     * @return string
     */
    public function getBodyRaw()
    {
        return $this->bodyRaw;
    }

    /**
     * @param $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return array
     */
    public function getHeadersArray()
    {
        return $this->headersArray;
    }

    /**
     * @param $name
     * @return null| string
     */
    public function getHeader($name)
    {
        $headerValue = null;
        if (is_array($this->headersArray) && array_key_exists($name, $this->headersArray)) {
            $headerValue = $this->headersArray[$name];
        }
        return $headerValue;
    }

    /**
     * @param $headersRaw
     */
    public function setHeadersRaw($headersRaw)
    {
        $this->headersRaw = $headersRaw;
        $this->parseHeadersToArray();
    }

    /**
     * @return string
     */
    public function getHeadersRaw()
    {
        return $this->headersRaw;
    }

    private function checkBodyRaw()
    {
        if (empty($this->bodyRaw)) {
            throw new ApiException("Nothing to decode in response body from API: {$this->bodyRaw} (HTTP response code was {$this->code})", $this->code, $this->bodyRaw);
        }
    }

    private function decodeJsonToArray()
    {
        try {
            $this->bodyJsonDecoded = json_decode($this->bodyRaw, true);
        } catch (\Exception $e) {
            throw new ApiException("Invalid response body from API: {$this->bodyRaw} (HTTP response code was {$this->code})", $this->code, $this->bodyRaw);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \ErrorException("Json parsing error with json_last_error code = " . json_last_error(
                ), $this->code, $this->bodyRaw);
        }
    }

    private function parseHeadersToLinesArrays()
    {
        $dividedHeadersArray = explode("\n", $this->headersRaw);
        return $dividedHeadersArray;
    }

    private function parseHeadersToArray()
    {
        $this->headersArray = array();

        $dividedHeadersArray = $this->parseHeadersToLinesArrays();

        foreach ($dividedHeadersArray as $header) {
            if (stripos($header, 'HTTP/') === false && !empty($header)) {
                $headerDivided = explode(":", trim($header), 2);
                if (count($headerDivided) > 1) {
                    $this->headersArray[trim($headerDivided[0])] = trim($headerDivided[1]);
                }
            }
        }
        return $this->headersArray;
    }
} 