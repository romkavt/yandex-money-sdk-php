<?php

namespace YandexMoney\Response;


class ExternalProcessPaymentResponse extends ProcessPaymentResponse
{

    const MONEY_SOURCE = 'money_source';

    public function __construct(array $responseParams)
    {
        $this->params = $responseParams;
    }

    /**
     * @return array|null
     */
    public function getMoneySource()
    {
        return $this->checkAndReturn(self::MONEY_SOURCE);
    }

} 