<?php
/*
* Модуль для простой и удобной работы с API Яндекс.Деньги.
* Использует библиотеки: cUrl, Mcrypt. 
*/

include('AES.php'); // модуль шифрования

/**
 * Интерфейс общения приложения с API Яндекс.Деньги.
 * @author dvmelnikov
 */
interface IYandexMoney {

    // URI API Яндекс.Деньги
    const URI_YM_API = 'https://money.yandex.ru/api';
    const URI_YM_AUTH = 'https://sp-money.yandex.ru/oauth/authorize';
    const URI_YM_TOKEN = 'https://sp-money.yandex.ru/oauth/token';

    // Константы для сохранения/восстановления токенов пользователей    
    const TOKEN_STORAGE_FILE = 'yamoney/tokens.json';
    const TOKET_STORAGE_SECRET = 'bigsecret';

    /**
     * Кодировка в которой отправляются запросы на сервер Яндекс.Денег
     * Если ваш веб-сервер работает с другой кодировкой по умолчанию, возможно
     * вам следует изменить значение данной константы.
     * Но в документации к API Яндекс.Денег написано, что рекомендуемая кодировка
     * именно UTF-8.
     */
    const CHARSET = 'UTF-8';

    /**
     * Статический метод OAuth-аутентификации приложения для получения временного
     * кода (токена). Он должен отправлять запрос в Яндекс.Деньги на доступ приложения
     * к эккаунту пользователя и затем сервер Яндекс.Денег сделает редирект на
     * адрес, указанный в параметрах $redirectUri
     * @static
     * @abstract
     * @param $clientId string идентификатор приложения в системе Яндекс.Деньги
     * @param $scope string список запрашиваемых приложением прав. В качестве разделителя
     * элементов списка используется пробел, элементы списка чувствительны к регистру.
     * Примеры прав можно посмотреть в классе Scope.
     * Если параметр не задан, то будут запрашиваться следующие права:
     * account-info operation-history
     * @param $redirectUri string URI страницы приложения, на который OAuth-сервер
     * осуществляет передачу события результата авторизации. Значение этого параметра
     * при посимвольном сравнении должно быть идентично значению redirectUri,
     * указанному при регистрации приложения. При сравнении не учитываются индивидуальные
     * параметры приложения, которые могут быть добавлены в конец строки URI.
     * @return void
     */
    public static function authorize($clientId, $scope = NULL, $redirectUri = NULL);

    /**
     * Конструктор объекта.
     * @abstract
     * @param $clientId string идентификатор приложения в системе Яндекс.Деньги
     * @param $certificateChain string абсолютный путь к файлу сертифика (yamoney/ym.crt)
     * сервера Яндекс.Денег. Пример: D:/PhpApi/yamoney/ym.crt
     */
    public function __construct($clientId, $certificateChain);

    /**
     * Метод для обмена временного кода, полученного от сервера Яндекс.Денег
     * после вызова метода authorize, на постоянный токен доступа к счету
     * пользователя.
     * @abstract
     * @param $code string временный код (токен), подлежащий обмену на токен авторизации.
     * Присутствует в случае успешного подтверждения авторизации пользователем.
     * @param $redirectUri string URI, на который OAuth-сервер осуществляет передачу
     * события результата авторизации. Значение этого параметра при посимвольном сравнении
     * должно быть идентично значению redirectUri, ранее переданному в метод authorize.
     * @return string при успешном выполнении возвращает токен авторизации пользователя
     */
    public function receiveOAuthToken($code, $redirectUri);

    /**
     * Метод получения информации о текущем состоянии счета пользователя.
     * Требуемые права токена: account-info
     * @abstract
     * @param $accessToken string токен авторизации пользователя
     * @return AccountInfoResponse возвращает экземпляр класса AccountInfoResponse
     */
    public function accountInfo($accessToken);

    /**
     * Метод позволяет просматривать историю операций (полностью или частично)
     * в постраничном режиме. Записи истории выдаются в обратном хронологическом
     * порядке. Операции выдаются для постраничного отображения (ограниченное количество).
     * Требуемые права токена: operation-history.
     * @abstract
     * @param $accessToken string токен авторизации пользователя
     * @param $startRecord integer порядковый номер первой записи в выдаче. По умолчанию
     * выдается с первой записи
     * @param $records integer количество запрашиваемых записей истории операций.
     * Допустимые значения: от 1 до 100, по умолчанию 30.
     * @param $type string перечень типов операций, которые требуется отобразить.
     * Типы операций перечисляются через пробел. В случае, если параметр
     * отсутствует, выводятся все операции. Возможные значения: payment deposition.
     * В качестве разделителя элементов списка используется пробел, элементы списка
     * чувствительны к регистру.
     * @return OperationHistoryResponse возвращает экземпляр класса
     * OperationHistoryResponse
     */
    public function operationHistory($accessToken, $startRecord = NULL, $records = NULL, $type = NULL);

    /**
     * Метод получения детальной информации по операции из истории.
     * @abstract
     * @param $accessToken string токен авторизации пользователя
     * @param $operationId string идентификатор операции. Значение параметра соответствует
     * либо значению поля operationId ответа метода operationHistory, либо, в
     * случае если запрашивается история счета плательщика, значению поля
     * paymentId ответа метода processPayment.
     * @return OperationDetailResponse возвращает экземпляр класса
     * OperationDetailResponse
     */
    public function operationDetail($accessToken, $operationId);

    /**
     * Запрос p2p перевода другому пользователю. Перевод на счет пользователя, чей
     * токен указывается в параметрах невозможен (самому себе делать переводы нельзя).
     * @abstract
     * @param $accessToken string токен авторизации пользователя
     * @param $to string номер счета получателя платежа (счет Яндекс.Денег)
     * @param $amount float сумма перевода. Представляет собой число с фиксированной точкой,
     * два знака после точки.
     * @param $comment string название платежа, отображается только в истории платежей
     * отправителя.
     * @param $message string сообщение получателю платежа.
     * @return OperationDetailResponse возвращает экземпляр класса
     * OperationDetailResponse
     */
    public function requestPaymentP2P($accessToken, $to, $amount, $comment, $message);

    /**
     * Метод подтверждения платежа.
     * @abstract
     * @param $accessToken токен авторизации пользователя
     * @param $requestId идентификатор запроса (requestId), полученный с
     * помощью метода requestPaymentP2P.
     * @param $moneySource string запрашиваемый метод проведения платежа:
     * wallet - из кошелька пользователя;
     * card - с привязанной к кошельку банковской карты пользователя.
     * По умолчанию: wallet.
     * @param $csc string Card Security Code, CVV2/CVC2-код привязанной
     * банковской карты пользователя. Параметр требуется указывать только при
     * платеже с привязанной банковской карты (moneySource="card").
     * @return ProcessPaymentResponse возвращает экземпляр класса
     * ProcessPaymentResponse
     */
    public function processPayment($accessToken, $requestId, $moneySource = 'wallet', $csc = NULL);

    /**
     * Метод сохранения токена полученоого в результате метода receiveOAuthToken.
     * Нужен для безопасного хранения токена. Метод шифрует полученный на вход токен
     * и сохраняет в формате json в файл, на который указывает константа
     * TOKEN_STORAGE_FILE. Но будьте внимательны: если вы запускаете примеры на хостинге,
     * то программная запись файлов в каталог по умолчанию может быть запрещена.
     * В качестве ключа (идентификатора) используется параметр $key
     * Шифрование осуществляется алгоритмом AES. Для этого в комплекте с библиотекой
     * поставляются файлы AES.php и Rijndael.php.
     * @abstract
     * @param $key string идентификатор пользователя
     * @param $accessToken string токен авторизации пользователя
     * @return ProcessPaymentResponse возвращает экземпляр класса ProcessPaymentResponse
     */
    public function storeToken($key, $accessToken);

    /**
     * Метод возвращает сохраненный при помощи метода storeToken токен по
     * указанному ключу (идентификатору).
     * @abstract
     * @param $key string идентификатор пользователя
     * @return string возвращает токен авторизации пользователя
     */
    public function restoreToken($key);

    /**
     * Метод возвращает идентификатор приложения в системе Яндекс.Деньги,
     * который был передан в конструкторе класса.
     * @abstract
     * @return string возвращает идентификатор приложения
     */
    public function getClientId();
}

/**
 * Класс для работы с API Яндекс.Деньги. Реализует интерфейс IYandexMoney.
 * @author dvmelnikov
 */
class YandexMoney implements IYandexMoney {

    private $clientId;
    private $certificateChain;

    public function __construct($clientId, $certificateChain) {
        if (!isset($clientId) || $clientId == '') {
            throw new YandexMoneyException(YandexMoneyException::ERR_MESS_CLIENT_ID, 1001);
        }
        $this->clientId = $clientId;

        if (!isset($certificateChain) || $certificateChain == '') {
            throw new YandexMoneyException(YandexMoneyException::ERR_MESS_CERTIFICATE, 1002);
        }
        $this->certificateChain = $certificateChain;
    }

    public function getClientId() {
        return $this->clientId;
    }

    public static function authorize($clientId, $scope = NULL, $redirectUri = NULL) {
        if (!isset($clientId) || $clientId == '') {
            throw new YandexMoneyException(YandexMoneyException::ERR_MESS_CLIENT_ID, 1001);
        }

        if (!isset($scope) || $scope == '') {
            $scope = Scope::ACCOUNT_INFO . Scope::OPERATION_HISTORY;
        }
        $scope = trim($scope);

        header('Location: ' . self::URI_YM_AUTH . "?client_id=$clientId" .
               "&response_type=code&scope=$scope&redirect_uri=$redirectUri");
    }

    public function receiveOAuthToken($code, $redirectUri) {
        $paramArray['grant_type'] = 'authorization_code';
        $paramArray['client_id'] = $this->clientId;
        $paramArray['code'] = $code;
        $paramArray['redirect_uri'] = $redirectUri;
        $params = http_build_query($paramArray);

        $curl = $this->initCurl(self::URI_YM_TOKEN, $this->certificateChain, $params);
        $response = $this->execCurl($curl);
        if (isset($response['error']))
            throw new YandexMoneyException(YandexMoneyException::ERR_MESS_RECEIVE_TOKEN .
                                           ': ' . $response['error'], 1004);
        return $response['access_token'];
    }

    public function accountInfo($accessToken) {
        $curl = $this->initCurl(self::URI_YM_API . '/account-info', $this->certificateChain, NULL, $accessToken);
        $response = $this->execCurl($curl);
        $ai = new AccountInfoResponse($response);
        return $ai;
    }

    public function operationHistory($accessToken, $startRecord = NULL, $records = NULL, $type = NULL) {
        $paramArray = Array();
        if ($type != NULL)
            $paramArray['type'] = $type;
        if ($startRecord != NULL)
            $paramArray['start_record'] = $startRecord;
        if ($records != NULL)
            $paramArray['records'] = $records;
        if (count($paramArray) > 0)
            $params = http_build_query($paramArray);
        else
            $params = '';

        $curl = $this->initCurl(self::URI_YM_API . '/operation-history',
                                $this->certificateChain, $params, $accessToken);
        $response = $this->execCurl($curl);
        $op = new OperationHistoryResponse($response);
        return $op;
    }

    public function operationDetail($accessToken, $operationId) {
        $paramArray['operation_id'] = $operationId;
        $params = http_build_query($paramArray);

        $curl = $this->initCurl(self::URI_YM_API . '/operation-details',
                                $this->certificateChain, $params, $accessToken);
        $response = $this->execCurl($curl);
        $op = new OperationDetailResponse($response);
        return $op;
    }

    public function requestPaymentP2P($accessToken, $to, $amount, $comment, $message) {
        $paramArray['pattern_id'] = 'p2p';
        $paramArray['to'] = $to;
        $paramArray['amount'] = $amount;
        $paramArray['comment'] = $comment;
        $paramArray['message'] = $message;
        $params = http_build_query($paramArray);

        $curl = $this->initCurl(self::URI_YM_API . '/request-payment', $this->certificateChain, $params, $accessToken);
        $response = $this->execCurl($curl);
        $op = new RequestPaymentResponse($response);
        return $op;
    }

    public function processPayment($accessToken, $requestId, $moneySource = 'wallet', $csc = NULL) {
        $cc = '';
        if ($moneySource === 'card') {
            $ms = 'card';
            if ($csc == NULL)
                throw new YandexMoneyException(YandexMoneyException::ERR_MESS_CSC, 1005);
        } else {
            $ms = 'wallet';
            $cc = '';
        }

        $paramArray['request_id'] = $requestId;
        $paramArray['money_source'] = $ms;
        if ($cc !== '')
            $paramArray['csc'] = $cc;
        $params = http_build_query($paramArray);

        $curl = $this->initCurl(self::URI_YM_API . '/process-payment', $this->certificateChain, $params, $accessToken);
        $response = $this->execCurl($curl);
        $op = new ProcessPaymentResponse($response);
        return $op;
    }

    public function storeToken($key, $accessToken) {
        $aes = new Crypt_AES();
        $aes->setKey(self::TOKET_STORAGE_SECRET);
        $encryptedToken = base64_encode($aes->encrypt($accessToken));

        if (file_exists(self::TOKEN_STORAGE_FILE))
            $tokenArray = json_decode(file_get_contents(self::TOKEN_STORAGE_FILE), TRUE);
        else
            $tokenArray = Array();

        $tokenArray[$key] = $encryptedToken;
        $json = json_encode($tokenArray);
        file_put_contents(self::TOKEN_STORAGE_FILE, $json);
    }

    public function restoreToken($key) {
        if (file_exists(self::TOKEN_STORAGE_FILE)) {
            $tokenArray = json_decode(file_get_contents(self::TOKEN_STORAGE_FILE), TRUE);
            if (array_key_exists($key, $tokenArray)) {
                $aes = new Crypt_AES();
                $aes->setKey(self::TOKET_STORAGE_SECRET);
                $decryptedToken = $aes->decrypt(base64_decode($tokenArray[$key]));
                return $decryptedToken;
            } else
                throw new YandexMoneyException(YandexMoneyException::ERR_MESS_TOKEN_NOT_FOUND .
                                               ' with key = ' . $key, 1006);
        }
        else
            throw new YandexMoneyException(YandexMoneyException::ERR_MESS_TOKEN_NOT_FOUND .
                                           ' with key = ' . $key . ' not found', 1006);
    }

    private function initCurl($uri, $certificateChain, $postParams, $accessToken = NULL) {
        $curl = curl_init($uri);

        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=' . self::CHARSET;
        if (isset($accessToken))
            $headers[] = 'Authorization: Bearer ' . $accessToken;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);

        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postParams);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_CAINFO, $certificateChain);

        return $curl;
    }

    private function execCurl($curl) {
        $response = json_decode(curl_exec($curl), TRUE);
        $error = curl_error($curl);
        if ($error != NULL)
            $error = 'cURL error: ' . $error . '; ';
        curl_close($curl);
        if ($response == NULL) {
            throw new YandexMoneyException($error . YandexMoneyException::ERR_MESS_RESPONSE, 1003);
        }
        return $response;
    }

}

/**
 * Класс-перечисление прав приложения на использование эккаунта Яндекс.Денег
 * пользователя.
 */
class Scope {

    const ACCOUNT_INFO = ' account-info';
    const OPERATION_HISTORY = ' operation-history';
    const OPERATION_DETAILS = ' operation-details';
    const PAYMENT = ' payment';
    const PAYMENT_SHOP = ' payment-shop';
    const PAYMENT_P2P = ' payment-p2p';
    const MONEY_SOURCE = ' money-source(wallet card)';

    private static $scopeArray = Array(self::ACCOUNT_INFO,
        self::OPERATION_HISTORY, self::OPERATION_DETAILS,
        self::PAYMENT, self::PAYMENT_SHOP, self::PAYMENT_P2P,
        self::MONEY_SOURCE);

    /**
     * Функция возвращает массив возможных прав для досутупа к эккаунту
     * пользователя. Значения описаны в приватном поле $scopeArray
     * @return Array возвращает массив прав доступа
     */
    public static function getScopeArray() {
        return self::$scopeArray;
    }
}

/**
 * Класс для возврата результата метода accountInfo.
 * Показывает номер счета, текущий баланс и код валюты пользователя
 * (возможные значения кода валюты: 643 - российский рубль).
 * @author dvmelnikov
 */
class AccountInfoResponse {

    protected $account;
    protected $balance;
    protected $currency;

    public function __construct($accountInfoArray) {
        if (isset($accountInfoArray['account']))
            $this->account = $accountInfoArray['account'];
        if (isset($accountInfoArray['balance']))
            $this->balance = $accountInfoArray['balance'];
        if (isset($accountInfoArray['currency']))
            $this->currency = $accountInfoArray['currency'];
    }

    /**
     * @return string возвращает номер счета пользователя
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * @return string возвращает количество денег на счету
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * @return string возвращает код валюты пользователя
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @return string возвращает содержимое объекта в качестве строки
     */
    public function __toString() {
        return "AccountInfoResponse{account: $this->account, balance:
                $this->balance, currency: $this->currency}";
    }
}

/**
 * Класс для возврата результата метода operationHistory.
 * Содержит ошибку, если таковая была получена в результате
 * запроса, номер следующей записи для следующего запроса и
 * список операций.
 * При значительном количестве записей в истории список операций
 * выдается постранично. По умолчанию выдается первая страница истории.
 * Если есть хотя бы одна последующая страница, то в ответе присутствует
 * параметр nextRecord, определяющий порядковый номер ее первой записи.
 * Для запроса следующей страницы истории повторите запрос с теми же
 * параметрами, добавив параметр startRecord и указав в нем порядковый
 * номер первой записи следующей страницы, полученный ранее
 * из параметра nextRecord.
 * @author dvmelnikov
 */
class OperationHistoryResponse {

    protected $error;
    protected $nextRecord;
    protected $operations;

    public function __construct($operationsArray) {
        if (isset($operationsArray['error']))
            $this->error = $operationsArray['error'];
        if (isset($operationsArray['next_record']))
            $this->nextRecord = $operationsArray['next_record'];

        if (isset($operationsArray['operations'])) {
            foreach ($operationsArray['operations'] as $operation) {
                $this->operations[] = new Operation($operation);
            }
        }
    }

    /**
     * @return string возвращает код ошибки
     * Возможные значения:
     * illegal_param_type - неверное значение параметра type метода
     * operationHistory;
     * illegal_param_start_record - неверное значение параметра startRecord
     * метода operationHistory;
     * illegal_param_records ― неверное значение параметра records;
     * Все прочие значения: техническая ошибка, повторите вызов операции позднее.
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @return integer возвращает порядковый номер первой записи на следующей
     * странице истории операций. Присутствует, только если следующая
     * страница существует.
     */
    public function getNextRecord() {
        return $this->nextRecord;
    }

    /**
     * @return Array возвращает массив объектов Operation
     */
    public function getOperations() {
        return $this->operations;
    }

    /**
     * @return string возвращает содержимое объекта в качестве строки
     */
    public function __toString() {
        return "OperationHistoryResponse{error: $this->error, nextRecord:
                $this->nextRecord, Operations count: " . count($this->operations) .
               "}";
    }
}

/**
 * Класс для получения информации о конкретной операции из списка операций
 * метода operationHistory. Используется в объекте OperationHistoryResponse
 * @author dvmelnikov
 */
class Operation {

    protected $operationId;
    protected $patternId;
    protected $direction;
    protected $amount;
    protected $datetime;
    protected $title;

    public function __construct($operation) {
        if (isset($operation['operation_id']))
            $this->operationId = $operation['operation_id'];
        if (isset($operation['pattern_id']))
            $this->patternId = $operation['pattern_id'];
        if (isset($operation['title']))
            $this->title = $operation['title'];
        if (isset($operation['direction']))
            $this->direction = $operation['direction'];
        if (isset($operation['amount']))
            $this->amount = $operation['amount'];
        if (isset($operation['datetime']))
            $this->datetime = strtotime($operation['datetime']);
    }

    /**
     * @return string возвращает идентификатор операции
     */
    public function getOperationId() {
        return $this->operationId;
    }

    /**
     * @return string возвращает идентификатор шаблона платежа,
     * по которому совершен платеж. Присутствует только для платежей.
     * Для перевода между счетами пользователей значение: p2p.
     * В остальных случая это операции с магазинами.
     */
    public function getPatternId() {
        return $this->patternId;
    }

    /**
     * @return string возвращает направление движения средств.
     * Может принимать значения:
     * in (приход);
     * out (расход).
     */
    public function getDirection() {
        return $this->direction;
    }

    /**
     * @return string возвращает сумму операции
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return integer возвращает дату и время совершения операции.
     */
    public function getDatetime() {
        return $this->datetime;
    }

    /**
     * @return string возвращает краткое описание операции (название
     * магазина или источник пополнения).
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string возвращает содержимое объекта в качестве строки
     */
    public function __toString() {
        return "Operation{operationId: $this->operationId, patternId: $this->patternId,
                direction: $this->direction, amount: $this->amount, datetime: 
                $this->datetime, title: $this->title}";
    }

}

/**
 * Класс для возврата результата метода operationDetail. Содержит
 * информацию о конкретной операции из списка.
 * @author dvmelnikov
 */
class OperationDetailResponse extends Operation {

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

    /**
     * @return string возвращает содержимое объекта в качестве строки
     */
    public function __toString() {
        $parent = parent::__toString();
        return "OperationDetailResponse{error: $this->error, details: $this->details,
                sender: $this->sender, recipient: $this->recipient, message: 
                $this->message, codepro: $this->codepro}" . $parent;
    }

}

/**
 * Класс для возврата результата метода requestPayment
 * @author dvmelnikov
 */
class RequestPaymentResponse {

    protected $status;
    protected $error;
    protected $moneySource;
    protected $requestId;
    protected $contract;
    protected $balance;

    public function __construct($responseArray) {
        if (isset($responseArray['status']))
            $this->status = $responseArray['status'];
        if (isset($responseArray['error']))
            $this->error = $responseArray['error'];
        if (isset($responseArray['money_source']))
            $this->moneySource = $responseArray['money_source'];
        if (isset($responseArray['request_id']))
            $this->requestId = $responseArray['request_id'];
        if (isset($responseArray['contract']))
            $this->contract = $responseArray['contract'];
        if (isset($responseArray['balance']))
            $this->balance = $responseArray['balance'];
    }

    /**
     * @return string возвращает код результата выполнения операции.
     * Возможные значения:
     * success - успешное выполнение;
     * refused - отказ в проведении платежа, объяснение причины отказа
     * содержится в поле error. Это конечное состояние платежа.
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return string возвращает код ошибки при проведении платежа
     * (пояснение к полю status). Присутствует только при ошибках.
     * Возможные значения:
     * illegal_params ― отсутствуют или имеют недопустимые значения
     * обязательные параметры платежа;
     * payment_refused ― магазин отказал в приеме платежа (например
     * пользователь попробовал заплатить за товар, которого нет в магазине).
     * Все прочие значения: техническая ошибка, повторите платеж
     * через несколько минут.
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @return string возвращает доступные для приложения методы
     * проведения платежа (wallet или card). Присутствует только при
     * успешном выполнении метода requestPayment.
     */
    public function getMoneySource() {
        return $this->moneySource;
    }

    /**
     * @return string возвращает идентификатор запроса платежа,
     * сгенерированный системой. Присутствует только при успешном
     * выполнении метода requestPayment.
     */
    public function getRequestId() {
        return $this->requestId;
    }

    /**
     * @return string возвращает текст описания платежа (контракт).
     * Присутствует только при успешном выполнении метода requestPayment.
     */
    public function getContract() {
        return $this->contract;
    }

    /**
     * @return string возвращает текущий остаток на счете пользователя.
     * Присутствует только при успешном выполнении метода requestPayment.
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * @return string возвращает содержимое объекта в качестве строки
     */
    public function __toString() {
        return "RequestPaymentResponse{status: $this->status, error: $this->error,
                moneySource: $this->moneySource, requestId: $this->requestId, 
                contract: $this->contract, balance: $this->balance}";
    }

}

/**
 * Класс для возврата результата метода processPayment
 * @author dvmelnikov
 */
class ProcessPaymentResponse {

    protected $status;
    protected $error;
    protected $paymentId;
    protected $balance;
    protected $payer;
    protected $payee;
    protected $creditAmount;

    public function __construct($responseArray) {
        if (isset($responseArray['status']))
            $this->status = $responseArray['status'];
        if (isset($responseArray['error']))
            $this->error = $responseArray['error'];
        if (isset($responseArray['payment_id']))
            $this->paymentId = $responseArray['payment_id'];
        if (isset($responseArray['balance']))
            $this->balance = $responseArray['balance'];
        if (isset($responseArray['payer']))
            $this->payer = $responseArray['payer'];
        if (isset($responseArray['payee']))
            $this->payee = $responseArray['payee'];
        if (isset($responseArray['credit_amount']))
            $this->creditAmount = $responseArray['credit_amount'];
    }

    /**
     * @return string возвращает код результата выполнения операции.
     * Возможные значения:
     * success - успешное выполнение (платеж проведен). Это конечное состояние платежа;
     * refused - отказ в проведении платежа, объяснение причины отказа
     * содержится в поле error. Это конечное состояние платежа;
     * in_progress - авторизация платежа находится в процессе выполнения.
     * Приложению следует повторить запрос с теми же параметрами спустя некоторое время;
     * все прочие значения - состояние платежа неизвестно. Приложению
     * следует повторить запрос с теми же параметрами спустя некоторое время.
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return string возвращает код ошибки при проведении
     * платежа (пояснение к полю status). Присутствует только при ошибках.
     * Возможные значения:
     * contract_not_found - отсутствует выставленный контракт с заданным requestId;
     * not_enough_funds - недостаточно средств на счете плательщика;
     * limit_exceeded - превышен лимит на сумму операции или сумму операций за
     * период времени для выданного токена авторизации. Приложение должно
     * отобразить соответствующее диалоговое окно.
     * money_source_not_available - запрошенный метод платежа (money_source)
     * недоступен для данного платежа.
     * illegal_param_csc - отсутствует или указано недопустимое значение параметра csc;
     * payment_refused - магазин по какой-либо причине отказал в приеме платежа;
     * authorization_reject - в авторизации платежа отказано. Истек срок действия
     * карты, либо банк-эмитент отклонил транзакцию по карте,
     * либо превышен лимит платежной системы для данного пользователя.
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @return string возвращает идентификатор проведенного платежа.
     * Присутствует только при успешном выполнении метода.
     */
    public function getPaymentId() {
        return $this->paymentId;
    }

    /**
     * @return string возвращает остаток на счете пользователя после
     * проведения платежа. Присутствует только при успешном выполнении метода.
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * @return string возвращает номер счета плательщика. Присутствует
     * только при успешном выполнении метода.
     */
    public function getPayer() {
        return $this->payer;
    }

    /**
     * @return string возвращает номер счета получателя. Присутствует
     * только при успешном выполнении метода.
     */
    public function getPayee() {
        return $this->payee;
    }

    /**
     * @return string возвращает сумму, полученную на счет получателем.
     * Присутствует при успешном переводе средств на счет другого
     * пользователя системы.
     */
    public function getCreditAmount() {
        return $this->creditAmount;
    }

    /**
     * @return string возвращает содержимое объекта в качестве строки
     */
    public function __toString() {
        return "ProcessPaymentResponse{status: $this->status, error: $this->error,
                paymentId: $this->paymentId, balance: $this->balance, payer: $this->payer, 
                payee: $this->payee, creditAmount: $this->creditAmount}";
    }

}

/**
 * Эксепшн и возможные тексты ошибок
 */
class YandexMoneyException extends Exception {
    const ERR_MESS_CLIENT_ID = 'client_id is empty'; // code = 1001
    const ERR_MESS_CERTIFICATE = 'path to certificate is empty'; // code = 1002
    const ERR_MESS_RESPONSE = 'response decoding failed'; // code = 1003
    const ERR_MESS_RECEIVE_TOKEN = 'error receiving token'; // code = 1004
    const ERR_MESS_CSC = 'csc is empty'; // code = 1005
    const ERR_MESS_TOKEN_NOT_FOUND = 'token not found'; // code = 1006
}

?>
