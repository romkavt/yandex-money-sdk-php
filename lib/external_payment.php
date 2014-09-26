<?php 
namespace YandexMoney;

require_once __DIR__ . "/base.php";

class ExternalPayment extends BaseAPI {
    function __construct($instance_id) {
        $this->instance_id = $instance_id;
    }
    public static function getInstanceId($client_id) {
        return self::sendRequest("/api/instance-id",
            array("client_id" => $client_id));
    }
    public function request($payment_options) {
        $payment_options['instance_id']= $this->instance_id;
        return self::sendRequest("/api/request-external-payment",
            $payment_options);
    }
    public function process($payment_options) {
        $payment_options['instance_id']= $this->instance_id;
        return self::sendRequest("/api/process-external-payment",
            $payment_options);
    }
}
