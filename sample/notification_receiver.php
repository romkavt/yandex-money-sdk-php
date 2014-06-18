<?php

ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../sample/consts.php';

use YandexMoney\YandexMoney;

$notificationReceiver = YandexMoney::getNotificationReceiver(NOTIFICATION_SECRET);

$logFile = fopen('testlog.txt', 'a+');

if ($notificationReceiver->receivedValidNotification()) {
    $notification = $notificationReceiver->getNotification();

    fwrite($logFile, "Request time: " . date("Y-m-d H:i:s", time()) . "\r\n");

    // Available on HTTP connection
    fwrite($logFile, $notification->getNotificationType() . "\r\n");
    fwrite($logFile, $notification->getOperationId() . "\r\n");
    fwrite($logFile, $notification->getAmount() . "\r\n");
    fwrite($logFile, $notification->getWithdrawAmount() . "\r\n");
    fwrite($logFile, $notification->getCurrency() . "\r\n");
    fwrite($logFile, $notification->getDatetime() . "\r\n");
    fwrite($logFile, $notification->getSender() . "\r\n");
    fwrite($logFile, $notification->getCodepro() . "\r\n");
    fwrite($logFile, $notification->getLabel() . "\r\n");
    fwrite($logFile, $notification->getSha1Hash() . "\r\n");
    fwrite($logFile, $notification->isTestNotification() . "\r\n");

    // Available on HTTPS connection
    fwrite($logFile, $notification->getLastName() . "\r\n");
    fwrite($logFile, $notification->getFirstName() . "\r\n");
    fwrite($logFile, $notification->getFathersName() . "\r\n");
    fwrite($logFile, $notification->getEmail() . "\r\n");
    fwrite($logFile, $notification->getPhone() . "\r\n");
    fwrite($logFile, $notification->getCity() . "\r\n");
    fwrite($logFile, $notification->getBuilding() . "\r\n");
    fwrite($logFile, $notification->getSuite() . "\r\n");
    fwrite($logFile, $notification->getFlat() . "\r\n");
    fwrite($logFile, $notification->getZip() . "\r\n");

    fwrite($logFile, " \r\n\r\n");

} else {
    $response = "Invalid request has been received!";

    fwrite($logFile, "Request time: " . date("Y-m-d H:i:s", time()) . "\r\n" . $response . "\r\n\r\n");
}

fclose($logFile);