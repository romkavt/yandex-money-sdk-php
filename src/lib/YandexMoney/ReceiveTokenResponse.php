<?php

class YM_ReceiveTokenResponse {

    protected $accessToken;
    protected $error;

    public function __construct($response) {
        if (isset($response["access_token"]))
            $this->accessToken = $response["access_token"] ;

        if (isset($response["error"]))
            $this->error = $response["error"];
    }

    public function getAccessToken() {
        return $this->accessToken;
    }

    public function getError() {
        return $this->error;
    }

    public function isSuccess() {
        return $this->error === null;
    }
}
