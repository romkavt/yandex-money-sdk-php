<?php

ini_set('display_errors', 1);

require_once(dirname(__FILE__) . '/../lib/YandexMoney.php');
require_once(dirname(__FILE__) . '/consts.php');

$code = $_GET['code'];
if (!isset($code)) { // If we are just begginig OAuth
    $scope = "account-info " .
        "operation-history " .
        "operation-details " .
        "payment.to-account(\"410011161616877\",\"account\").limit(30,10) " .
        "payment.to-pattern(\"337\").limit(30,10) " .
        "money-source(\"wallet\",\"card\") ";
    $authUri = YandexMoneyNew::authorizeUri(CLIENT_ID, REDIRECT_URI, $scope);
    header('Location: ' . $authUri);

} else { // when we recieved a temporary code on redirect
    ?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Yandex.Money PHP SDK sample app</title>
</head>
<body>
<div id="main">
    <h3 id="header">Yandex.Money PHP SDK sample app</h3>

    <?php


        $ym = new YandexMoneyNew(CLIENT_ID, './ym.log');
        $receiveTokenResp = $ym->receiveOAuthToken($code, REDIRECT_URI, CLIENT_SECRET);

        print "<p class=\"output\">";
        if ($receiveTokenResp->isSuccess()) {
            $token = $receiveTokenResp->getAccessToken();
            print "Received token: " . $token;
        } else {
            print "Error: " . $receiveTokenResp->getError();
            die();
        }
        print "</p>";
        print "<p>Notice: after you received access_token you should store it to your app's storage</p>";


        $resp = $ym->accountInfo($token);
        print "<p class=\"output\">";
        if ($resp->isSuccess()) {
            var_dump($resp);
        } else {
            print "Error: " . $resp->getError();
            die();
        }
        print "</p>";


        $resp = $ym->operationHistory($token, 0, 3);
        print "<p class=\"output\">";
        if ($resp->isSuccess()) {
            var_dump($resp);
        } else {
            print "Error: " . $resp->getError();
            die();
        }
        print "</p>";

        $operations = $resp->getOperations();
        $requestId = $operations[0]->getOperationId();
        $resp = $ym->operationDetail($token, $requestId);
        print "<p class=\"output\">";
        if ($resp->isSuccess()) {
            var_dump($resp);
        } else {
            print "Error: " . $resp->getError();
            die();
        }
        print "</p>";

        $resp = $ym->requestPaymentP2P($token, "410011161616877", "0.02");
        print "<p class=\"output\">";
        if ($resp->isSuccess()) {
            var_dump($resp);
        } else {
            print "Error: " . $resp->getError();
            die();
        }
        print "</p>";

        $requestId = $resp->getRequestId();
        $resp = $ym->processPaymentByWallet($token, $requestId);
        print "<p class=\"output\">";
        if ($resp->isSuccess()) {
            var_dump($resp);
        } else {
            print "Error: " . $resp->getError();
            die();
        }
        print "</p>";

        $params["pattern_id"] = "337";
        $params["PROPERTY1"] = "921";
        $params["PROPERTY2"] = "3020052";
        $params["sum"] = "1.00";
        $resp = $ym->requestPaymentShop($token, $params);
        print "<p class=\"output\">";
        if ($resp->isSuccess()) {
            var_dump($resp);
        } else {
            print "Error: " . $resp->getError();
            die();
        }
        print "</p>";

        $requestId = $resp->getRequestId();
        $resp = $ym->processPaymentByCard($token, $requestId, "375");
        print "<p class=\"output\">";
        if ($resp->isSuccess()) {
            var_dump($resp);
        } else {
            print "Error: " . $resp->getError();
            die();
        }
        print "</p>";

        if ($ym->revokeOAuthToken($token)) {
            print "<p>Token was successfully revoked</p>";
        }
    }

    ?>
</div>
</body>
</html>