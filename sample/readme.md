# yandex-money-sdk-php sample app

This project is a simple app that can help you to understand what the payment APIs can do for you.

**To try out the sample:**

* [register](https://sp-money.yandex.ru/myservices/new.xml) an app
* update info in consts.php file (```CLIENT_ID```, ```REDIRECT_URI```, ```CLIENT_SECRET```)
* run ```composer update --no-dev``` from the sample folder
* deploy to a server and run

Also, if you want to make payments from bank card you should enter right pin in ```index.php``` at processPaymentByCard execution.