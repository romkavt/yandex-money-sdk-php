<?php

namespace YandexMoney\Response;


class IncomingTransferAcceptResponse extends BaseResponse
{

    const INCOMING_TRANSFER_ACCEPT = 'incoming-transfer-accept';
    const EXT_ACTION_URI = 'ext_action_uri';
    const PROTECTION_CODE_ATTEMPTS_AVAILABLE = "protection_code_attempts_available";

    public function __construct(array $responseParams)
    {
        $this->params = $responseParams;
    }

    /**
     * @return integer|null
     */
    public function getProtectionCodeAttemptsAvailable()
    {
        return $this->checkAndReturn(self::PROTECTION_CODE_ATTEMPTS_AVAILABLE);
    }

    /**
     * @return integer|null
     */
    public function getIncomingTransferAccept()
    {
        return $this->checkAndReturn(self::INCOMING_TRANSFER_ACCEPT);
    }

    /**
     * @return string|null
     */
    public function getExtActionUri()
    {
        return $this->checkAndReturn(self::EXT_ACTION_URI);
    }
}