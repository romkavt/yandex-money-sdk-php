<?php

class YM_ApiRequestor {

    const YM_USER_AGENT = 'yamolib-php';

    private $accessToken;

    public function __construct($accessToken = null) {
        $this->accessToken = $accessToken;
    }

    public function request($uri, $params = null) {
        list($rbody, $rcode) = $this->_curlRequest($uri, $params);
        return $this->_interpretResponse($rbody, $rcode);
    }

    private function _curlRequest($uri, $params) {
        $curl = curl_init($uri);

        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8;';
        if ($this->accessToken)
            $headers[] = 'Authorization: Bearer ' . $this->accessToken;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_USERAGENT, self::YM_USER_AGENT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //        curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 80);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        //        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/../data/ca-certificate.crt');

        $rbody = curl_exec($curl);
        if ($rbody === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->_handleCurlError($errno, $message);
        }

        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return array($rbody, $rcode);
    }

    private function _interpretResponse($rbody, $rcode) {
        if ($rcode < 200 || $rcode >= 300) {
            $this->_handleApiError($rbody, $rcode, $resp);
        }

        try {
            $resp = json_decode($rbody, true);            
        } catch (Exception $e) {
            throw new YM_ApiError("Invalid response body from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
        }    

        // Only for PHP 5 >= 5.3.0
        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     throw new YM_ApiError("Json parsing error with json_last_error code = " . json_last_error(), $rcode, $rbody);
        // }

        if ($resp === null) {
            throw new YM_ApiError("Server response body is null: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
        }
        
        return $resp;
    }

    private function _handleCurlError($errno, $message) {
        switch ($errno) {
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

        $msg .= "\n\n(Network error: $message)";
        throw new YM_ApiConnectionError($msg);
    }

    private function _handleApiError($rbody, $rcode, $resp) {
        switch ($rcode) {
            case 400:
                throw new YM_ApiError("Invalid request error", $rcode, $rbody, $resp);
            case 401:
                throw new YM_InvalidTokenError("Nonexistent, expired, or revoked token specified.", $rcode, $rbody, $resp);
            case 403:
                throw new YM_InsufficientScopeError("The token does not have permissions for the requested operation.",
                    $rcode, $rbody, $resp);
            case 500:
                throw new YM_InternalServerError("It is a technical error occurs, the server responds with the HTTP code
                    500 Internal Server Error. The application should repeat the request with the same parameters later.",
                    $rcode, $rbody, $resp);
            default:
                throw new YM_ApiError("Unknown API response error. You should inform your software developer.",
                    $rcode, $rbody, $resp);
        }
    }
}
