<?php

require_once 'yamoney/ym.php';
require_once 'consts.php';

$scope = '';
$arr = YMScope::getScopeArray();
$co = count($arr);
for ($i = 0; $i < $co; $i++) {
    $key = trim($arr[$i]);
    if (array_key_exists($key, $_POST)) {
        $scope = $scope . '' . $_POST[$key];
    }
}

YandexMoney::authorize(Consts::CLIENT_ID, $scope, Consts::REDIRECT_URL);
?>
