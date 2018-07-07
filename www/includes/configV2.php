<?php

// Email support
define('EMAIL', 'support@EMAIL.ru');

// Steam Web API https://steamcommunity.com/dev/apikey 
define('STEAM_WEB_API', '*****************************');
return array(
    // Версия сборки
    'LR_VERS' => '2.0',
    
    // TRUE or FALSE
    'DEBUG' => 'FALSE',

    // Основной сайт проекта (если нет закомментируйте стоку)
    'MAIN_SITE' => 'http://sitename.ru/',
    
    // Главная страница статистики
    'MAIN_PAGE' => 'http://sitename.ru/',
    
    // Название проекта
    'NAME_PROJ' => 'Название',
    
    // Записей на страницу
    'RECORD_ON_PAGE' => 50,
    
    // 18 (CS:GO) or 19 (LR)
    'LVL_18_OR_19' => '19',

    // Кэширование
    // TRUE or FALSE
    'CACHE' => TRUE,
    // задаем время жизни кэша в секундах
    'CACHETIME' => 600,
    // Статистика STEAM
    // TRUE or FALSE
    'STEAMSTATUS' => TRUE,

    // НЕ ТРОГАТЬ!
    'STEAM_WEB_API' => constant("STEAM_WEB_API"),
    // НЕ ТРОГАТЬ!
    'EMAIL' =>  constant("EMAIL"),
    // НЕ ТРОГАТЬ!
    'VIP' => FALSE,
    // НЕ ТРОГАТЬ!
    'STATS_SHOW' => 'SHOWCASE',
    
    //Скрывать ботов в списке игроков на сервере
    // TRUE - показывать
    // FALSE - не показывать
    'BOT_INFO'  =>  FALSE,

    'vip_database' => array(
        'host'      =>  'ip',
        'dbName'    =>  'db',
        'login'     =>  'login',
        'pass'      =>  'pass',
        // Порт базы данных (стандартный порт 3306)
        'port'      =>  '3306'
    ),

    'ban_database' => array(
        'host'      =>  'ip',
        'dbName'    =>  'db',
        'login'     =>  'login',
        'pass'      =>  'pass',
        // Порт базы данных (стандартный порт 3306)
        'port'      =>  '3306'
    ),

	//steamID64 для лоуступа к админке
    'admins'    =>  array(
        76561198004733537,
		76561198368693755
    ),
    
    // Серверы
    'server' => array(
        array(
            //Настройки базы данных
            'host'      =>  'ip',
            'dbName'    =>  'db',
            'login'     =>  'login',
            'pass'      =>  'pass',
			// Порт базы данных (стандартный порт 3306)
            'port'      =>  '3306',

            //Настройки игрового сервера
            'name'      =>  'отображаемое имя',
            'gameHost'  =>  'ip',
            'gamePort'  =>  27015
        ),
		// если 2 сервера
		/**
        array(
            'host'      =>  'ip',
            'dbName'    =>  'db',
            'login'     =>  'login',
            'pass'      =>  'pass',
			// Порт базы данных (стандартный порт 3306)
            'port'      =>  '3306',

            //Настройки игрового сервера
            'name'      =>  'отображаемое имя',
            'gameHost'  =>  'ip',
            'gamePort'  =>  27015
        ),
		**/
		
        
    )
)

?>