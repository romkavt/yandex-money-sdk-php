<?php

namespace YandexMoney\Utils;


use YandexMoney\Presets\MoneySource;
use YandexMoney\Presets\PaymentIdentifier;
use YandexMoney\Presets\Rights;

/**
 * Class RightsConfigurator {@link http://api.yandex.ru/money/doc/dg/concepts/protocol-rights.xml}
 * @package YandexMoney\Utils
 */
class RightsConfigurator
{

    const LIMIT_SUM = "sum";
    const LIMIT_DURATION = "duration";
    const PAYMENT_TO_ACCOUNT = "payment.to-account";
    const PAYMENT_TO_PATTERN = "payment.to-pattern";
    const LIMIT = "limit";
    const MONEY_SOURCE = "money-source";

    private $rightsString = "";

    public function addRight($right)
    {
        $this->rightsString .= $right . " ";
    }

    public function paymentToAccount(
        $to = null,
        $identifierType = PaymentIdentifier::ACCOUNT,
        $duration = '',
        $sum = ''
    ) {
        $this->rightsString .= self::PAYMENT_TO_ACCOUNT . "(\"" . $to . "\",\"" . $identifierType . "\")";
        $this->addLimit($duration, $sum);
    }

    public function paymentToPattern($patternId = null, $duration = null, $sum = null)
    {
        $this->rightsString .= self::PAYMENT_TO_PATTERN . "(\"" . $patternId . "\")";
        $this->addLimit($duration, $sum);
    }

    public function setMoneySource($moneySourceFirst = null, $moneySourceSecond = null)
    {
        $moneySource = self::MONEY_SOURCE . "(";
        if ($moneySourceFirst == null && $moneySourceSecond == null) {
            $moneySource .= '"' . MoneySource::WALLET . '"';
        } else if ($moneySourceFirst != null && $moneySourceSecond == null) {
            $moneySource .= '"' . $moneySourceFirst . '"';
        } else if ($moneySourceFirst != null && $moneySourceSecond != null) {
            $moneySource .= '"' . $moneySourceFirst . '","' . $moneySourceSecond . '"';
        }
        $moneySource .= ")";

        $this->rightsString .= $moneySource;
    }

    public function toString() {

        if(empty($this->rightsString)) {
            $this->rightsString = Rights::ACCOUNT_INFO . " " . Rights::OPERATION_HISTORY;
        }
        return trim($this->rightsString);
    }

    /**
     * @param $duration
     * @param $sum
     */
    private function addLimit($duration, $sum)
    {
        if (!empty($sum)) {
            $this->rightsString .= "." . self::LIMIT . "(" . $duration . "," . $sum . ") ";
        } else {
            $this->rightsString .= " ";
        }
    }

} 
