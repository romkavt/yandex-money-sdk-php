<?php 
namespace YandexMoney;

require_once __DIR__ . "/base.php";

/**
 * The external bank cards payment API
 */
class ExternalPayment extends BaseAPI {
    function __construct($instance_id) {
        $this->instance_id = $instance_id;
    }

    /**
     * Registers an instance of an application
     *
     * @see http://api.yandex.com/money/doc/dg/reference/instance-id.xml
     * @see https://tech.yandex.ru/money/doc/dg/reference/instance-id-docpage/
     * @param string $client_id The client_id that was assigned to the application.
     * @throws Exceptions/ServerError If status code >= 500
     * @return response object
     */
    public static function getInstanceId($client_id) {
        return self::sendRequest("/api/instance-id",
            array("client_id" => $client_id));
    }

    /**
     * Requests an external payment.
     *
     * @see http://api.yandex.com/money/doc/dg/reference/request-external-payment.xml
     * @see https://tech.yandex.ru/money/doc/dg/reference/request-external-payment-docpage/
     * @param array[] $payment_options Key-value parameters collection
     * @throws Exceptions/ServerError If status code >= 500
     * @return response object
     */
    public function request($payment_options) {
        $payment_options['instance_id']= $this->instance_id;
        return self::sendRequest("/api/request-external-payment",
            $payment_options);
    }

    /**
     * Requests an external payment.
     *
     * @see http://api.yandex.com/money/doc/dg/reference/request-external-payment.xml
     * @see https://tech.yandex.ru/money/doc/dg/reference/request-external-payment-docpage/
     * @param array[] $payment_options Key-value parameters collection
     * @throws Exceptions/ServerError If status code >= 500
     * @return response object
     */
    public function process($payment_options) {
        $payment_options['instance_id']= $this->instance_id;
        return self::sendRequest("/api/process-external-payment",
            $payment_options);
    }
}
