<?php
    // мониторим скорость выполнения
	$start = microtime(true);
	// задаем время жизни кэша в секундах
	$cacheTime = 3600;
	// Название скрипта
	$fileName = strrchr($_SERVER["REQUEST_URI"], "/");
	// удаляем все слеши
	$fileName = trim($fileName, '/\\');
	// путь для хранения кеша
	$cacheFile = "./../cache/$fileName.cache";
	// если кэш существует
	if (file_exists($cacheFile)) {
		// проверяем актуальность кеша
		if ((time() - $cacheTime) < filemtime($cacheFile)) {
			// показываем данные из кеша
            header('content-type: image/png');
			echo file_get_contents($cacheFile);
			// мониторим скорость работы
			//echo 'Время выполнения скрипта: '.(microtime(true) - $start).' сек.';
			exit; // Завершаем скрипт
		}
	}
	// открываем буфер
	ob_start();

    $config = include("configV2.php");
    spl_autoload_register(function ($class){
        include 'class/' . $class . '.class.php';
    });
    header('content-type: image/png');
    $image = imagecreatefrompng('img/signature1.png');
    $statusArr = ['Offline', 'Online', 'Busy', 'Away', 'Snooze', 'looking to trade', 'looking to play'];
    $statsColorArr = [imagecolorallocate($image, 255, 0, 0), imagecolorallocate($image, 0, 255, 0), imagecolorallocate($image, 255, 255, 0), imagecolorallocate($image, 255, 255, 0), imagecolorallocate($image, 255, 255, 0), imagecolorallocate($image, 255, 255, 0), imagecolorallocate($image, 255, 255, 0)];
    $statsColorArr2 = [imagecolorallocate($image, 128, 128, 128), imagecolorallocate($image, 0, 255, 0), imagecolorallocate($image, 255, 255, 0), imagecolorallocate($image, 255, 255, 0), imagecolorallocate($image, 255, 255, 0), imagecolorallocate($image, 255, 255, 0), imagecolorallocate($image, 255, 255, 0)];
    $white = imagecolorallocate($image, 255, 255, 255);
    $green = imagecolorallocate($image, 0, 255, 0);

    $steamid = "STEAM_1:".$_GET['id'];
    $player = new Player($steamid);
    $server = new Server($config['server'][$_GET['serv']]['name'], $config['server'][$_GET['serv']]['host'], $config['server'][$_GET['serv']]['dbName'], $config['server'][$_GET['serv']]['login'], $config['server'][$_GET['serv']]['pass'], $config['server'][$_GET['serv']]['port']);
	$playerSteam = new PlayerSteamSatas($steamid);
    $player->getStat($server);
    
    //var_dump($playerSteam->avaUrl);

    //Указываем путь к шрифту 
    putenv('GDFONTPATH=' . realpath('.'));
    $font_path = 'fonts/Phenomena-Bold.otf';
    //Пишем текст
    $serverName = $config['server'][$_GET['serv']]['name'];
    $name = $player->name;
    $shots = $player->shot;
    $lvl = $player->lvl;
    $hits = $player->hit;
    $score = $player->value;
    $deaths = $player->death;
    $qwe = $player->acc;
    $kills = $player->kills;
    $kd = $player->kd;
    $support = $player->help;
    $lastGame = date("d.m.y", $player->lastgame);
    $status = $playerSteam->personastate;
    //Соединяем текст и картинку
    //imagettftext($image, 14, 0, 10, 20, $white, $font_path, $_GET['serv']);
    //imagettftext($image, 14, 0, 10, 40, $white, $font_path, $steamid);
    imagettftext($image, 14, 0, (335 - strlen($serverName)*3.5), 17, $white, $font_path, $serverName);
    imagettftext($image, 12, 0, (158 - strlen($name)*2), 53, $green, $font_path, $name);
    imagettftext($image, 12, 0, (158 - strlen($shots)*2.5), 88, $white, $font_path, $shots);
    imagettftext($image, 12, 0, (234 - strlen($lvl)*2.5), 53, $white, $font_path, $lvl);
    imagettftext($image, 12, 0, (234 - strlen($hits)*2.5), 88, $green, $font_path, $hits);
    imagettftext($image, 12, 0, (300 - strlen($score)*3), 53, $green, $font_path, $score);
    imagettftext($image, 12, 0, (300 - strlen($deaths)*2.5), 88, $white, $font_path, $deaths);
    imagettftext($image, 12, 0, (375 - strlen($qwe)*3), 53, $white, $font_path, $qwe);
    imagettftext($image, 12, 0, (375 - strlen($kills)*3), 88, $green, $font_path, $kills);
    imagettftext($image, 12, 0, (436 - strlen($kd)*2), 53, $green, $font_path, $kd);
    imagettftext($image, 12, 0, (436 - strlen($support)*3), 88, $white, $font_path, $support);
    imagettftext($image, 12, 0, (506 - strlen($lastGame)*2.5), 53, $white, $font_path, $lastGame);
    imagettftext($image, 12, 0, (506 - strlen($statusArr[$status])*2.5), 88, $statsColorArr[$status], $font_path, $statusArr[$status]);
    imagefilledrectangle($image, 4, 3, 93, 92, $statsColorArr2[$status]);
    imagecopyresized($image, imagecreatefromstring(file_get_contents($playerSteam->avaUrl)), 5, 4, 0, 0, 88, 88, 184, 184);
    imagepng($image);
    imagedestroy($image);

    // Открываем файл для записи
    $file = fopen($cacheFile, 'w');
    // сохраняем все что есть в буфере вфайл кеша
    fwrite($file, ob_get_contents());
    // закрываем файл
    fclose($file);
    // выводим страницу
    ob_end_flush();
    // мониторим скорость работы
    //echo 'Время выполнения скрипта: '.(microtime(true) - $start).' сек.';
    //echo '<br>'. $fileName;
    
?>