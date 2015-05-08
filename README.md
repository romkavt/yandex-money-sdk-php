

[![Build Status](https://travis-ci.org/yandex-money/yandex-money-sdk-php.svg?branch=master)](https://travis-ci.org/yandex-money/yandex-money-sdk-php)

# PHP Yandex.Money API SDK

## Requirements

PHP 5.3 or above


## Links

1. Yandex.Money API page: [Ru](http://api.yandex.ru/money/),
[En](http://api.yandex.com/money/)
2. [sample app](https://github.com/yandex-money/yandex-money-sdk-php-sample)

## Getting started

### Installation

1. Add `"yandex-money/yandex-money-sdk-php": "3.0.*"` to `composer.json` of your application. Or clone repo to your project.
2. If you are using composer - simply use `require_once 'vendor/autoload.php';` otherwise paste following code
    ```php
    // For payments from the Yandex.Money wallet
    require_once '/path/to/cloned/repo/lib/api.php';

    // For payments from bank cards without authorization
    require_once '/path/to/cloned/repo/lib/external_payment.php';
    ```

### Payments from the Yandex.Money wallet

Using Yandex.Money API requires following steps

1. Obtain token URL and redirect user's browser to Yandex.Money service.
Note: `client_id`, `redirect_uri`, `client_secret` are constants that you get,
when [register](https://sp-money.yandex.ru/myservices/new.xml) app in Yandex.Money API.

    ```php
    use \YandexMoney\API;

    $auth_url = API::buildObtainTokenUrl($client_id, $redirect_uri, $scope);
    ```

2. After that, user fills Yandex.Money HTML form and user is redirected back to
`REDIRECT_URI?code=CODE`.

3. You should immediately exchange `CODE` with `ACCESS_TOKEN`.

    ```php
    $access_token_response = API::getAccessToken($client_id, $code, $redirect_uri, $client_secret=NULL);
    if(property_exists($access_token_response, "error")) {
        // process error
    }
    $access_token = $access_token_response->access_token;
    ```

4. Now you can use Yandex.Money API.

    ```php
    $api = new API($access_token);

    // get account info
    $acount_info = $api->accountInfo();

    // check status 

    // get operation history with last 3 records
    $operation_history = $api->operationHistory(array("records"=>3));

    // check status 

    // make request payment
    $request_payment = $api->requestPayment(array(
        "pattern_id" => "p2p",
        "to" => $money_wallet,
        "amount_due" => $amount_due,
        "comment" => $comment,
        "message" => $message,
        "label" => $label,
    ));

    // check status 

    // call process payment to finish payment
    $process_payment = $api->processPayment(array(
        "request_id" => $request_payment->request_id,
    ));
    ```

### Payments from bank cards without authorization

1. Fetch instantce-id(ussually only once for every client. You can store
result in DB).

    ```php
    use \YandexMoney\ExternalPayment;

    $response = ExternalPayment::getInstanceId($client_id);
    if($response->status == "success") {
        $instance_id = $response->instance_id;
    }
    else {
        // throw exception with $response->error message
    }
    ```

2. Make request payment

    ```php
    // make instance
    $external_payment = ExternalPayment($instance_id);

    $payment_options = array(
        // pattern_id, etc..
    );
    $response = $external_payment->request($payment_options);
    if($response->status == "success") {
        $request_id = $response->request_id;
    }
    else {
        // throw exception with $response->message
    }
    ```

3. Process the request with process-payment. 

    ```php
    $process_options = array(
        "request_id" => $request_id
        // other params..
    );
    $result = $external_payment->process($process_options);
    // process $result according to docs
    ```

## Side notes

1. Library throws exceptions in case of
    * response status isn't equal 2**
    * I/O error(see [requests](https://github.com/rmccue/Requests))
2. If you register app and fill `CLIENT_SECRET` entry then you should
provide `$client_secret` explicitly where `$client_secret=NULL`
3. You should wrap all passed boolean values in quotes(because php converts
them to numbers otherwise). For example:

```php
API($access_token).requestPayment(array(
    test_payment => "true",
    // other params
));
```


## Running tests

1. Clone this repo.
2. Install composer
3. Run `composer install`
4. Make sure `phpunit` executable is present in your `$PATH`
5. Create `tests/constants.php` with `CLIENT_ID`, `CLIENT_SECRET` and `ACCESS_TOKEN`
constants. 
6. Run tests `phpunit --bootstrap vendor/autoload.php tests/`
