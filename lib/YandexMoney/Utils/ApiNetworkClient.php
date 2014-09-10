<?php

namespace YandexMoney\Utils;

use YandexMoney\Exception;
use YandexMoney\Response\OriginalServerResponse;

/**
 *
 */
class ApiNetworkClient
{

    /**
     *
     */
    const CERTIFICATE_PATH = '/../../data/ca-certificate.crt';

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $logFile;

    /**
     * @var bool
     */
    private $transmitRawResponse = false;

    /**
     * @var bool
     */
    private $headerRequired = false;

    /**
     * @var bool
     */
    private $sslVerificationRequired;

    /**
     * @var OriginalServerResponse
     */
    private $originalServerResponse;

    /**
     * @return bool
     */
    private function isSSLVerificationRequired()
    {
        return $this->sslVerificationRequired;
    }

    /**
     * @param boolean $headerRequired
     */
    public function toggleHeadersRequired($headerRequired = false)
    {
        $this->headerRequired = $headerRequired;
    }

    /**
     * @return boolean
     */
    public function areHeadersRequired()
    {
        return $this->headerRequired;
    }

    /**
     * @param boolean $transmitRawResponse
     */
    public function toggleTransmitRawResponse($transmitRawResponse = false)
    {
        $this->transmitRawResponse = $transmitRawResponse;
    }

    /**
     * @return boolean
     */
    public function isTransmitRawResponseEnable()
    {
        return $this->transmitRawResponse;
    }

    /**
     * @param string $accessToken
     * @param string $logFile
     * @param bool $sslVerification
     */
    public function __construct($accessToken = null, $logFile = null, $sslVerification = true)
    {
        $this->accessToken = $accessToken;
        $this->logFile = $logFile;
        $this->sslVerificationRequired = $sslVerification;
    }

    /**
     * @param string $uri
     * @param string $params
     * @return \YandexMoney\Response\OriginalServerResponse
     */
    public function request($uri, $params = null)
    {
        $this->makePostCurlRequest($uri, $params);
        $this->checkOriginalServerResponse();
        return $this->originalServerResponse;
    }

    /**
     * @param string $uri
     * @param string $params
     * @return OriginalServerResponse
     */
    private function makePostCurlRequest($uri, $params)
    {
        $headers = $this->prepareRequestHeaders();

        $curl = curl_init($uri);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if ($this->isSSLVerificationRequired()) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . self::CERTIFICATE_PATH);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 80);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $this->writeMessageToLogFile($this->prepareLogMessage($uri, $params));

        $responseRaw = curl_exec($curl);

        $errorCode = curl_errno($curl);
        $errorMessage = curl_error($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseHeaderSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeadersRaw = trim(substr($responseRaw, 0, $responseHeaderSize));
        $responseBodyRaw = trim(substr($responseRaw, $responseHeaderSize));

        curl_close($curl);

        $this->writeMessageToLogFile(
            $this->prepareLogMessageExtended($uri, $params, $responseCode, $errorCode, $errorMessage)
        );

        $this->originalServerResponse = new OriginalServerResponse();
        $this->originalServerResponse->setCode($responseCode);
        $this->originalServerResponse->setHeadersRaw($responseHeadersRaw);
        $this->originalServerResponse->setBodyRaw($responseBodyRaw);
        $this->originalServerResponse->setErrorCode($errorCode);
        $this->originalServerResponse->setErrorMessage($errorMessage);
    }

    private function checkOriginalServerResponse()
    {
        $this->checkForCurlErrors();
        $this->checkForApiErrors();
    }

    /**
     * @param int $errorCode
     * @param string $errorMessage
     *
     * @throws \YandexMoney\Exception\ApiConnectionException
     */
    private function handleCurlError($errorCode, $errorMessage)
    {
        switch ($errorCode) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Yandex.Money. Please check your internet connection and try again.";
                break;
            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify Yandex.Money's SSL certificate. Please make sure that your network is not intercepting certificates.";
                break;
            default:
                $msg = "Unexpected error communicating with Yandex.Money.";
        }

        $msg .= "\n\n(Network error: $errorMessage)";

        throw new Exception\ApiConnectionException($msg);
    }

    /**
     * @param string $responseCode
     * @param string $responseBody
     * @throws \YandexMoney\Exception\InsufficientScopeException
     * @throws \YandexMoney\Exception\InvalidTokenException
     * @throws \YandexMoney\Exception\InternalServerErrorException
     * @throws \YandexMoney\Exception\ApiException
     * @internal param string $response
     *
     */
    private function handleApiError($responseCode, $responseBody)
    {
        switch ($responseCode) {
            case 400:
                throw new Exception\ApiException('Invalid request error', $responseCode, $responseBody);
            case 401:
                throw new Exception\InvalidTokenException('Nonexistent, expired, or revoked token specified.', $responseCode, $responseBody);
            case 403:
                throw new Exception\InsufficientScopeException('The token does not have permissions for the requested operation.',
                    $responseCode, $responseBody);
            case 500:
                throw new Exception\InternalServerErrorException('It is a technical error occurs, the server responds with the HTTP code
                    500 Internal Server Error. The application should repeat the request with the same parameters later.',
                    $responseCode, $responseBody);
            default:
                throw new Exception\ApiException('Unknown API response error. You should inform your software developer.',
                    $responseCode, $responseBody);
        }
    }

    /**
     * @param string $message
     *
     * @throws \YandexMoney\Exception\Exception
     */
    private function writeMessageToLogFile($message)
    {
        $f = $this->logFile;
        if ($f !== null) {
            if (file_exists($f)) {
                if (!is_file($f)) {
                    throw new Exception\Exception("log file $f is not a file");
                }
                if (!is_writable($f)) {
                    throw new Exception\Exception("log file $f is not writable");
                }
            }

            if (!$handle = fopen($f, 'a+')) {
                throw new Exception\Exception("couldn't open log file $f for appending");
            }

            $time = '[' . date("Y-m-d H:i:s") . '] ';
            if (fwrite($handle, $time . $message . "\r\n") === false) {
                throw new Exception\Exception("couldn't fwrite message log to $f");
            }

            fclose($handle);
        }
    }

    /**
     * @param string $uri
     * @param string $params
     *
     * @return string
     */
    private function prepareLogMessage($uri, $params)
    {
        if ($this->logFile === null) {
            return '';
        }

        $m = "request: " . $uri . '; ';

        $token = $this->accessToken;
        if (isset($token)) {
            $m = $m . 'token = *' . substr($token, -4) . '; ';
        }

        parse_str($params);
        if (isset($to)) {
            $m = $m . 'param to = *' . substr($to, -4) . '; ';
        }

        if (isset($pattern_id)) {
            $m = $m . 'param pattern_id = ' . $pattern_id . '; ';
        }

        if (isset($request_id)) {
            $m = $m . 'param request_id = *' . substr($request_id, -4) . '; ';
        }

        return $m;
    }

    /**
     * @param string $uri
     * @param string $params
     * @param int $code
     * @param int $errorCode
     * @param string $errorMessage
     *
     * @return string
     */
    private function prepareLogMessageExtended($uri, $params, $code, $errorCode, $errorMessage)
    {
        if ($this->logFile === null) {
            return '';
        }

        $m = $this->prepareLogMessage($uri, $params);
        $m = str_replace('request: ', 'response: ', $m);
        $m = $m . "http_code = $code; curl_errno = $errorCode; curl_error = $errorMessage";

        return $m;
    }

    /**
     * @internal param $headers
     * @return array
     */
    private function prepareRequestHeaders()
    {
        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8;';
        if ($this->accessToken) {
            $headers[] = 'Authorization: Bearer ' . $this->accessToken;
            return $headers;
        }
        return $headers;
    }

    private function checkForCurlErrors()
    {
        if ($this->originalServerResponse->getErrorCode() != 0) {
            $this->handleCurlError(
                $this->originalServerResponse->getErrorCode(),
                $this->originalServerResponse->getErrorMessage()
            );
        }
    }

    private function checkForApiErrors()
    {
        if ($this->originalServerResponse->getCode() < 200 || $this->originalServerResponse->getCode() >= 308) {
            $this->handleApiError(
                $this->originalServerResponse->getCode(),
                $this->originalServerResponse->getBodyRaw()
            );
        }
    }
}
