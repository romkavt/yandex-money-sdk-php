<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/28/14
 * Time: 8:34 PM
 */

namespace YandexMoney\Response;


use YandexMoney\Domain\Base;

class BaseResponse extends Base implements ResponseInterface
{

    const ERROR = 'error';
    const REQUEST_ID = 'request_id';
    const MONEY_SOURCE = 'money_source';
    const STATUS = 'status';
    const BALANCE = 'balance';

    /**
     * @var OriginalServerResponse
     */
    private $originalServerResponse;

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->checkAndReturn(self::STATUS);
    }

    /**
     * @return string/null
     */
    public function getError()
    {
        return $this->checkAndReturn(self::ERROR);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->checkAndReturn(self::ERROR) === null;
    }

    /**
     * @return \YandexMoney\Response\OriginalServerResponse
     */
    public function getOriginalServerResponse()
    {
        return $this->originalServerResponse;
    }

    /**
     * @param \YandexMoney\Response\OriginalServerResponse $originalServerResponse
     */
    public function setOriginalServerResponse($originalServerResponse)
    {
        $this->originalServerResponse = $originalServerResponse;
    }

    public function getDefinedParams()
    {
        return $this->params;
    }
}