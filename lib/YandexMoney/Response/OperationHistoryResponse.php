<?php

class YM_OperationHistoryResponse {

    protected $error;
    protected $nextRecord;
    protected $operations = array();

    public function __construct($operationsArray) {
        if (isset($operationsArray['error']))
            $this->error = $operationsArray['error'];
        if (isset($operationsArray['next_record']))
            $this->nextRecord = $operationsArray['next_record'];

        if (isset($operationsArray['operations'])) {
            foreach ($operationsArray['operations'] as $operation) {
                $this->operations[] = new YM_Operation($operation);
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
    public function getError() {
        return $this->error;
    }

    /**
     * @return integer возвращает порядковый номер первой записи на следующей
     * странице истории операций. Присутствует, только если следующая
     * страница существует.
     */
    public function getNextRecord() {
        return $this->nextRecord;
    }

    /**
     * @return YM_Operation[] возвращает массив объектов Operation
     */
    public function getOperations() {
        return $this->operations;
    }

    public function isSuccess() {
        return $this->error === null;
    }
}
