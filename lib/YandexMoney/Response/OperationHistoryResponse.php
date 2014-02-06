<?php

namespace Yandex\YandexMoney\Response;

use Yandex\YandexMoney\Operation\Operation;

/**
 * 
 */
class OperationHistoryResponse implements ResponseInterface
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @param int
     */
    protected $nextRecord;
    
    /**
     * @var array
     */
    protected $operations;

    /**
     * @param array $operations
     */
    public function __construct(array $operations)
    {
        $this->operations = array();

        if (isset($operations['error'])) {
            $this->error = $operations['error'];
        }

        if (isset($operations['next_record'])) {
            $this->nextRecord = $operations['next_record'];
        }

        if (isset($operations['operations'])) {
            foreach ($operations['operations'] as $operation) {
                $this->operations[] = new Operation($operation);
            }
        }
    }

    /**
     * @return string возвращает код ошибки
     * Возможные значения:
     * illegal_param_type - неверное значение параметра type метода
     * operationHistory;
     * illegal_param_start_record - неверное значение параметра startRecord
     * метода operationHistory;
     * illegal_param_records ― неверное значение параметра records;
     * Все прочие значения: техническая ошибка, повторите вызов операции позднее.
     */
    public function getError() 
    {
        return $this->error;
    }

    /**
     * @return integer возвращает порядковый номер первой записи на следующей
     * странице истории операций. Присутствует, только если следующая
     * страница существует.
     */
    public function getNextRecord()
    {
        return $this->nextRecord;
    }

    /**
     * @return array возвращает массив объектов Operation
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess()
    {
        return $this->error === null;
    }
}
