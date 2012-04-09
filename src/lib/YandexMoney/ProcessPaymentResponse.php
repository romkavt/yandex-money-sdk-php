<?php

class YM_ProcessPaymentResponse {

    protected $status;
    protected $error;
    protected $paymentId;
    protected $balance;
    protected $payer;
    protected $payee;
    protected $creditAmount;

    public function __construct($responseArray) {
        if (isset($responseArray['status']))
            $this->status = $responseArray['status'];
        if (isset($responseArray['error']))
            $this->error = $responseArray['error'];
        if (isset($responseArray['payment_id']))
            $this->paymentId = $responseArray['payment_id'];
        if (isset($responseArray['balance']))
            $this->balance = $responseArray['balance'];
        if (isset($responseArray['payer']))
            $this->payer = $responseArray['payer'];
        if (isset($responseArray['payee']))
            $this->payee = $responseArray['payee'];
        if (isset($responseArray['credit_amount']))
            $this->creditAmount = $responseArray['credit_amount'];
    }

    /**
     * @return string возвращает код результата выполнения операции.
     * Возможные значения:
     * success - успешное выполнение (платеж проведен). Это конечное состояние платежа;
     * refused - отказ в проведении платежа, объяснение причины отказа
     * содержится в поле error. Это конечное состояние платежа;
     * in_progress - авторизация платежа находится в процессе выполнения.
     * Приложению следует повторить запрос с теми же параметрами спустя некоторое время;
     * все прочие значения - состояние платежа неизвестно. Приложению
     * следует повторить запрос с теми же параметрами спустя некоторое время.
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return string возвращает код ошибки при проведении
     * платежа (пояснение к полю status). Присутствует только при ошибках.
     * Возможные значения:
     * contract_not_found - отсутствует выставленный контракт с заданным requestId;
     * not_enough_funds - недостаточно средств на счете плательщика;
     * limit_exceeded - превышен лимит на сумму операции или сумму операций за
     * период времени для выданного токена авторизации. Приложение должно
     * отобразить соответствующее диалоговое окно.
     * money_source_not_available - запрошенный метод платежа (money_source)
     * недоступен для данного платежа.
     * illegal_param_csc - отсутствует или указано недопустимое значение параметра csc;
     * payment_refused - магазин по какой-либо причине отказал в приеме платежа;
     * authorization_reject - в авторизации платежа отказано. Истек срок действия
     * карты, либо банк-эмитент отклонил транзакцию по карте,
     * либо превышен лимит платежной системы для данного пользователя.
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @return string возвращает идентификатор проведенного платежа.
     * Присутствует только при успешном выполнении метода.
     */
    public function getPaymentId() {
        return $this->paymentId;
    }

    /**
     * @return string возвращает остаток на счете пользователя после
     * проведения платежа. Присутствует только при успешном выполнении метода.
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * @return string возвращает номер счета плательщика. Присутствует
     * только при успешном выполнении метода.
     */
    public function getPayer() {
        return $this->payer;
    }

    /**
     * @return string возвращает номер счета получателя. Присутствует
     * только при успешном выполнении метода.
     */
    public function getPayee() {
        return $this->payee;
    }

    /**
     * @return string возвращает сумму, полученную на счет получателем.
     * Присутствует при успешном переводе средств на счет другого
     * пользователя системы.
     */
    public function getCreditAmount() {
        return $this->creditAmount;
    }

    public function isSuccess() {
        return $this->error === null;
    }
}
