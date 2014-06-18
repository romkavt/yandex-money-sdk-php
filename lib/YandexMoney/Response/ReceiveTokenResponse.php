<?php

namespace YandexMoney\Response;

/**
 *
 */
class ReceiveTokenResponse extends BaseResponse
{
    const ACCESS_TOKEN = 'access_token';

    /**
     * @param array $response
     */
    // TODO Won't work, should be refactored
    public function __construct(array $response)
    {
        $this->params = $response;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->checkAndReturn(self::ACCESS_TOKEN);
    }

}
