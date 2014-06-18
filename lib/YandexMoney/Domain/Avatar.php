<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/27/14
 * Time: 11:50 PM
 */

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