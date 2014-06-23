<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/30/14
 * Time: 11:00 PM
 */

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