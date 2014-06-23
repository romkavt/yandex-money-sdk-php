<?php

namespace YandexMoney\Request;


use YandexMoney\Presets\ApiKey;

class PaymentRequest extends BaseRequest
{

    /**
     * @var bool
     */
    protected $amountWasUsed = false;

    /**
     * @var bool
     */
    protected $amountDueWasUsed = false;

    /**
     * @param boolean $codepro
     */
    public function setCodepro($codepro)
    {
        $this->paramsArray[ApiKey::CODEPRO] = $codepro;
    }

    /**
     * @return boolean
     */
    public function getCodepro()
    {
        return $this->checkAndReturn(ApiKey::CODEPRO);
    }

    /**
     * @param int $expirePeriod
     */
    public function setExpirePeriod($expirePeriod)
    {
        $this->paramsArray[ApiKey::EXPIRE_PERIOD] = $expirePeriod;
    }

    /**
     * @return int
     */
    public function getExpirePeriod()
    {
        return $this->checkAndReturn(ApiKey::EXPIRE_PERIOD);
    }

    /**
     * @return bool
     */
    public function isAmountUsed()
    {
        return $this->amountWasUsed;
    }

    /**
     * @return bool
     */
    public function isAmountDueUsed()
    {
        return $this->amountDueWasUsed;
    }

    /**
     * @param float $amount_due
     * @throws \Exception
     */
    public function setAmountDue($amount_due)
    {
        if ($this->amountWasUsed) {
            throw new \Exception("You can't use both setAmount and setAmountDue methods in one operation");
        } else {
            $this->paramsArray[ApiKey::AMOUNT_DUE] = $amount_due;
            $this->amountDueWasUsed = true;
        }
    }

    /**
     * @return float
     */
    public function getAmountDue()
    {
        return $this->checkAndReturn(ApiKey::AMOUNT_DUE);
    }

    /**
     * @param float $amount
     * @throws \Exception
     */
    public function setAmount($amount)
    {
        if ($this->amountDueWasUsed) {
            throw new \Exception("You can't use both setAmount and setAmountDue methods in one operation");
        } else {
            $this->paramsArray[ApiKey::AMOUNT] = $amount;
            $this->amountWasUsed = true;
        }
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->checkAndReturn(ApiKey::AMOUNT);
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->paramsArray[ApiKey::MESSAGE] = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->checkAndReturn(ApiKey::MESSAGE);
    }

    /**
     * @param string $to
     */
    public function setTo($to)
    {
        $this->paramsArray[ApiKey::TO] = $to;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->checkAndReturn(ApiKey::TO);
    }
} 