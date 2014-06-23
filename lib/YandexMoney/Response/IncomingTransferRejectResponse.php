<?php

namespace YandexMoney\Response;


class IncomingTransferRejectResponse extends BaseResponse
{

    public function __construct(array $responseParams)
    {
        $this->params = $responseParams;
    }
}