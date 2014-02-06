<?php

namespace YandexMoney\Response;

/**
 * 
 */
class RequestPaymentResponse implements ResponseInterface
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
    protected $moneySource;

    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var string
     */
    protected $contract;

    /**
     * @var string
     */
    protected $balance;

    /**
     * @param array $responseArray
     */
    public function __construct(array $response)
    {
        if (isset($response['status'])) {
            $this->status = $response['status'];
        }
        if (isset($response['error'])) {
            $this->error = $response['error'];
        }
        if (isset($response['money_source'])) {
            $this->moneySource = $response['money_source'];
        }
        if (isset($response['request_id'])) {
            $this->requestId = $response['request_id'];
        }
        if (isset($response['contract'])) {
            $this->contract = $response['contract'];
        }
        if (isset($response['balance'])) {
            $this->balance = $response['balance'];
        }
    }

    /**
     * @return string возвращает код результата выполнения операции.
     * Возможные значения:
     * success - успешное выполнение;
     * refused - отказ в проведении платежа, объяснение причины отказа
     * содержится в поле error. Это конечное состояние платежа.
     */
    public function getStatus()
    {
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
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string возвращает доступные для приложения методы
     * проведения платежа (wallet или card). Присутствует только при
     * успешном выполнении метода requestPayment.
     */
    public function getMoneySource()
    {
        return $this->moneySource;
    }

    /**
     * @return string возвращает идентификатор запроса платежа,
     * сгенерированный системой. Присутствует только при успешном
     * выполнении метода requestPayment.
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return string возвращает текст описания платежа (контракт).
     * Присутствует только при успешном выполнении метода requestPayment.
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @return string возвращает текущий остаток на счете пользователя.
     * Присутствует только при успешном выполнении метода requestPayment.
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess()
    {
        return $this->error === null;
    }
}
