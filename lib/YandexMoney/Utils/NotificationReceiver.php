<?php

namespace YandexMoney\Utils;

use YandexMoney\Domain\Notification;

class NotificationReceiver
{

    /**
     * @var array mixed
     */
    private $responsePostArray = array();

    /**
     * @var string
     */
    private $receivedSha1Hash;

    /**
     * @var string
     */
    private $notificationSecret;

    /**
     * @param boolean
     */
    private $inputParamsWhereChecked = false;


    public function __construct($notificationSecret)
    {
        $this->notificationSecret = $notificationSecret;
        if (!empty($_POST)) {
            $this->responsePostArray = $_POST;
            $this->receivedSha1Hash = $this->responsePostArray[Notification::SHA1_HASH];
        }
    }

    /**
     * @return bool
     */
    public function receivedValidNotification()
    {
        $valid = false;
        if (count($this->responsePostArray) > 8) {
            $receivedParamsString = $this->buildStringForHashing();
            $sha1HashOfReceivedParams = hash("sha1", $receivedParamsString);
            $valid = (strcmp($this->receivedSha1Hash, $sha1HashOfReceivedParams) === 0);
        }
        $this->inputParamsWhereChecked = true;
        return $valid;
    }

    /**
     * @throws \ErrorException
     * @return Notification You have to invoke this method only after validation
     */
    public function getNotification() {
        if($this->inputParamsWhereChecked)
            return new Notification($this->responsePostArray);
        else
            throw new \ErrorException("Notification must be validated before! Use receivedValidNotification() method.");
    }

    /**
     * @return string
     */
    private function buildStringForHashing()
    {
        $receivedParamsString = $this->responsePostArray[Notification::NOTIFICATION_TYPE] . "&"
            . $this->responsePostArray[Notification::OPERATION_ID] . "&"
            . $this->responsePostArray[Notification::AMOUNT] . "&"
            . $this->responsePostArray[Notification::CURRENCY] . "&"
            . $this->responsePostArray[Notification::DATETIME] . "&"
            . $this->responsePostArray[Notification::SENDER] . "&"
            . $this->responsePostArray[Notification::CODEPRO] . "&"
            . $this->notificationSecret . "&"
            . $this->responsePostArray[Notification::LABEL];
        return $receivedParamsString;
    }

    /**
     * @return array
     */
    public function getResponsePostArray()
    {
        return $this->responsePostArray;
    }

    /**
     * @param array $responsePostArray
     */
    public function setResponsePostArray($responsePostArray)
    {
        $this->responsePostArray = $responsePostArray;
        $this->receivedSha1Hash = $this->responsePostArray[Notification::SHA1_HASH];
    }
} 