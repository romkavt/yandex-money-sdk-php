<?php

class YM_AccountInfoResponse {

    protected $account;
    protected $balance;
    protected $currency;
    protected $identified;
    protected $accountType;

    public function __construct($accountInfoArray) {
        if (isset($accountInfoArray['account']))
            $this->account = $accountInfoArray['account'];
        if (isset($accountInfoArray['balance']))
            $this->balance = $accountInfoArray['balance'];
        if (isset($accountInfoArray['currency']))
            $this->currency = $accountInfoArray['currency'];
        if (isset($accountInfoArray['identified']))
            $this->identified = (bool)$accountInfoArray['identified'];
        if (isset($accountInfoArray['account_type']))
            $this->accountType = $accountInfoArray['account_type'];
    }

    /**
     * @return string возвращает номер счета пользователя
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * @return string возвращает количество денег на счету
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * @return string возвращает код валюты пользователя
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @return boolean возвращает, идентифицирован ли пользователь в системе
     */
    public function getIdentified() {
        return $this->identified;
    }

    /**
     * @return boolean возвращает тип счета пользователя
     */
    public function getAccountType() {
        return $this->accountType;
    }

    public function isSuccess() {
        return true;
    }
}
