<?php

namespace YandexMoney\Response;

use YandexMoney\Domain\OperationDetails;

/**
 * Class OperationHistoryResponse {@link http://api.yandex.ru/money/doc/dg/reference/operation-history.xml}
 * @package YandexMoney\Response
 *
 */
class OperationHistoryResponse extends BaseResponse implements ResponseInterface
{
    const NEXT_RECORD = 'next_record';
    const OPERATIONS = 'operations';

    /**
     * @param array $operations
     */
    public function __construct(array $operations)
    {
        $this->params = $operations;
    }

    /**
     * @return integer|null
     */
    public function getNextRecord()
    {
        return $this->checkAndReturn(self::NEXT_RECORD);
    }

    /**
     * @return array OperationDetails
     */
    public function getOperations()
    {
        $operationsArrayResponse = array();
        if ($this->checkAndReturn(self::OPERATIONS) != null && count($this->params[self::OPERATIONS]) > 0) {
            foreach ($this->params[self::OPERATIONS] as $operation) {
                array_push($operationsArrayResponse, new OperationDetails($operation));
            }
        }
        return $operationsArrayResponse;
    }

}
