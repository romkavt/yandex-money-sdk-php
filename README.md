# Simplified PHP Yandex.Money API SDK

## Requirements

1. PHP 5.3 or above
2. composer
3. rmccue/requests

## Links

1. Yandex.Money API page: [Ru](http://api.yandex.ru/money/), [En](http://api.yandex.com/money/)
2. [sample app](https://github.com/raymank26/yandex-money-php-simplified-sample)

## Getting started

### Workflow for authenticated payments

Using Yandex.Money API requires following steps

1. Call `API::buildObtainTokenUrl` and point user browser to resulted url. Where `$client_id`, `$redirect_uri`, `$client_secret` are
parameters that you get, when [register](https://sp-money.yandex.ru/myservices/new.xml) your app in Yandex.Money API.

```php
$auth_url = API::buildObtainTokenUrl($client_id, $redirect_uri,
    $client_secret=NULL, $scope)
```

2. After that, user fills Yandex.Money form and Yandex.Money service redirects browser
to `$redirect_uri` on your server with `code` GET param.

3. You should immediately exchange `$code` with `$access_token` using `getAccessToken`
```php
$access_token = API::getAccessToken($client_id, $code, $redirect_uri,
            $client_secret=NULL)
```
Feel free to save `$access_token` in your database. But don't show `$access_token`
to anybody.

4. Now you can use Yandex.Money API
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

### Workflow for non-authenticated payments

1. Fetch instantce-id(ussually only once for every client. You can store
result in DB).

```php
$response = ExternalPayment::getInstanceId($client_id);
if($reponse->status == "success") {
    $instance_id = $response->instance_id;
}
else {
    // throw exception with $reponse->error message
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





