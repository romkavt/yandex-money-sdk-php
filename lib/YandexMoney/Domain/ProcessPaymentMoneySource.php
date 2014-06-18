<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/28/14
 * Time: 10:37 PM
 */

namespace YandexMoney\Domain;

/**
 * Class ProcessPaymentMoneySource {@link http://api.yandex.ru/money/doc/dg/reference/request-payment.xml}
 * @package YandexMoney\Domain
 */
class ProcessPaymentMoneySource extends Base
{

    const WALLET = 'wallet';
    const CARDS = 'cards';
    const ITEM = 'item';
    const ITEMS = 'items';
    const ALLOWED = 'allowed';
    const CSC_REQUIRED = 'csc_required';

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return bool
     */
    public function isWalletAllowed()
    {
        $output = false;
        if ($this->checkAndReturn(self::WALLET) != null) {
            $wallet = $this->checkAndReturn(self::WALLET);
            $output = $wallet[self::ALLOWED];
        }
        return $output;
    }

    /**
     * @return bool
     */
    public function areCardsAllowed()
    {
        $output = false;
        if ($this->checkAndReturn(self::CARDS) != null) {
            $cards = $this->checkAndReturn(self::CARDS);
            $output = $cards[self::CARDS];
        }
        return $output;
    }

    /**
     * @return bool
     */
    public function isCscRequired()
    {
        $output = false;
        if ($this->areCardsAllowed()) {
            $cards = $this->checkAndReturn(self::CARDS);
            $output = $cards[self::CSC_REQUIRED];
        }
        return $output;
    }

    /**
     * @return array LinkedCard
     */
    public function getItems()
    {
        $itemsOutput = array();
        if ($this->areCardsAllowed()) {
            $cards = $this->checkAndReturn(self::CARDS);
            if (array_key_exists($cards, self::ITEM)) {
                array_push($itemsOutput, new LinkedCard($cards[self::ITEM]));
            } elseif (array_key_exists($cards, self::ITEMS)) {
                $items = $cards[self::ITEMS];
                foreach ($items as $item) {
                    array_push($itemsOutput, $item);
                }
            }
        }
        return $itemsOutput;
    }
} 