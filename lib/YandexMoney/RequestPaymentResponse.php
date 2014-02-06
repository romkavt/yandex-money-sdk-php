<?php

class YM_RequestPaymentResponse {

    protected $status;
    protected $error;
    protected $moneySource;
    protected $requestId;
    protected $contract;
    protected $balance;

    public function __construct($responseArray) {
        if (isset($responseArray['status']))
            $this->status = $responseArray['status'];
        if (isset($responseArray['error']))
            $this->error = $responseArray['error'];
        if (isset($responseArray['money_source']))
            $this->moneySource = $responseArray['money_source'];
        if (isset($responseArray['request_id']))
            $this->requestId = $responseArray['request_id'];
        if (isset($responseArray['contract']))
            $this->contract = $responseArray['contract'];
        if (isset($responseArray['balance']))
            $this->balance = $responseArray['balance'];
    }

    /**
     * @return string возвращает код результата выполнения операции.
     * Возможные значения:
     * success - успешное выполнение;
     * refused - отказ в проведении платежа, объяснение причины отказа
     * содержится в поле error. Это конечное состояние платежа.
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return string возвращает код ошибки при проведении платежа
     * (пояснение к полю status). Присутствует только при ошибках.
     * Возможные значения:
     * illegal_params ― отсутствуют или имеют недопустимые значения
     * обязательные параметры платежа;
     * payment_refused ― магазин отказал в приеме платежа (например
     * пользователь попробовал заплатить за товар, которого нет в магазине).
     * Все прочие значения: техническая ошибка, повторите платеж
     * через несколько минут.
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @return string возвращает доступные для приложения методы
     * проведения платежа (wallet или card). Присутствует только при
     * успешном выполнении метода requestPayment.
     */
    public function getMoneySource() {
        return $this->moneySource;
    }

    /**
     * @return string возвращает идентификатор запроса платежа,
     * сгенерированный системой. Присутствует только при успешном
     * выполнении метода requestPayment.
     */
    public function getRequestId() {
        return $this->requestId;
    }

    /**
     * @return string возвращает текст описания платежа (контракт).
     * Присутствует только при успешном выполнении метода requestPayment.
     */
    public function getContract() {
        return $this->contract;
    }

    /**
     * @return string возвращает текущий остаток на счете пользователя.
     * Присутствует только при успешном выполнении метода requestPayment.
     */
    public function getBalance() {
        return $this->balance;
    }

    public function isSuccess() {
        return $this->error === null;
    }
}
