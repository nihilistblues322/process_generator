Для удобства, база данных используется sqlite.

Начало работы: 

```bash
composer install

```
Запуск теста:
```bash
vendor/bin/phpunit ProcessTest.php
```

Запуск рандомайзера данных по 2-5 штук:
```bash
php create_process.php
```

В случае ошибок с зависимостями на своей локальной машине, смотреть по логам в сторону php.ini 
