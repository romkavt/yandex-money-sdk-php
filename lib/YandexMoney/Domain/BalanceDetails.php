<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/27/14
 * Time: 11:55 PM
 */

namespace YandexMoney\Domain;


class BalanceDetails extends Base
{

    const TOTAL = 'total';
    const AVAILABLE = 'available';
    const DEPOSITION_PENDING = 'deposition_pending';
    const BLOCKED = 'blocked';
    const DEBT = 'debt';

    public function __construct(array $balanceDetailsArray)
    {
        $this->params = $balanceDetailsArray;
    }

    /**
     * @return float|null
     */
    public function getTotal()
    {
        return $this->checkAndReturn(self::TOTAL);
    }

    /**
     * @return float|null
     */
    public function getAvailable()
    {
        return $this->checkAndReturn(self::AVAILABLE);
    }

    /**
     * @return float|null
     */
    public function getDepositionPending()
    {
        return $this->checkAndReturn(self::DEPOSITION_PENDING);
    }

    /**
     * @return float|null
     */
    public function getBlocked()
    {
        return $this->checkAndReturn(self::BLOCKED);
    }

    /**
     * @return float|null
     */
    public function getDebt()
    {
        return $this->checkAndReturn(self::DEBT);
    }
} 