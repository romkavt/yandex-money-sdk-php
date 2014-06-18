<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/10/14
 * Time: 8:45 PM
 */

namespace YandexMoney\Request;


class ProcessPaymentByWalletRequest extends BaseRequest
{

    const REQUEST_ID = 'request_id';

    public function setRequestId($requestId)
    {
        $this->paramsArray[self::REQUEST_ID] = $requestId;
    }

    public function getRequestId()
    {
        return $this->checkAndReturn(self::REQUEST_ID);
    }
} 