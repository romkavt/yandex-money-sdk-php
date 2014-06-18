<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/30/14
 * Time: 10:26 PM
 */

namespace YandexMoney\Response;


class IncomingTransferRejectResponse extends BaseResponse
{

    public function __construct(array $responseParams)
    {
        $this->params = $responseParams;
    }
}