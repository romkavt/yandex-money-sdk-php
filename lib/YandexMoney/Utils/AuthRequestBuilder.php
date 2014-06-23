<?php

namespace YandexMoney\Utils;


class AuthRequestBuilder
{

    /**
     *  Fixed value
     */
    const RESPONSE_TYPE = "code";

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $redirectUri;


    /**
     * @var string
     */
    private $rights;

    /**
     * @param string $rights
     */
    public function setRights($rights)
    {
        $this->rights = $rights;
    }

    /**
     * @return string
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

}