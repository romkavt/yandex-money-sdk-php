<?php

namespace YandexMoney\Response;

/**
 * 
 */
class ProcessPaymentResponse implements ResponseInterface
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var string
     */
    protected $paymentId;

    /**
     * @var string
     */
    protected $balance;

    /**
     * @var string
     */
    protected $payer;

    /**
     * @var string
     */
    protected $payee;

    /**
     * @var string
     */
    protected $creditAmount;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        if (isset($response['status'])) {
            $this->status = $response['status'];
        }
        if (isset($response['error'])) {
            $this->error = $response['error'];
        }
        if (isset($response['payment_id'])) {
            $this->paymentId = $response['payment_id'];
        }
        if (isset($response['balance'])) {
            $this->balance = $response['balance'];
        }
        if (isset($response['payer'])) {
            $this->payer = $response['payer'];
        }
        if (isset($response['payee'])) {
            $this->payee = $response['payee'];
        }
        if (isset($response['credit_amount'])) {
            $this->creditAmount = $response['credit_amount'];
        }
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
    public function getStatus()
    {
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
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string возвращает идентификатор проведенного платежа.
     * Присутствует только при успешном выполнении метода.
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @return string возвращает остаток на счете пользователя после
     * проведения платежа. Присутствует только при успешном выполнении метода.
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return string возвращает номер счета плательщика. Присутствует
     * только при успешном выполнении метода.
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * @return string возвращает номер счета получателя. Присутствует
     * только при успешном выполнении метода.
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * @return string возвращает сумму, полученную на счет получателем.
     * Присутствует при успешном переводе средств на счет другого
     * пользователя системы.
     */
    public function getCreditAmount()
    {
        return $this->creditAmount;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess()
    {
        return $this->error === null;
    }
}
