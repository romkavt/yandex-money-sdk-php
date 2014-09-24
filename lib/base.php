<?php 
namespace YandexMoney;

require_once __DIR__ . "/exceptions.php";

class BaseAPI {
    const MONEY_URL = "https://money.yandex.ru";
    const SP_MONEY_URL = "https://sp-money.yandex.ru";
    
    public static function sendRequest($url, $options=array(), $access_token=NULL) {
        $full_url= self::MONEY_URL . $url;
        if($access_token != NULL) {
            $headers = array(
                "Authorization" => sprintf("Bearer %s", $access_token),
            );
        } 
        else {
            $headers = array();
        }
        $result = \Requests::post($full_url, $headers, $options);
        return self::processResult($result);
    }
    protected static function processResult($result) {
        switch ($result->status_code) {
            case 400:
                throw new Exceptions\FormatError; 
                break;
            case 401:
                throw new Exceptions\TokenError; 
                break;
            case 403:
                throw new Exceptions\ScopeError; 
                break;
        }
        return json_decode($result->body);
    }
}
