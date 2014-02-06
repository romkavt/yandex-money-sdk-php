<?php

class YM_OperationDetail extends YM_Operation {
    protected $error;
    protected $sender;
    protected $recipient;
    protected $message;
    protected $codepro;
    protected $details;

    public function __construct($operation) {
        parent::__construct($operation);
        if (isset($operation['error']))
            $this->error = $operation['error'];
        if (isset($operation['sender']))
            $this->sender = $operation['sender'];
        if (isset($operation['recipient']))
            $this->recipient = $operation['recipient'];
        if (isset($operation['message']))
            $this->message = $operation['message'];
        if (isset($operation['codepro']))
            $this->codepro = $operation['codepro'];
        if (isset($operation['details']))
            $this->details = $operation['details'];
    }

    /**
     * @return string возвращает детальное описание платежа.
     * Строка произвольного формата, может содержать любые символы и
     * переводы строк.
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * @return string возвращает код ошибки, присутствует при ошибке выполнения запроса.
     * Возможные значения: illegal_param_operation_id  неверное значение
     * параметра operation_id.
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @return string возвращает номер счета отправителя перевода. Присутствует для
     * входящих переводов от других пользователей.
     */
    public function getSender() {
        return $this->sender;
    }

    /**
     * @return string возвращает номер счета отправителя перевода. Присутствует для
     * входящих переводов от других пользователей.
     */
    public function getRecipient() {
        return $this->recipient;
    }

    /**
     * @return string возвращает комментарий к переводу. Присутствует для
     * переводов другим пользователям.
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return string возвращает перевод защищен кодом протекции.
     * Присутствует для переводов другим пользователям.
     */
    public function getCodepro() {
        return $this->codepro;
    }

    public function isSuccess() {
        return $this->error === null;
    }
}
