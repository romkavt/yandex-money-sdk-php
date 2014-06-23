<?php

namespace YandexMoney\Presets;


class Uri
{

    const API = 'https://money.yandex.ru/api';
    const AUTH = 'https://sp-money.yandex.ru/oauth/authorize';
    const TOKEN = 'https://sp-money.yandex.ru/oauth/token';

    const REVOKE = '/revoke';
    const INSTANCE_ID = "/instance-id";
    const REQUEST_EXTERNAL_PAYMENT = "/request-external-payment";
    const PROCESS_EXTERNAL_PAYMENT = "/process-external-payment";
    const ACCOUNT_INFO = '/account-info';
    const OPERATION_HISTORY = '/operation-history';
    const OPERATION_DETAILS = '/operation-details';
    const REQUEST_PAYMENT = '/request-payment';
    const PROCESS_PAYMENT = '/process-payment';

    const INCOMING_TRANSFER_ACCEPT = '/incoming-transfer-accept';
    const INCOMING_TRANSFER_REJECT = '/incoming-transfer-reject';

} 