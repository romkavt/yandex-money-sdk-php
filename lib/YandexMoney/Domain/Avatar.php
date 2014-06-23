<?php

namespace YandexMoney\Domain;


class Avatar extends Base
{

    const URL = 'url';
    const TIMESTAMP = 'ts';

    public function __construct(array $avatarParams)
    {
        $this->params = $avatarParams;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->checkAndReturn(self::URL);
    }

    /**
     * @return string|null
     */
    public function getTimestamp()
    {
        return $this->checkAndReturn(self::TIMESTAMP);
    }

} 