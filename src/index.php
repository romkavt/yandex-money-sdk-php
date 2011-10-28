<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Примеры использования Yandex.Money API</title>
    </head>
    <body>
        <div id="main">        		
            <h3 id="header">Примеры использования Yandex.Money API</h3>
            <p>
                Попробуем сделать несколько вызовов, показывающих как можно работать
                с Yandex.Money Api. Для простоты описания будем считать, что вы работаете
                на локальном веб-сервере и файлы примеров лежат в корне 
                каталога /www сайта нашего приложения.<br/><br/>
                По <a href="http://api.yandex.ru/money/">инструкции</a> первым делом 
                нужно получить идентификатор приложения. Для этого нужно перейти по 
                <a href="https://sp-money.yandex.ru/myservices/new.xml">ссылке</a> 
                и ввести следующие данные:
                <ul>
                	<li>Название: любое, но помните, что его будут видеть пользователи при
                	подтверждении прав на доступ к их деньгам</li>
                	<li>Адрес сайта: http://localhost/</li>
                	<li>Redirect URI: http://localhost/redirected.php</li>
                </ul>
                Если вы запускаетесь не на локальном сервере, а на хостинге, то стоит заменить localhost 
                на адрес вашего сайта и указать правильный путь к redirected.php.
                <p>
                    <b>Внимание: </b> затем нужно полученный идентификатор клиента скопировать в файл consts.php 
                    в константу CLIENT_ID. Далее нужно указать REDIRECT_URL такой же, как указали в сервисе 
                    при регистрации. Ну и напоследок нужно указать путь к файлу ym.crt цепочки сертификатов в константе
                    CERTIFICATE_CHAIN_PATH. Если вы будете запускать на локальном сервере, то лучше указать 
                    абсолютный путь. Если на хостинге, то скорее всего значение константы следует указать такое: 
                    CERTIFICATE_CHAIN_PATH = 'yamoney/ym.crt'; Это нужно, чтобы библиотека смогла удостовериться, 
                    что мы общаемся именно с сервером Яндекс.Денег. 
                </p>
                
                После этого нам нужно запросить у какого-либо пользователя Яндекс.Денег (можно указать свой
                эккаунт) разрешения на доступ вашего приложения к его эккаунту. Это разрешение представляет собой 
                токен доступа, который далее мы попробуем получить. Нажмите кнопку "Получить токен" для продолжения. <br />
                Не снимайте галочки, чтобы были права на все операции, которые описаны в данном примере.
                <br>                               
                            
            </p>        
            <p>
            Форма получения токена пользователя. <br>    
            <b>Выберите права:</b>
            <form action="auth.php" method="post">            
                <input name="account-info" type="checkbox" value=" account-info" checked="true"/>account-info<br>
                <input name="operation-history" type="checkbox" value=" operation-history" checked="true"/>operation-history<br>
                <input name="operation-details" type="checkbox" value=" operation-details" checked="true"/>operation-details<br>
                <!-- Эти права не реализованы
                <input name="payment" type="checkbox" value=" payment"/>payment<br>
                <input name="payment-shop" type="checkbox" value=" payment-shop"/>payment-shop<br>-->
                <input name="payment-p2p" type="checkbox" checked="true" value=" payment-p2p"/>payment-p2p<br>
                <input name="money-source(wallet card)" type="checkbox" checked="true" value=" money-source(wallet card)"/>money-source<br>
                <input type="submit" value="Получить токен"/>
            </form>        
            </p>
            <?php
            // put your code here
            ?>
        </div>
    </body>
</html>
