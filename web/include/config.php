<?php
// Версия
define('LR_VERS', '0.1.6');

// debug (true or false) // лучше не трогать!!
define('DEBUG', false);

// Главная страница сайта
define('MAIN_PAGE', "http://site.ru/");

// Тип статистики old (lvlold) or new (lvlnew)
define('STATS_TYPE', 'lvlnew');

// Имя сайта/проекта
define('NAME_PROJ', 'LR.ru');

// Имя файла лого в папке /include/img/ (176x44 name.png)
define('LR_LOGO', 'lr_logo.png');

// Записей на страницу
define('RECORD_ON_PAGE', 50);

// Звания 18 или 55
define('LVL_18_OR_55', '55');

// Число серверов
define('SERV_COUNT', 1);

// Steam Web API https://steamcommunity.com/dev/apikey 
define('STEAM_WEB_API', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

//Название сервера
define('SERV_NAME_1', '[LR] Server');
// Подключение к базе данных
// IP адрес
define('DB_HOST_1', 'ip');
// База данных
define('DB_DB_1', 'имя базы');
// Логин
define('DB_LOGIN_1', 'логин');
// Пароль
define('DB_PASSWD_1', 'пароль');
// Порт, по умолчанию 3306
define('DB_PORT_1', '3306');

// Если 2 сервера
// Название сервера
define('SERV_NAME_2', '[LR] Server 2');
// Подключение к базе данных 
// Адрес
define('DB_HOST_2', 'ip');
// База данных
define('DB_DB_2', 'имя базы');
// Логин
define('DB_LOGIN_2', 'логин');
// Пароль
define('DB_PASSWD_2', 'пароль');
// Порт
define('DB_PORT_2', '3306');

// Если 3 сервера
//Название сервера
define('SERV_NAME_3', '[LR] Server 3');
// Подключение к базе данных 
// Адрес
define('DB_HOST_3', 'ip');
// База данных
define('DB_DB_3', 'имя базы');
// Логин
define('DB_LOGIN_3', 'логин');
// Пароль
define('DB_PASSWD_3', 'пароль');
// Порт
define('DB_PORT_3', '3306');

// Если больше дописывайте по примеру выше
?>