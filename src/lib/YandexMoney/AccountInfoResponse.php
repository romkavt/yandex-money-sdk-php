<?php

class YM_AccountInfoResponse {

    protected $account;
    protected $balance;
    protected $currency;

    public function __construct($accountInfoArray) {
        if (isset($accountInfoArray['account']))
            $this->account = $accountInfoArray['account'];
        if (isset($accountInfoArray['balance']))
            $this->balance = $accountInfoArray['balance'];
        if (isset($accountInfoArray['currency']))
            $this->currency = $accountInfoArray['currency'];
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

    public function isSuccess() {
        return true;
    }
}
