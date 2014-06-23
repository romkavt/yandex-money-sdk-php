<?php

namespace YandexMoney\Domain;

use YandexMoney\Response\BaseResponse;

/**
 * Class Operation {@link http://api.yandex.ru/money/doc/dg/reference/operation-history.xml}
 * @package YandexMoney\Domain
 */
class Operation extends BaseResponse
{
    const OPERATION_ID = 'operation_id';
    const PATTERN_ID = 'pattern_id';
    const TITLE = 'title';
    const DIRECTION = 'direction';
    const AMOUNT = 'amount';
    const DATETIME = 'datetime';
    const STATUS = 'status';
    const LABEL = 'label';
    const TYPE = 'type';

    /**
     * @param array $operationArray
     */
    public function __construct(array $operationArray)
    {
        $this->params = $operationArray;
    }

    /**
     * @return string|null
     */
    public function getOperationId()
    {
        return $this->checkAndReturn(self::OPERATION_ID);
    }

    /**
     * @return string|null
     */
    public function getPatternId()
    {
        return $this->checkAndReturn(self::PATTERN_ID);
    }

    /**
     * @return string|null
     */
    public function getDirection()
    {
        return $this->checkAndReturn(self::DIRECTION);
    }

    /**
     * @return string|null
     */
    public function getAmount()
    {
        return $this->checkAndReturn(self::AMOUNT);
    }

    /**
     * @return string|null
     */
    public function getDatetime()
    {
        return $this->checkAndReturn(self::DATETIME);
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->checkAndReturn(self::TITLE);
    }

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->checkAndReturn(self::STATUS);
    }

    /**
     * @return string|null
     */
    public function getLabel()
    {
        return $this->checkAndReturn(self::LABEL);
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->checkAndReturn(self::TYPE);
    }
}
