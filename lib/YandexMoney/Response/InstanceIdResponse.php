<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/7/14
 * Time: 7:19 PM
 */

namespace YandexMoney\Response;


class InstanceIdResponse extends BaseResponse
{

    const INSTANCE_ID = 'instance_id';

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string|null
     */
    public function getInstanceId()
    {
        return $this->checkAndReturn(self::INSTANCE_ID);
    }
} 