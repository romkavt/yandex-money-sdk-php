<?php

namespace YandexMoney\Domain;

/**
 * Class Notification {@link http://api.yandex.ru/money/doc/dg/reference/notification-p2p-incoming.xml}
 * @package YandexMoney\Domain
 */

class Notification extends Base
{
    const NOTIFICATION_TYPE = "notification_type";
    const P2P_INCOMING = "p2p-incoming";
    const CARD_INCOMING = "card-incoming";

    const OPERATION_ID = "operation_id";
    const AMOUNT = "amount";
    const WITHDRAW_AMOUNT = "withdraw_amount";
    const CURRENCY = "currency";
    const DATETIME = "datetime";
    const SENDER = "sender";
    const CODEPRO = "codepro";
    const LABEL = "label";
    const SHA1_HASH = "sha1_hash";
    const TEST_NOTIFICATION = "test_notification";

    const LAST_NAME = "lastname";
    const FIRST_NAME = "firstname";
    const FATHERS_NAME = "fathersname";
    const EMAIL = "email";
    const PHONE = "phone";
    const CITY = "city";
    const STREET = "street";
    const BUILDING = "building";
    const SUITE = "suite";
    const FLAT = "flat";
    const ZIP = "zip";

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string|null
     */
    public function getNotificationType()
    {
        return $this->checkAndReturn(self::NOTIFICATION_TYPE);
    }

    /**
     * @return string|null
     */
    public function getOperationId()
    {
        return $this->checkAndReturn(self::OPERATION_ID);
    }

    /**
     * @return float|null
     */
    public function getAmount()
    {
        return $this->checkAndReturn(self::AMOUNT);
    }

    /**
     * @return float|null
     */
    public function getWithdrawAmount()
    {
        return $this->checkAndReturn(self::WITHDRAW_AMOUNT);
    }

    /**
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->checkAndReturn(self::CURRENCY);
    }

    /**
     * @return string|null
     */
    public function getDatetime()
    {
        return $this->checkAndReturn(self::DATETIME);
    }

    /**
     * @return string|null
     */
    public function getSender()
    {
        return $this->checkAndReturn(self::SENDER);
    }

    /**
     * @return boolean|null
     */
    public function getCodepro()
    {
        return $this->checkAndReturn(self::CODEPRO);
    }

    /**
     * @return string|null
     */
    public function getLabel()
    {
        return $this->checkAndReturn(self::LABEL);
    }

    /**
     * @return string|null
     */
    public function getSha1Hash()
    {
        return $this->checkAndReturn(self::SHA1_HASH);
    }

    /**
     * @return bool
     */
    public function isTestNotification()
    {
        return $this->checkAndReturn(self::TEST_NOTIFICATION) == null ? false : true;
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->checkAndReturn(self::LAST_NAME);
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->checkAndReturn(self::FIRST_NAME);
    }

    /**
     * @return string|null
     */
    public function getFathersName()
    {
        return $this->checkAndReturn(self::FATHERS_NAME);
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->checkAndReturn(self::EMAIL);
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->checkAndReturn(self::PHONE);
    }

    /**
     * @return string|null
     */
    public function getCity()
    {
        return $this->checkAndReturn(self::CITY);
    }

    /**
     * @return string|null
     */
    public function getStreet()
    {
        return $this->checkAndReturn(self::STREET);
    }

    /**
     * @return string|null
     */
    public function getBuilding()
    {
        return $this->checkAndReturn(self::BUILDING);
    }

    /**
     * @return string|null
     */
    public function getSuite()
    {
        return $this->checkAndReturn(self::SUITE);
    }

    /**
     * @return string|null
     */
    public function getFlat()
    {
        return $this->checkAndReturn(self::FLAT);
    }

    /**
     * @return string|null
     */
    public function getZip()
    {
        return $this->checkAndReturn(self::ZIP);
    }
} 