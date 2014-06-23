<?php

namespace YandexMoney\Domain;

use YandexMoney\Response\BaseResponse;

/**
 * Class AccountInfo {@link http://api.yandex.ru/money/doc/dg/reference/account-info.xml}
 * @package YandexMoney\Structure
 */

class AccountInfo extends BaseResponse
{
    const ACCOUNT = 'account';
    const BALANCE = 'balance';
    const CURRENCY = 'currency';
    const IDENTIFIED = 'identified';
    const ACCOUNT_STATUS = 'account_status';
    const ACCOUNT_TYPE = 'account_type';
    const AVATAR = 'avatar';
    const BALANCE_DETAILS = 'balance_details';
    const CARDS_LINKED = 'cards_linked';
    const SERVICES_ADDITIONAL = 'services_additional';

    /**
     * @param array $accountInfo
     */
    public function __construct(array $accountInfo)
    {
        $this->params = $accountInfo;
    }

    /**
     * @return string возвращает номер счета пользователя
     */
    public function getAccount()
    {
        return $this->checkAndReturn(self::ACCOUNT);
    }

    /**
     * @return string возвращает количество денег на счету
     */
    public function getBalance()
    {
        return $this->checkAndReturn(self::BALANCE);
    }

    /**
     * @return string возвращает код валюты пользователя
     */
    public function getCurrency()
    {
        return $this->checkAndReturn(self::CURRENCY);
    }

    /**
     * @return boolean возвращает, идентифицирован ли пользователь в системе
     */
    public function getIdentified()
    {
        return $this->checkAndReturn(self::IDENTIFIED);
    }

    /**
     * @return string|null
     */
    public function getAccountStatus()
    {
        return $this->checkAndReturn(self::ACCOUNT_STATUS);
    }

    /**
     * @return boolean возвращает тип счета пользователя
     */
    public function getAccountType()
    {
        return $this->checkAndReturn(self::ACCOUNT_TYPE);
    }

    /**
     * @return Avatar|null
     */
    public function getAvatar()
    {
        $avatar = null;
        if ($this->checkAndReturn(self::AVATAR) != null) {
            $avatar = new Avatar($this->params[self::AVATAR]);
        }
        return $avatar;
    }

    /**
     * @return BalanceDetails|null
     */
    public function getBalanceDetails()
    {
        $balanceDetails = null;
        if ($this->checkAndReturn(self::BALANCE_DETAILS) != null) {
            $balanceDetails = new BalanceDetails($this->params[self::BALANCE_DETAILS]);
        }
        return $balanceDetails;
    }

    /**
     * @return array LinkedCard
     */
    public function getCardsLinked()
    {
        $cardsLinked = array();
        if ($this->checkAndReturn(self::CARDS_LINKED) != null && !empty($this->params[self::CARDS_LINKED])) {
            $cardsLinkedArray = $this->checkAndReturn(self::CARDS_LINKED);
            foreach ($cardsLinkedArray as $linkedCardParams) {
                array_push($cardsLinked, new LinkedCard($linkedCardParams));
            }
        }
        return $cardsLinked;
    }

    /**
     * @return array string|null
     */
    public function getServicesAdditional()
    {
        return $this->checkAndReturn(self::SERVICES_ADDITIONAL);
    }
}
