<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/27/14
 * Time: 11:29 PM
 */

namespace YandexMoney\Domain;


class Base
{

    /**
     * @var array of mixed
     */
    protected $params = array();

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param string $key of response parameter
     * @return null | mixed
     */
    public function checkAndReturn($key)
    {
        $value = null;
        if (array_key_exists($key, $this->params)) {
            $value = $this->params[$key];
        }
        return $value;
    }
} 