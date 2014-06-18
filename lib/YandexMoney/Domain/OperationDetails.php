<?php
/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/26/14
 * Time: 11:11 PM
 */

namespace YandexMoney\Domain;

use YandexMoney\Response\ResponseInterface;

/**
 * Class OperationDetail {@link http://api.yandex.ru/money/doc/dg/reference/operation-details.xml}
 * @package YandexMoney\Domain
 */
class OperationDetails extends Operation
{
    const ERROR = 'error';
    const SENDER = 'sender';
    const RECIPIENT = 'recipient';
    const RECIPIENT_TYPE = 'recipient_type';
    const MESSAGE = 'message';
    const CODEPRO = 'codepro';
    const DETAILS = 'details';
    const AMOUNT_DUE = 'amount_due';
    const FEE = 'fee';
    const COMMENT = 'comment';
    const PROTECTION_CODE = 'protection_code';
    const EXPIRES = 'expires';
    const ANSWER_DATETIME = 'answer_datetime';
    const DIGITAL_GOODS = 'digital_goods';

    /**
     * @param array $operationArray
     */
    public function __construct(array $operationArray)
    {
        parent::__construct($operationArray);
    }

    /**
     * @return float|null
     */
    public function getAmountDue()
    {
        return $this->checkAndReturn(self::AMOUNT_DUE);
    }

    /**
     * @return float|null
     */
    public function getFee()
    {
        return $this->checkAndReturn(self::FEE);
    }

    /**
     * @return string|null
     */
    public function getComment()
    {
        return $this->checkAndReturn(self::COMMENT);
    }

    /**
     * @return string|null
     */
    public function getProtectionCode()
    {
        return $this->checkAndReturn(self::PROTECTION_CODE);
    }

    /**
     * @return string|null
     */
    public function getExpires()
    {
        return $this->checkAndReturn(self::EXPIRES);
    }

    /**
     * @return string|null
     */
    public function getAnswerDatetime()
    {
        return $this->checkAndReturn(self::ANSWER_DATETIME);
    }

    /**
     * @return array string|null
     */
    public function getDigitalGoods()
    {
        return $this->checkAndReturn(self::DIGITAL_GOODS);
    }

    /**
     * @return string возвращает детальное описание платежа.
     * Строка произвольного формата, может содержать любые символы и
     * переводы строк.
     */
    public function getDetails()
    {
        return $this->checkAndReturn(self::DETAILS);
    }

    /**
     * @return string возвращает код ошибки, присутствует при ошибке выполнения запроса.
     * Возможные значения: illegal_param_operation_id  неверное значение
     * параметра operation_id.
     */
    public function getError()
    {
        return $this->checkAndReturn(self::ERROR);
    }

    /**
     * @return string возвращает номер счета отправителя перевода. Присутствует для
     * входящих переводов от других пользователей.
     */
    public function getSender()
    {
        return $this->checkAndReturn(self::SENDER);
    }

    /**
     * @return string возвращает номер счета отправителя перевода. Присутствует для
     * входящих переводов от других пользователей.
     */
    public function getRecipient()
    {
        return $this->checkAndReturn(self::RECIPIENT);
    }

    /**
     * @return string|null
     */
    public function getRecipientType()
    {
        return $this->checkAndReturn(self::RECIPIENT_TYPE);
    }

    /**
     * @return string возвращает комментарий к переводу. Присутствует для
     * переводов другим пользователям.
     */
    public function getMessage()
    {
        return $this->checkAndReturn(self::MESSAGE);
    }

    /**
     * @return string возвращает перевод защищен кодом протекции.
     * Присутствует для переводов другим пользователям.
     */
    public function getCodepro()
    {
        return $this->checkAndReturn(self::CODEPRO);
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess()
    {
        return $this->checkAndReturn(self::ERROR) === null;
    }
}
