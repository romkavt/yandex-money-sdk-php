<?php

namespace Yandex\YandexMoney\Response;

/**
 * 
 */
class AccountInfoResponse implements ResponseInterface
{
    /**
     * @var string
     */
    protected $account;
    
    /**
     * @var string
     */
    protected $balance;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var boolean
     */
    protected $identified;

    /**
     * @var boolean
     */
    protected $accountType;

    /**
     * @param array $accountInfo
     */
    public function __construct(array $accountInfo)
    {
        if (isset($accountInfo['account'])) {
            $this->account = $accountInfo['account'];
        }
        if (isset($accountInfo['balance'])) {
            $this->balance = $accountInfo['balance'];
        }
        if (isset($accountInfo['currency'])) {
            $this->currency = $accountInfo['currency'];
        }
        if (isset($accountInfo['identified'])) {
            $this->identified = (bool) $accountInfo['identified'];
        }
        if (isset($accountInfo['account_type'])) {
            $this->accountType = $accountInfo['account_type'];
        }
    }

    /**
     * @return string возвращает номер счета пользователя
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return string возвращает количество денег на счету
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return string возвращает код валюты пользователя
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return boolean возвращает, идентифицирован ли пользователь в системе
     */
    public function getIdentified()
    {
        return $this->identified;
    }

    /**
     * @return boolean возвращает тип счета пользователя
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * {@inheritDoc}
     */
    public function getError()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess()
    {
        return true;
    }
}
