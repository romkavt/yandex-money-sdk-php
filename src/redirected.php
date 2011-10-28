<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Страница для OAuth редиректа Яндекс.Денег</title>
</head>
<body>
	<div id="main">
		<h3 id="header">Страница OAuth редиректа после авторизации и примеры вызовов API Яндекс.Денег</h3>

		<p>
			После нажатия кнопки  "Получить токен" на предыдущей форме, мы 
			отправили запрос в файл <i>auth.php</i>. В нем из POST-параметров, 
			которые мы указали в форме, формируется строка прав <i>$scope</i> и,
			затем делается главный вызов:
			<pre class="code">YandexMoney::authorize(Consts::CLIENT_ID, $scope, Consts::REDIRECT_URL);</pre>
			При вызове этого метода мы указываем идентификатор нашего приложения <i>CLIENT_ID</i>, 
			права доступа <i>$scope</i> и адрес редиректа <i>REDIRECT_URL</i> (адрес на эту страницу 
			redirected.php).<br>
			Далее метод шлет запрос на сервер Яндекс.Денег, там 
			пользователь подтверждает разрешение на доступ к его деньгам и в ответ на страницу 
			редиректа в GET-параметрах приходит временный код, который нужно обменять на 
			постоянный токен доступа. Выводим полученный code на экран:
		</p>

		<p class="output">
			<?php
			echo 'code: ';
			print_r($_GET['code']);
			if (array_key_exists('error', $_GET)) {
	    		echo '<br>error: ';
	    		print_r($_GET['error']);
			}
			?>
		</p>

		<p>
			Как видно выше, в параметрах редиректа мы получили сообщение об ошибке
    		или некий <i>code</i>. Это временный токен, который имеет очень короткое время
    		жизни, поэтому его нужно сразу обменять на постоянный токен. Что мы и сделаем
    		дальше при помощи функции <i>recieveAuthToken</i> класса <i>YandexMoney</i>. 
    		Приведем листинг данного кода:    	
			<pre class="code">
require_once 'consts.php';
require_once 'yamoney/ym.php';

$ym = new YandexMoney(Consts::CLIENT_ID, Consts::CERTIFICATE_CHAIN_PATH);
$token = $ym->receiveOAuthToken($_GET['code'], Consts::REDIRECT_URL);
print_r('Наш токен выглядит так: ' . $token . '&lt;br&gt;');</pre>
			В этом коде мы создаем объект класса <i>YandexMoney</i> и вызываем функцию
			получения постоянного токена OAuth авторизации <i>receiveOAuthToken</i>, а после
			выводим результат на экран. <br>
			Приведем результат работы данного кода:
		</p>
		
		<p class="output">    
			<?php
	        require_once 'consts.php';
			require_once 'yamoney/ym.php';

			$ym = new YandexMoney(Consts::CLIENT_ID, Consts::CERTIFICATE_CHAIN_PATH);
			$token = $ym->receiveOAuthToken($_GET['code'], Consts::REDIRECT_URL);
			print_r('Наш токен выглядит так: ' . $token . '<br>');
			?>
		</p>

		<p>
			Если никаких ошибок не произошло и мы видим токен, то на этом OAuth 
			авторизация в API Яндекс.Денег успешно завершена. <br>
			Далее приложению стоит сохранить куда-нибудь полученный токен, например, в 
			базу данных, ассоциируя токен с запросившем его пользователем. 
			В библиотеке <i>ym.php</i> есть методы для сохранения и восстановления токенов:
			<i>storeToken</i> и <i>restoreToken</i>. Правда сохранение/восстановление
			происходит не в базу, а в json-файл лежащий по умолчанию в каталоге библиотеки (за
			местоположение файла отвечает константа <i>TOKEN_STORAGE_FILE</i>. Но будьте внимательны: 
			если вы запускате примеры на хостинге, то программная запись файлов в данный каталог 
			скорее всего запрещена. <br />  
			Данные при сохранении шифруются (ключ шифрования зашит в коде, за него отвечает константа 
			<i>TOKET_STORAGE_SECRET</i>. Если вы хотите сохранять токены в базу, то стоит переписать
			реализацию данных методов, помня о важности безопасности токенов пользователей.
		</p>
    	
    	<p>
    		Покажем как можно сохранить и восстановить токен пользователя
    		с помощью методов storeToken и restoreToken:
    		<pre class="code">
print "Сохраняем полученный токен с идентификатором пользователя PolikarpStepahovich. &lt;br&gt;";
$ym->storeToken("PolikarpStepanovich", $token);
print_r("Токен пользователя PolikarpStepanovich: " . $ym->restoreToken("user321") . '&lt;br&gt;');</pre>
			Правда данный код выполнять не будем, 
			закомментируем. Очень вероятна ошибка сохранинения файла (на хостингах), но вы можете 
			на свой страх и риск раскомментировать и попробовать. 
    	</p>
    	
    	<p class="output">	
			<?php
			//print "Сохраняем полученный токен с идентификатором пользователя PolikarpStepahovich. <br>";
			//$ym->storeToken("PolikarpStepanovich", $token);
			//print_r("Токен пользователя PolikarpStepanovich: " . $ym->restoreToken("PolikarpStepanovich") . '<br>');
			?>	
		</p>

		<p>
			А теперь, когда мы покончили с авторизацией и добыли токен, давайте 
			посмотрим как пользоваться непосредственно API. 
			Попробуем получить информацию о счете пользователя, чей токен мы только что
			получили. Для этого вызываем метод <i>accountInfo</i>, листинг кода:
			<pre class="code">
$resp = $ym->accountInfo($token);
print_r('Номер счета: ' . $resp->getAccount() . '&lt;br&gt;');
print_r('Баланс: ' . $resp->getBalance() . '&lt;br&gt;');
print_r('Код валюты: ' . $resp->getCurrency() . '&lt;br&gt;');</pre>
			Результат выполнения кода:
		</p>

		<p class="output">
			<?php
	    		$resp = $ym->accountInfo($token);			
				print_r('Номер счета: ' . $resp->getAccount() . '<br>');
				print_r('Баланс: ' . $resp->getBalance() . '<br>');
				print_r('Код валюты: ' . $resp->getCurrency() . '<br>');				
			?>
		</p>

		<p>
			А здесь попробуем достать историю операций нашего пользователя.
    		Для этого вызовем метод <i>operationHistory</i>. Подробное описание
    		параметров метода можно посмотреть в документации исходного кода в файле
    		<i>ym.php</i>. Листинг кода:
    		<pre class="code">
$resp = $ym->operationHistory($token, 0, 5);
print_r($resp . "&lt;br&gt;");
print "Operations (count = " . count($resp->getOperations()) . "): &lt;br&gt;";
$op = $resp->getOperations();
foreach ($op as $o) {
	print_r('' . $o . '&lt;br&gt;');
}</pre>
			В этом примере кода мы не выводим все поля полученного в 
			результате выполнения метода полей, а сразу выводим объект, а затем
			перебираем в цикле все операции и выводим их (тоже целиком, не используя 
			геттеры полей). Результат выполнения:
    	</p>

		<p class="output">
			<?php
	        $resp = $ym->operationHistory($token, 0, 5);
			print_r($resp . "<br>");
			print "Operations (count = " . count($resp->getOperations()) . "): <br>";
			$op = $resp->getOperations();
			foreach ($op as $o) {
	    		print_r('' . $o . '<br>');
			}
			?>
		</p>

		<p>
			Можно вытащить детальную информацию по какому-нибудь конкретному
    		платежу. Возьмем operationId из объекта Operation предыдущего примера и
    		вызовем функцию <i>operationDetail</i>. Листинг кода:
    		<pre class="code">
$resp = $ym->operationDetail($token, $op[0]->getOperationId());
print_r('Сообщение: ' . $resp->getMessage() . '&lt;br&gt;');
print_r('Название операции: ' . $resp->getTitle() . '&lt;br&gt;');
print_r('Объект OperationDetailResponse целиком: ' . $resp);</pre>
    		Результат выполнения:
    	</p>
		
		<p class="output">
			<?php
			$resp = $ym->operationDetail($token, $op[0]->getOperationId());
			print_r('Сообщение: ' . $resp->getMessage() . '<br>');
			print_r('Название операции: ' . $resp->getTitle() . '<br>');
			print_r('Объект OperationDetailResponse целиком: ' . $resp);
			?>
		</p>

		<p>
			Теперь сделаем перевод p2p (peer-to-peer). Для этого нам нужен счет
    		другого пользователя (например, возьмем счет 41001901291751) и сделаем
    		запрос на перевод ему 20 копеек (это минимальная сумма перевода).
    		Листинг кода:
    		<pre class="code">
$request = $ym->requestPaymentP2P($token, '410011161616877', 0.02, 'тестовый перевод', 'сообщение к переводу :)');
print_r('Статус запроса платежа: ' . $request->getStatus() . '&lt;br&gt;');
print_r('Объект RequestPaymentResponse целиком: ' . $request);</pre>
    		Результат выполнения:
    	</p>
		
		<p class="output">
			<?php
			$request = $ym->requestPaymentP2P($token, '410011161616877', 0.02, 'тестовый перевод', 'сообщение к переводу :)');
			print_r('Статус запроса платежа: ' . $request->getStatus() . '<br>');
			print_r('Объект RequestPaymentResponse целиком: ' . $request);
			?>
		</p>

		<p>
			Ну и, наконец, проведем обработку запроса из предыдущего примера, чтобы факт 
			перевода двух копеек состоялся. Сделаем processPayment по полученному 
			<i>requestPayment</i>. Листинг кода:
			<pre class="code">
$process = $ym->processPayment($token, $request->getRequestId());
print_r('Статут проведения платежа: ' . $process->getStatus() . '&lt;br&gt;');
print_r('Идентификатор платежа: ' . $process->getPaymentId() . '&lt;br&gt;');
print_r('Остаток баланса после проведения платежа: ' . $process->getBalance() . '&lt;br&gt;');
print_r('Объект ProcessPaymentResponse целиком: ' . $process);</pre>
			Результат выполнения кода:
		</p>
		
		<p class="output">
			<?php
			$process = $ym->processPayment($token, $request->getRequestId());
			print_r('Статут проведения платежа: ' . $process->getStatus() . '<br>');
			print_r('Идентификатор платежа: ' . $process->getPaymentId() . '<br>');
			print_r('Остаток баланса после проведения платежа: ' . $process->getBalance() . '<br>');
			print_r('Объект ProcessPaymentResponse целиком: ' . $process);
			?>
		</p>

		<p>
			Как вы видите пользоваться API предельно просто. Если какие-то параметры 
			или методы непонятны, то посмотрите в документацию по API или в исходные коды
			библиотеки <i>ym.php</i>. Там множество комментариев и описаний, которые помогут
			разобраться. <br>
			Также, посмотрев, в исходники библиотеки вам не должно составить труда реализовать
			еще какой-нибудь требуемый вам метод (например, платеж в магазин или платежи 
			с ограничениями). 
		</p>
	</div>
</body>
</html>
