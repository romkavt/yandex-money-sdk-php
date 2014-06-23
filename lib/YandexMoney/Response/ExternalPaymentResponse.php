<?php

namespace YandexMoney\Response;


class ExternalPaymentResponse extends RequestPaymentResponse
{

    const TITLE = 'title';

    public function __construct(array $responseParams)
    {
        $this->params = $responseParams;
    }

    /**
     * @return string|null
     */
    public function getTitle() {
        return $this->checkAndReturn(self::TITLE);
    }

} 