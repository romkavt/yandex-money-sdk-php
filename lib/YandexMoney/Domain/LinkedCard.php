<?php

namespace YandexMoney\Domain;


class LinkedCard extends Base
{

    const ID = 'id';
    const PAN_FRAGMENT = 'pan_fragment';
    const TYPE = 'type';

    public function __construct(array $linkedCardParams)
    {
        $this->params = $linkedCardParams;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->checkAndReturn(self::ID);
    }

    /**
     * @return string|null
     */
    public function getPanFragment()
    {
        return $this->checkAndReturn(self::PAN_FRAGMENT);
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->checkAndReturn(self::TYPE);
    }
} 