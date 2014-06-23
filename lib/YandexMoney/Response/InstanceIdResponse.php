<?php

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