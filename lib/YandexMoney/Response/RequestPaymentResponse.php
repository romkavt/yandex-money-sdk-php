<?php

namespace YandexMoney\Response;

use YandexMoney\Domain\ProcessPaymentMoneySource;

/**
 * Class RequestPaymentResponse {@link http://api.yandex.ru/money/doc/dg/reference/request-payment.xml}
 * @package YandexMoney\Response
 */
class RequestPaymentResponse extends BaseResponse
{
    const EXT_ACTION_URI = 'ext_action_uri';
    const ACCOUNT_UNBLOCK_URI = 'account_unblock_uri';
    const PROTECTION_CODE = 'protection_code';
    const RECIPIENT_ACCOUNT_TYPE = 'recipient_account_type';
    const RECIPIENT_ACCOUNT_STATUS = 'recipient_account_status';
    const RECIPIENT_IDENTIFIED = 'recipient_identified';
    const CONTRACT_AMOUNT = 'contract_amount';
    const CONTRACT = 'contract';


    /**
     * @param array $response
     * @internal param array $responseArray
     */
    public function __construct(array $response)
    {
        $this->params = $response;
    }

    /**
     * @return string возвращает доступные для приложения методы
     * проведения платежа (wallet или card). Присутствует только при
     * успешном выполнении метода requestPayment.
     */
    public function getMoneySource()
    {
        $moneySource = null;
        if ($this->checkAndReturn(self::MONEY_SOURCE) != null) {
            $moneySource = new ProcessPaymentMoneySource($this->params[self::MONEY_SOURCE]);
        }

        return $moneySource;
    }

    /**
     * @return string возвращает идентификатор запроса платежа,
     * сгенерированный системой. Присутствует только при успешном
     * выполнении метода requestPayment.
     */
    public function getRequestId()
    {
        return $this->checkAndReturn(self::REQUEST_ID);
    }

    /**
     * @return string|null
     */
    public function getContract()
    {
        return $this->checkAndReturn(self::CONTRACT);
    }

    /**
     * @return float|null
     */
    public function getContractAmount()
    {
        return $this->checkAndReturn(self::CONTRACT_AMOUNT);
    }


    /**
     * @return string возвращает текущий остаток на счете пользователя.
     * Присутствует только при успешном выполнении метода requestPayment.
     */
    public function getBalance()
    {
        return $this->checkAndReturn(self::BALANCE);
    }

    /**
     * @return string|null
     */
    public function getRecipientAccountStatus()
    {
        return $this->checkAndReturn(self::RECIPIENT_ACCOUNT_STATUS);
    }

    /**
     * @return boolean|null
     */
    public function isRecipientIdentified()
    {
        return $this->checkAndReturn(self::RECIPIENT_IDENTIFIED);
    }

    /**
     * @return string|null
     */
    public function getRecipientAccountType()
    {
        return $this->checkAndReturn(self::RECIPIENT_ACCOUNT_TYPE);
    }

    /**
     * @return string|null
     */
    public function getProtectionCode()
    {
        return $this->checkAndReturn(self::PROTECTION_CODE);
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
    public function getExtActionUri()
    {
        return $this->checkAndReturn(self::EXT_ACTION_URI);
    }
}
