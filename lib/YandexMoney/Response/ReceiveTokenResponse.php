<?php

namespace YandexMoney\Response;

/**
 * 
 */
class ReceiveTokenResponse implements ResponseInterface
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $error;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        if (isset($response['access_token'])) {
            $this->accessToken = $response['access_token'] ;
        }

        if (isset($response['error'])) {
            $this->error = $response['error'];
        }
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * {@inheritDoc}
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess()
    {
        return $this->error === null;
    }
}
