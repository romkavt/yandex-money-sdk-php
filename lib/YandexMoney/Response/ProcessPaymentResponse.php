<?php

namespace YandexMoney\Response;

/**
 * Class ProcessPaymentResponse {@link http://api.yandex.ru/money/doc/dg/reference/process-payment.xml}
 * @package YandexMoney\Response
 */
class ProcessPaymentResponse extends BaseResponse
{
    const PAYMENT_ID = 'payment_id';
    const INVOICE_ID = 'invoice_id';
    const PAYER = 'payer';
    const PAYEE = 'payee';
    const CREDIT_AMOUNT = 'credit_amount';
    const ACCOUNT_UNBLOCK_URI = 'account_unblock_uri';
    const HOLD_FOR_PICKUP_LINK = 'hold_for_pickup_link';
    const ACS_URI = 'acs_uri';
    const ACS_PARAMS = 'acs_params';
    const NEXT_RETRY = 'next_retry';
    const DIGITAL_GOODS = 'digital_goods';

    /**
     * @param array $responseParams
     */
    public function __construct(array $responseParams)
    {
        $this->params = $responseParams;
    }

    /**
     * @return string|null
     */
    public function getInvoiceId()
    {
        return $this->checkAndReturn(self::INVOICE_ID);
    }

    /**
     * @return string|null
     */
    public function getAccountUnblockUri()
    {
        return $this->checkAndReturn(self::ACCOUNT_UNBLOCK_URI);
    }

    /**
     * @return string|null
     */
    public function getHoldForPickupLink()
    {
        return $this->checkAndReturn(self::HOLD_FOR_PICKUP_LINK);
    }

    /**
     * @return string|null
     */
    public function getAcsUri()
    {
        return $this->checkAndReturn(self::ACS_URI);
    }

    /**
     * @return array|null
     */
    public function getAcsParams()
    {
        return $this->checkAndReturn(self::ACS_PARAMS);
    }

    /**
     * @return integer|null
     */
    public function getNextRetry()
    {
        return $this->checkAndReturn(self::NEXT_RETRY);
    }

    /**
     * @return array|null
     */
    public function getDigitalGoods()
    {
        return $this->checkAndReturn(self::DIGITAL_GOODS);
    }

    /**
     * @return string|null
     */
    public function getPaymentId()
    {
        return $this->checkAndReturn(self::PAYMENT_ID);
    }

    /**
     * @return string|null
     */
    public function getBalance()
    {
        return $this->checkAndReturn(self::BALANCE);
    }

    /**
     * @return string|null
     */
    public function getPayer()
    {
        return $this->checkAndReturn(self::PAYER);
    }

    /**
     * @return string|null
     */
    public function getPayee()
    {
        return $this->checkAndReturn(self::PAYEE);
    }

    /**
     * @return float|null
     */
    public function getCreditAmount()
    {
        return $this->checkAndReturn(self::CREDIT_AMOUNT);
    }

}
