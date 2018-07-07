<?php
	$config = include("./includes/configV2.php");

	// если включено кэширование
	if($config['CACHE']){
		// мониторим скорость выполнения
		$start = microtime(true);
		// задаем время жизни кэша в секундах
		$cacheTime = $config['CACHETIME'];
		// Название скрипта
		$fileName = strrchr($_SERVER["REQUEST_URI"], "/");
		// удаляем все слеши
		$fileName = trim($fileName, '/\\');
		// путь для хранения кеша
		$cacheFile = "./cache/$fileName.cache";
		// если кэш существует
		if (file_exists($cacheFile)) {
			// проверяем актуальность кеша
			if ((time() - $cacheTime) < filemtime($cacheFile)) {
				// показываем данные из кеша
				echo file_get_contents($cacheFile);
				// мониторим скорость работы
				//echo 'Время выполнения скрипта: '.(microtime(true) - $start).' сек.';
				exit; // Завершаем скрипт
			}
		}
		// открываем буфер
		ob_start();
	}

    
    include("./includes/steamauth/steamauth.php");
    spl_autoload_register(function ($class){
        include './includes/class/' . $class . '.class.php';
    });
    if($config['DEBUG']){
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    
    $cur_serv = "player";
    $adminAccess = false;
    
    if(isset($_GET['sid'])){
        $steam_id = $_GET['sid'];
        $player = new Player($steam_id);
        $playerSteam = new PlayerSteam ($steam_id);
        if(isset($_SESSION['steamid'])){
            include_once('./includes/steamauth/userInfo.php');
            if(in_array($steamprofile['steamid'], $config['admins'])){
                $adminAccess = true;
            }
        }

    }elseif(isset($_SESSION['steamid'])){
        include_once('./includes/steamauth/userInfo.php');
        if(in_array($steamprofile['steamid'], $config['admins'])){
            $adminAccess = true;
        }
        $steam_id = $steamprofile['steamid'];
        $player = new Player($steam_id);
        $playerSteam = new PlayerSteam ($steam_id);

    }else{
        $steam_id = NULL;
    }
    
	if($config['LVL_18_OR_19']==18){
		$title_lr = array(0=>"Калибровка", 1=>"Серебро I", 2=>"Серебро II", 3=>"Серебро III", 4=>"Серебро IV", 5=>"Серебро-Элита", 6=>"Серебро-Великий Магистр", 7=>"Золотая Звезда I", 8=>"Золотая Звезда II", 9=>"Золотая Звезда III", 10=>"Золотая Звезда — Магистр", 11=>"Магистр-хранитель I", 12=>"Магистр-хранитель II", 13=>"Магистр-хранитель Элита", 14=>"Заслуженный <br/>Магистр-хранитель", 15=>"Легендарный Беркут", 16=>"Легендарный <br/>Беркут — Магистр", 17=>"Великий Магистр<br/>Высшего Ранга", 18=>"<font color='#FFD700'>Всемирная Элита</font>");
	}else{
		$title_lr = array(0=>"Калибровка", 1=>"Серебро I", 2=>"Серебро II", 3=>"Серебро III", 4=>"Серебро IV", 5=>"Серебро-Элита", 6=>"Серебро-Великий Магистр", 7=>"Золотая Звезда I", 8=>"Золотая Звезда II", 9=>"Золотая Звезда III", 10=>"Золотая Звезда — Магистр", 11=>"Магистр-хранитель I", 12=>"Магистр-хранитель II", 13=>"Магистр-хранитель Элита", 14=>"Заслуженный <br/>Магистр-хранитель", 15=>"Легендарный Беркут", 16=>"Легендарный <br/>Беркут — Магистр", 17=>"Великий Магистр<br/>Высшего Ранга", 18=>"<font color='#FFD700'>Всемирная Элита</font>");
	}
	
	$vipStats = FALSE;
?>
<!DOCTYPE html>
<html lang="ru">
<html>
    <head>
        <meta charset="UTF-8">
        <title>Levels Ranks | <?=$config['NAME_PROJ']?></title>
        <!--Import Google Icon Font-->
        <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link rel="stylesheet" type="text/css" href="includes/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="includes/css/bootstrap-theme.css">
        <link rel="stylesheet" type="text/css" href="includes/css/style-steam.css">  

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link rel="icon" href="./favicon.png">
    </head>

    <body>
        <div class="head-margin"></div>
        
        <div class="container">
            <?php 
                // Header
                include("./includes/header.php");
            ?>

            <div class="row">
                <div class="content col-md-12">        
                    <?php if(!isset($steam_id)): //не существует?>
                        <div class="profile_header_bg_texture">
                            <div class="profile_header_inner">
                                <div class="row">
                                    <div class="col-md-3 avatar-div"><img class="responsive-img" src="./includes/img/noname.png" style="border: solid 2px;" height='164px', width='164px'></div>
                                    <div class="col-md-5 persona">
                                        <div class="persona_name">Top Secret</div>                            
                                        <div class="row col-md-12"></div>
                                        <div class="row col-md-12"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row col-md-12 persona_name">Уровень: &infin;</div>
                                        <div class="row col-md-12 rank">
                                            <div class="profile_header_badge">
                                                <div class="favorite_badge">
                                                    <div class="favorite_badge_icon">
                                                        <img src="./includes/img/steam/top-secret.png">
                                                    </div>
                                                    <div class="favorite_badge_description">
                                                    <div class="name"></div>
                                                    <div class="xp">&nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-md-12 rank"><br /><?php if(!isset($_SESSION['steamid'])) { loginbutton("rectangle"); }else{ if(convert64to32($steamprofile['steamid'])==$steam_id) logoutbutton(); } ?></div>
                                        <div class="row col-md-12">&nbsp;</div>
                                    </div>
                                </div>
                            </div>                    
                        </div>
						
					<?php else: //существует ?>
            
						<?php
							if($config['VIP']){
								$vip = $player->getVip($config);
								for($i=0; $i<count($vip); $i++){
									if($vip[$i]['vip']) $vipStats = TRUE;
								}
							}else{
								$vipStats = FALSE;
							}
							
						?>
						<div class="<?php if($vipStats){ echo "profile_header_bg_texture_vip";}else{echo "profile_header_bg_texture";}; ?>">
                            <div class="profile_header_inner">
                                <div class="row">
                                    <div class="col-md-3 avatar-div"><img src="<?=$playerSteam->avatarfull?>" style="border: solid 2px;" height='164px', width='164px'></div>
                                    <div class="col-md-5 persona">
                                        <div class="persona_name">
											<a href="<?=$playerSteam->profileurl?>">
												<?php 
													if($config['VIP']){
														if($vipStats){
															echo '<font color="gold">'. $playerSteam->personaname .'</font>';
														}else{
															echo $playerSteam->personaname;
														}
													}else{
														echo $playerSteam->personaname;
													}
													
												?>
											</a>
										</div>
										<?php if($config['VIP']): ?>
											<div class="row col-md-12 <?php if($vipStats){echo 'green';}else{ echo 'white';} ?>"><b>VIP</b></div>
											<?php for($i=0; $i<count($vip); $i++):?>
												<div class="row col-md-12 white"><?=$vip[$i]['server'];?> <?php if($vip[$i]['vip']){ echo " - до " . date("d.m.Y H:i", $vip[$i]['vip']);}else{ echo ' - нет';}?></div>
											<?php endfor; ?>
										<?php endif; ?>
                                        <div class="row col-md-12"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row col-md-12 persona_name">Уровень: <?php $maxLvl = max($player->getMaxLvl($config)); if($maxLvl){echo $maxLvl;}else{$maxLvl = 0; echo $maxLvl; }?></div>
                                        <div class="row col-md-12 rank">
                                            <div class="profile_header_badge">
                                                <div class="favorite_badge">
                                                    <div class="favorite_badge_icon">
                                                        <img src="./includes/img/rank/<?=$config['LVL_18_OR_19']?>/<?=$maxLvl?>.png">
                                                    </div>
                                                    <div class="favorite_badge_description">
                                                    <div class="name"><?=$title_lr[$maxLvl]?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-md-12 rank"><br /><?php if(!isset($_SESSION['steamid'])) { loginbutton("rectangle"); }else{ logoutbutton(); } ?></div>
                                        <div class="row col-md-12">&nbsp;</div>
                                    </div>
                                </div>
                            </div>                    
                        </div>
						
						<div class="profile_content">
							<div class="profile_content_inner">
								<div class="row">

									<div class="col-md-12">                            
										<?php for($i=0; $i < count($config['server']); $i++): ?>
											<?php
												$server = new Server($config['server'][$i]['name'], $config['server'][$i]['host'], $config['server'][$i]['dbName'], $config['server'][$i]['login'], $config['server'][$i]['pass'], $config['server'][$i]['port']);
												$player->getStat($server);
											?>
											<?php if($config['STATS_SHOW'] == "SHOWCASE"): //тип отображения ?>
												<?php if($player->name): ?>
													<div class="row col-md-12 showcase">
														<span class="showcase-server_name"><b><?=$server->name?></b></span>
														<div class="row showcase-line">
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->value?></b>
																</div>
																<div class="row">
																	Очки
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->lvl?></b>
																</div>
																<div class="row">
																	Уровень
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->kills?></b>
																</div>
																<div class="row">
																	Убийства
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->death?></b>
																</div>
																<div class="row">
																	Смерти
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->kd?></b>
																</div>
																<div class="row">
																	K/D
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->help?></b>
																</div>
																<div class="row">
																	Помощь
																</div>
															</div>
														</div>
														<div class="row showcase-line">
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->shot?></b>
																</div>
																<div class="row">
																	Выстрелы
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->hit?></b>
																</div>
																<div class="row">
																	Попадания
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->acc?>%</b>
																</div>
																<div class="row">
																	Точность
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$player->inhead?></b>
																</div>
																<div class="row">
																	В голову
																</div>
															</div>
															<div class="col-md-4">
																<div class="row">
																	<b><?=date("d.m.y", $player->lastgame)?></b>
																</div>
																<div class="row">
																	Последняя игра
																</div>
															</div>
														</div>
                                                            <?php if($adminAccess):?>
                                                            <form action="./includes/clearRanks.php" method="post">
                                                                <input type="hidden" value='<?=$player->steamid?>' name="user_id" />
                                                                <input type="hidden" value='<?=$i?>' name="server" />
                                                                <button type='submit' class='btn_profile_action btn_medium'><span>Обнулить статистику игрока</span></button>
                                                            </form>
                                                            <?php endif; ?>
                                                        <br>
													</div>
													
												<?php else: ?>
													<div class="row col-md-12 showcase">
														<span class="showcase-server_name"><b><?=$server->name?></b></span>
														<div class="row showcase-line">
															<div class="col-md-12">
																Нет данных
															</div>
														</div>
														<br>
													</div>
												<?php endif; ?>

											<?php else: ?>

												<div class="row col-md-12 recent_game">
													
													<?php if($player->name): ?>

													<table class="table table-condensed table-striped">                                 
														<thead>
															<tr>
																<th colspan="2"><?=$server->name?></th>
															</tr>
														</thead>

														<tbody>
															<tr>
																<td>Очки</td>
																<td style="text-align: center;"><?=$player->value?></td>
															</tr>
															<tr>
																<td>Уровень</td>
																<td style="text-align: center;"><?=$player->lvl?></td>
															</tr>
															<tr>
																<td>Убийства</td>
																<td style="text-align: center;"><?=$player->kills?></td>
															</tr>
															<tr>
																<td>Смерти</td>
																<td style="text-align: center;"><?=$player->death?></td>
															</tr>
															<tr>
																<td>K/D</td>
																<td style="text-align: center;"><?=$player->kd?></td>
															</tr>
															<tr>
																<td>Выстрелы</td>
																<td style="text-align: center;"><?=$player->shot?></td>
															</tr>
															<tr>
																<td>Попадания</td>
																<td style="text-align: center;"><?=$player->hit?></td>
															</tr>
															<tr>
																<td>Точность</td>
																<td style="text-align: center;"><?=$player->acc?>%</td>
															</tr>
															<tr>
																<td>В голову</td>
																<td style="text-align: center;"><?=$player->inhead?></td>
															</tr>
															<tr>
																<td>Помощь</td>
																<td style="text-align: center;"><?=$player->help?></td>
															</tr>                                                    
															<tr>
																<td>Последняя игра</td>
																<td style="text-align: center;"><?=date("d.m.y", $player->lastgame)?></td>
															</tr>

														</tbody>
													</table>
													<?php else: ?>
														<table class="table table-condensed table-striped">                                  
															<thead>
																<tr>
																	<th><?=$server->name?></th>
																</tr>
															</thead>
															<tbody>
																<tr><td>Нет данных!</td></tr>
															</tbody>
														</table>
													<?php endif; ?>
												</div>
											<?php endif; ?>
										<?php unset($server); endfor; ?>
										
										<?php if($config['STEAMSTATUS']): // включен стим??>
											<?php if($config['STATS_SHOW'] == "SHOWCASE"): //тип отображения ?>

												<?php if(isset($playerSteam->totalTime)): ?>
													<div class="row col-md-12 showcase">
													<span class="showcase-server_name"><b>STEAM</b></span>
														<div class="row showcase-line">
															<div class="col-md-3">
																<div class="row">
																	<b><?=round($playerSteam->totalTime/3600, 2)?> ч</b>
																</div>
																<div class="row">
																	Время в игре
																</div>
															</div>
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->acc?> %</b>
																</div>
																<div class="row">
																	Точность стрельбы
																</div>
															</div>
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->kills?></b>
																</div>
																<div class="row">
																	Убийств
																</div>
															</div>
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->inhead?></b>
																</div>
																<div class="row">
																	Убийств в голову
																</div>
															</div>
														</div>

														<div class="row showcase-line">
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->headshotPercent?> %</b>
																</div>
																<div class="row">
																	Убийств в голову
																</div>
															</div>
															<div class="col-md-6">
																<div class="row">
																	<b><?=$playerSteam->killsBlinded?></b>
																</div>
																<div class="row">
																	Убито ослепленных врагов
																</div>
															</div>
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->totalDamageDone?></b>
																</div>
																<div class="row">
																	Нанесено урона
																</div>
															</div>
														</div>

														<div class="row showcase-line">
															<div class="col-md-4">
																<div class="row">
																	<b><?=$playerSteam->killsEnemyWeapons?></b>
																</div>
																<div class="row">
																	Убийств оружием врага
																</div>
															</div>
															<div class="col-md-5">
																<div class="row">
																	<b><?=$playerSteam->dominations?></b>
																</div>
																<div class="row">
																	Превосходства над игроками
																</div>
															</div>
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->revenges?></b>
																</div>
																<div class="row">
																	Возмездий
																</div>
															</div>
														</div>

														<div class="row showcase-line">
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->death?></b>
																</div>
																<div class="row">
																	Смертей
																</div>
															</div>
															<div class="col-md-2">
																<div class="row">
																	<b><?=$playerSteam->kd?></b>
																</div>
																<div class="row">
																	K/D
																</div>
															</div>
															<div class="col-md-4">
																<div class="row">
																	<b><?=$playerSteam->bombPlanted?></b>
																</div>
																<div class="row">
																	Бомб установлено
																</div>
															</div>
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->bombDefused?></b>
																</div>
																<div class="row">
																	Бомб обезврежено
																</div>
															</div>
														</div>

														<div class="row showcase-line">
															<div class="col-md-3">
																<div class="row">
																	<b><?=$playerSteam->wins?></b>
																</div>
																<div class="row">
																	Победы
																</div>
															</div>
															<div class="col-md-4">
																<div class="row">
																	<b><?=$playerSteam->winsKnifeFight?></b>
																</div>
																<div class="row">
																	Побед в ножевом бою
																</div>
															</div>
															<div class="col-md-5">
																<div class="row">
																	<b><?=$playerSteam->winPistolRrounds?></b>
																</div>
																<div class="row">
																	Побед в пистолетных раундах
																</div>
															</div>
														</div>

														<div class="row showcase-line">
															<div class="col-md-6">
																<div class="row">
																	<b><?=$playerSteam->achivements?> из 167</b>
																</div>
																<div class="row">
																	Достижения
																</div>
															</div>
															<div class="col-md-6">
																<div class="row">
																	<b><?=$playerSteam->modeyEarned?></b>
																</div>
																<div class="row">
																	Заработано денег
																</div>
															</div>
														</div>
														<br>
													</div>
													
												<?php else: ?>

													<div class="row col-md-12 showcase">
														<span class="showcase-server_name"><b>STEAM</b></span>
														<div class="row showcase-line">
															<div class="col-md-12">
																Нет данных
															</div>
														</div>
														<br>
													</div>

												<?php endif; ?>

											<?php else: // table?>

												<div class="row col-md-12 recent_game">                         
													<table class="table table-condensed table-striped">
														<thead>
															<tr>
																<th colspan="2">По данным Steam</th>
															</tr>
														</thead>
														<?php if(isset($playerSteam->totalTime)): ?>
															<tbody>
																<tr>
																	<td>Реальное время в игре</td>
																	<td style="text-align: center;"><?=round($playerSteam->totalTime/3600, 2)?> ч</td>
																</tr>
																<tr>
																	<td>Точность стрельбы</td>
																	<td style="text-align: center;"><?=$playerSteam->acc?> %</td>
																</tr>
																<tr>
																	<td>Убийств</td>
																	<td style="text-align: center;"><?=$playerSteam->kills?></td>
																</tr>
																<tr>
																	<td>Убийств в голову</td>
																	<td style="text-align: center;"><?=$playerSteam->inhead?></td>
																</tr>
																<tr>
																	<td>Убийств в голову %</td>
																	<td style="text-align: center;"><?=$playerSteam->headshotPercent?> %</td>
																</tr>
																<tr>
																	<td>Убито ослепленных врагов</td>
																	<td style="text-align: center;"><?=$playerSteam->killsBlinded?></td>
																</tr>                                                
																<tr>
																	<td>Убийств оружием врага</td>
																	<td style="text-align: center;"><?=$playerSteam->killsEnemyWeapons?></td>
																</tr>
																<tr>
																	<td>Нанесено урона</td>
																	<td style="text-align: center;"><?=$playerSteam->totalDamageDone?></td>
																</tr>
																<tr>
																	<td>Смертей</td>
																	<td style="text-align: center;"><?=$playerSteam->death?></td>
																</tr>    
																<tr>
																	<td>K/D</td>
																	<td style="text-align: center;"><?=$playerSteam->kd?></td>
																</tr>                                                                                       
																<tr>
																	<td>Бомб установлено</td>
																	<td style="text-align: center;"><?=$playerSteam->bombPlanted?></td>
																</tr>
																<tr>
																	<td>Бомб обезврежено</td>
																	<td style="text-align: center;"><?=$playerSteam->bombDefused?></td>
																</tr>
																<tr>
																	<td>Победы</td>
																	<td style="text-align: center;"><?=$playerSteam->wins?></td>
																</tr>
																<tr>
																	<td>Побед в ножевом бою</td>
																	<td style="text-align: center;"><?=$playerSteam->winsKnifeFight?></td>
																</tr>
																<tr>
																	<td>Побед в пистолетных раундах</td>
																	<td style="text-align: center;"><?=$playerSteam->winPistolRrounds?></td>
																</tr>  
																<tr>
																	<td>Заработано денег</td>
																	<td style="text-align: center;"><?=$playerSteam->modeyEarned?></td>
																</tr>                                       
																<tr>
																	<td>Превосходства над игроками</td>
																	<td style="text-align: center;"><?=$playerSteam->dominations?></td>
																</tr>
																<tr>
																	<td>Возмездий</td>
																	<td style="text-align: center;"><?=$playerSteam->revenges?></td>
																</tr>
																<tr>
																	<td>Достижения</td>
																	<td style="text-align: center;"><?=$playerSteam->achivements?> из 167</td>
																</tr>
															</tbody>
														<?php else: ?>
															<tbody>
																<tr>
																	<td>
																		Профиль скрыт
																	</td>
																</tr>
															</tbody>
														<?php endif; ?>
													</table>
												</div>
											<?php endif; // table?>
										<?php endif; // end если включен STEAM?>
									</div>
								</div>
							</div>
						</div>
					
					<?php endif;?>
						
				</div>
			</div>
			
			<?php
				// Footer
				include("./includes/footer.php");
			?>
		</div>
		<div class="footer-margin"></div>
    </body>
</html>

<?php unset($player); unset($playerSteam); ?>

<?php
	// Если включено кэширование
	if($config['CACHE']){
		if(($fileName!="player.php") && ($fileName!="player.php?login")){
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
		}
	}
	
?>
