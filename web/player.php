<?php
include_once('include/config.php');
include_once('include/function.php');
include_once("include/steamauth/steamauth.php");
if(DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

if(isset($_GET['sid'])){
    $steam_id = $_GET['sid'];
    $steam_playr_info = get_player_info_steam($steam_id);
    $player_all_stat_steam = get_player_all_stat_steam($steam_id);

}elseif(isset($_SESSION['steamid'])){
    include_once('include/steamauth/userInfo.php');
    $steam_id = convert64to32($steamprofile['steamid']);
    $steam_playr_info = get_player_info_steam($steam_id);
    $player_all_stat_steam = get_player_all_stat_steam_login($steamprofile['steamid']);

}else{
    $steam_id = NULL;
}
$title_lr = array(0=>"Нет звания", 1=>"Серебро I", 2=>"Серебро II", 3=>"Серебро III", 4=>"Серебро IV", 5=>"Серебро-Элита", 6=>"Серебро-Великий Магистр", 7=>"Золотая Звезда I", 8=>"Золотая Звезда II", 9=>"Золотая Звезда III", 10=>"Золотая Звезда — Магистр", 11=>"Магистр-хранитель I", 12=>"Магистр-хранитель II", 13=>"Магистр-хранитель Элита", 14=>"Заслуженный <br/>Магистр-хранитель", 15=>"Легендарный Беркут", 16=>"Легендарный <br/>Беркут — Магистр", 17=>"Великий Магистр<br/>Высшего Ранга", 18=>"<font color='#FFD700'>Всемирная Элита</font>", );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <html lang="ru">

    <title>Levels Ranks | <?php echo NAME_PROJ; ?></title>   
    <link rel="stylesheet" type="text/css" href="include/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="include/css/bootstrap-theme.css">
    <link rel="stylesheet" type="text/css" href="include/css/style-steam.css">

    
</head>

<body>
    <div class="head-margin"></div>
    <div class="container">
        <div class="row">
            <div class="header col-md-12">
                    <div class="header-content"><span class="logo"><img src="include/img/<?=constant("LR_LOGO");?>" width="176" height="44"></span>
                    <?php if(SERV_COUNT != 1): // Если больше одного сервера ?>
                        <a href='./'>Главная</a>
                        <?php for($i = 1; $i < SERV_COUNT + 1; $i++): ?>
                           <font color="gray">|</font> <a href="./?serv=<?=$i?>"><?=constant("SERV_NAME_".($i))?></a>
                        <?php endfor; ?>     
                            <span class="search"><a href="./search.php">Поиск</a></span>
                    <?php else: //Если 1 сервер ?>
                       <a href='./'><?=SERV_NAME_1?></a><span class="search"><a href="./search.php">Поиск</a></span>
                    <?php endif; ?>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="content col-md-12">                
                <div class="profile_header_bg_texture">
                    <div class="profile_header_inner">
                        <div class="row">
                            <div class="col-md-3 avatar-div"><?php if($steam_id): ?><img src="<?php echo $steam_playr_info->response->players[0]->avatarfull; ?>" alt="Avatar" class="avatar <?php if(isset($steam_playr_info->response->players[0]->gameextrainfo)) echo "in-game"; ?>"> <?php else: ?> <img src="include/img/noname.png" alt="Avatar" class="avatar"><?php endif; ?></div>
                            <div class="col-md-5 persona">
                                <div class="persona_name"><?php if($steam_id) echo "<a href='". $steam_playr_info->response->players[0]->profileurl ."'>" .$steam_playr_info->response->players[0]->personaname. "</a>"; else echo "Top Secret"; ?></div>                            
                                <div class="row col-md-12"><?php if(isset($steam_playr_info->response->players[0]->realname)) echo $steam_playr_info->response->players[0]->realname; ?></div>
                                <div class="row col-md-12"><?php if(isset($steam_playr_info->response->players[0]->loccountrycode)) echo "<img src='http://steamcommunity-a.akamaihd.net/public/images/countryflags/". mb_strtolower($steam_playr_info->response->players[0]->loccountrycode) .".gif' >" ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="row col-md-12 persona_name">Уровень: <?php if(constant("LVL_18_OR_55") == 55){ echo round(get_lvl($steam_id)/3);}elseif(constant("LVL_18_OR_55") == 18){ echo get_lvl($steam_id);} else {echo 0; } ?></div>
                                <div class="row col-md-12 rank">
                                    <div class="profile_header_badge">
                                        <div class="favorite_badge">
                                            <div class="favorite_badge_icon">
                                                <?php //var_dump(get_lvl($steam_id)); // получаем максимальный уровень?>
                                                <img src="include/img/rank/<?php if(constant("LVL_18_OR_55") == 55){ echo round(get_lvl($steam_id)/3);}elseif(constant("LVL_18_OR_55") == 18){ echo get_lvl($steam_id);} else {echo 0; } ?>.png">
                                            </div>
                                            <div class="favorite_badge_description">
                                            <div class="name"><?php if(constant("LVL_18_OR_55") == 55){ echo $title_lr[round(get_lvl($steam_id)/3)];}elseif(constant("LVL_18_OR_55") == 18){ echo $title_lr[get_lvl($steam_id)];} else {echo $title_lr[0]; } ?></div>
                                            <div class="xp">&nbsp</div>
                                            </div>
                                        </div>
									</div>
                                </div>
                                <div class="row col-md-12"><br /><?php if(!isset($_SESSION['steamid'])) { loginbutton("rectangle"); }else{ if(convert64to32($steamprofile['steamid'])==$steam_id) logoutbutton(); } ?></div>
                                <div class="row col-md-12">&nbsp</div>
                            </div>
                        </div>
                    </div>                    
                </div>
                
                <div class="profile_content">
                     <div class="profile_content_inner">
                        <div class="row">                       
                            <div class="col-md-8">                                
                                <?php if(SERV_COUNT != 1 AND $steam_id): // Если больше одного сервера ?>
                                    <?php for($i= 0; $i < SERV_COUNT; $i++): ?>
                                        <?php $stat = get_stat($steam_id, $i+1); ?>
                                        <?php if(empty($stat)):?>
                                            <div class="row col-md-12 recent_game">
                                                <?php echo "<h4>". constant("SERV_NAME_".($i+1)) ."</h4>"; ?>
                                                Не играл на сервере!
                                                <br/>
                                                <br/>
                                            </div>
                                        <?php else:?>
                                            <div class="row col-md-12 recent_game">
                                                <?php if(constant("STATS_TYPE") == "lvlnew"): //если новый тип статистики?>
                                                    <table class="table table-condensed table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2"><h4><?=constant("SERV_NAME_".($i+1)); ?></h4></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Очки</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['exp'];} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Уровень</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['rank'];} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Убийства</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['kills'];} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Смерти</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['deaths'];} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>K/D</td>
                                                                <td align="center"><?php if($stat['deaths'] == 0) { echo "-"; } else { echo round($stat["kills"] / $stat['deaths'], 2); } ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Выстрелы</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['shoots'];} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Попадания</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['hits'];} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Точность</td>
                                                                <td align="center"><?php if($stat["hits"] == 0 || $stat["shoots"] == 0) { echo "-"; } else { echo round($stat["hits"] * 100 / $stat["shoots"]) . "%"; } ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>В голову</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['headshots'];} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Помощь</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['assists'];} ?></td>
                                                            </tr>                                                    
                                                            <tr>
                                                                <td>Последняя игра</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo date("d.m.y", $stat['lastconnect']);} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Активность</td>
                                                                <td align="center">
                                                                    <?php if($stat['lastconnect']): ?>

                                                                    <div class="progress">
                                                                        <div class="progress-bar <?php $percent =  player_activ($stat['lastconnect']);if($percent > 85) { echo "progress-bar-success";} else if($percent > 25) { echo "progress-bar-warning"; } else { echo "progress-bar-danger"; }; ?>" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php if($percent) echo $percent; else echo "0";?>%">
                                                                        </div>                                                                
                                                                    </div>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                <?php elseif(constant("STATS_TYPE") == "lvlold"): //если старый тип статистики?>
                                                    <table class="table table-condensed table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2"><h4><?=constant("SERV_NAME_".($i+1)); ?></h4></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Очки</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['exp'];} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Уровень</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo $stat['rank'];} ?></td>
                                                            </tr>                                                                                                          
                                                            <tr>
                                                                <td>Последняя игра</td>
                                                                <td align="center"><?php if($stat == 0) { echo "-"; } else { echo date("d.m.y", $stat['lastconnect']);} ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Активность</td>
                                                                <td align="center">
                                                                    <?php if($stat['lastconnect']): ?>

                                                                    <div class="progress">
                                                                        <div class="progress-bar <?php $percent =  player_activ($stat['lastconnect']);if($percent > 85) { echo "progress-bar-success";} else if($percent > 25) { echo "progress-bar-warning"; } else { echo "progress-bar-danger"; }; ?>" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php if($percent) echo $percent; else echo "0";?>%">
                                                                        </div>                                                                
                                                                    </div>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif;?>
                                    <?php endfor; ?>
                                <?php elseif($steam_id): //Если 1 сервер ?>
                                <?php $stat = get_stat($steam_id, 1); ?>
                                <div class="row col-md-12 recent_game">
                                    <?php if(constant("STATS_TYPE") == "lvlnew"): //если новый тип статистики?>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2"><h4><?=constant("SERV_NAME_1"); ?></h4></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Очки</td>
                                                    <td align="center"><?php echo $stat['exp'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Уровень</td>
                                                    <td align="center"><?php echo $stat['rank'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Убийства</td>
                                                    <td align="center"><?php echo $stat['kills'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Смерти</td>
                                                    <td align="center"><?php echo $stat['deaths'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>K/D</td>
                                                    <td align="center"><?php if($stat['deaths'] == 0) {echo $stat['deaths']; } else { echo round($stat["kills"] / $stat['deaths'], 2); } ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Выстрелы</td>
                                                    <td align="center"><?php echo $stat['shoots'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Попадания</td>
                                                    <td align="center"><?php echo $stat['hits'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Точность</td>
                                                    <td align="center"><?php if($stat["hits"] == 0 || $stat["shoots"] == 0) { echo "-"; } else { echo round($stat["hits"] * 100 / $stat["shoots"]) . "%"; } ?></td>
                                                </tr>
                                                <tr>
                                                    <td>В голову</td>
                                                    <td align="center"><?php echo $stat['headshots'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Помощь</td>
                                                    <td align="center"><?php echo $stat['assists'] ?></td>
                                                </tr>                                            
                                                <tr>
                                                    <td>Последняя игра</td>
                                                    <td align="center"><?php echo date("d.m.y", $stat['lastconnect']) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Активность</td>
                                                    <td align="center">
                                                        <div class="progress">
                                                            <div class="progress-bar <?php $percent =  player_activ($stat['lastconnect']);if($percent > 85) { echo "progress-bar-success";} else if($percent > 25) { echo "progress-bar-warning"; } else { echo "progress-bar-danger"; }; ?>" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php if($percent) echo $percent; else echo "0";?>%">
                                                            </div>                                                                
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php elseif(constant("STATS_TYPE") == "lvlold"): //если старый тип статистики?>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2"><h4><?=constant("SERV_NAME_1"); ?></h4></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Очки</td>
                                                    <td align="center"><?php echo $stat['exp'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Уровень</td>
                                                    <td align="center"><?php echo $stat['rank'] ?></td>
                                                </tr>                                                                                           
                                                <tr>
                                                    <td>Последняя игра</td>
                                                    <td align="center"><?php echo date("d.m.y", $stat['lastconnect']) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Активность</td>
                                                    <td align="center">
                                                        <div class="progress">
                                                            <div class="progress-bar <?php $percent =  player_activ($stat['lastconnect']);if($percent > 85) { echo "progress-bar-success";} else if($percent > 25) { echo "progress-bar-warning"; } else { echo "progress-bar-danger"; }; ?>" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php if($percent) echo $percent; else echo "0";?>%">
                                                            </div>                                                                
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                                    <div class="row col-md-12 recent_game">
                                    <br /><h2 align="center">Top Secret</h2><br /><br /><br /></div>
                                <?php endif; ?>

                                <?php if(!empty($player_all_stat_steam)){ //Вывод статистики из стима если есть игрок
                                    foreach ($player_all_stat_steam->playerstats->stats as $key => $value) {
                                        if($value->name == "total_kills" ) {
                                            $total_kills = $value->value;
                                        }
                                        if($value->name == "total_deaths" ) {
                                            $total_deaths = $value->value;
                                        }
                                        if($value->name == "total_time_played" ) {
                                            $total_time_played = $value->value;
                                        }
                                        if($value->name == "total_planted_bombs" ) {
                                            $total_planted_bombs = $value->value;
                                        }
                                        if($value->name == "total_defused_bombs" ) {
                                            $total_defused_bombs = $value->value;
                                        }
                                        if($value->name == "total_wins" ) {
                                            $total_wins = $value->value;
                                        }
                                        if($value->name == "total_damage_done" ) {
                                            $total_damage_done = $value->value;
                                        }
                                        if($value->name == "total_money_earned" ) {
                                            $total_money_earned = $value->value;
                                        }
                                        if($value->name == "total_dominations" ) {
                                            $total_dominations = $value->value;
                                        }
                                        if($value->name == "total_revenges" ) {
                                            $total_revenges = $value->value;
                                        }
                                        if($value->name == "total_shots_hit" ) {
                                            $total_shots_hit = $value->value;
                                        }
                                        if($value->name == "total_shots_fired" ) {
                                            $total_shots_fired = $value->value;
                                        }
                                        if($value->name == "total_kills_headshot" ) {
                                            $total_kills_headshot = $value->value;
                                        }
                                        if($value->name == "total_kills_enemy_blinded" ) {
                                            $total_kills_enemy_blinded = $value->value;
                                        }
                                        if($value->name == "total_kills_knife_fight" ) {
                                            $total_kills_knife_fight = $value->value;
                                        }
                                        if($value->name == "total_kills_enemy_weapon" ) {
                                            $total_kills_enemy_weapon = $value->value;
                                        }    
                                        if($value->name == "total_wins_pistolround" ) {
                                            $total_wins_pistolround = $value->value;
                                        }                                     
                                    } 
                                ?> 
                                <div class="row col-md-12 recent_game">
                                    <h4>По данным Steam</h4>
                                    <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>Реальное время в игре</td>
                                                    <td align="center"><?php echo round($total_time_played/3600, 2); ?> часов</td>
                                                </tr>
                                                <tr>
                                                    <td>Точность стрельбы</td>
                                                    <td align="center"><?php echo round($total_shots_hit * 100/$total_shots_fired, 2); ?>%</td>
                                                </tr>
                                                <tr>
                                                    <td>Убийств</td>
                                                    <td align="center"><?php echo $total_kills; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Убийств в голову</td>
                                                    <td align="center"><?php echo $total_kills_headshot; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Убийств в голову %</td>
                                                    <td align="center"><?php echo round($total_kills_headshot * 100 / $total_kills, 2); ?> %</td>
                                                </tr>
                                                <tr>
                                                    <td>Убито ослепленных врагов</td>
                                                    <td align="center"><?php echo$total_kills_enemy_blinded; ?></td>
                                                </tr>                                                
                                                <tr>
                                                    <td>Убийств оружием врага</td>
                                                    <td align="center"><?php echo $total_kills_enemy_weapon; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Нанесено урона</td>
                                                    <td align="center"><?php echo $total_damage_done; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Смертей</td>
                                                    <td align="center"><?php echo $total_deaths; ?></td>
                                                </tr>    
                                                <tr>
                                                    <td>K/D</td>
                                                    <td align="center"><?php echo round($total_kills / $total_deaths, 2); ?></td>
                                                </tr>                                                                                       
                                                <tr>
                                                    <td>Бомб установлено</td>
                                                    <td align="center"><?php echo $total_planted_bombs; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Бомб обезврежено</td>
                                                    <td align="center"><?php echo $total_defused_bombs; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Победы</td>
                                                    <td align="center"><?php echo $total_wins; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Побед в ножевом бою</td>
                                                    <td align="center"><?php echo $total_kills_knife_fight; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Побед в пистолетных раундах</td>
                                                    <td align="center"><?php echo $total_wins_pistolround; ?></td>
                                                </tr>  
                                                <tr>
                                                    <td>Заработано денег</td>
                                                    <td align="center"><?php echo $total_money_earned; ?></td>
                                                </tr>                                       
                                                <tr>
                                                    <td>Превосходства над игроками</td>
                                                    <td align="center"><?php echo $total_dominations; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Возмездий</td>
                                                    <td align="center"><?php echo $total_revenges; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Достижения</td>
                                                    <td align="center"><?php echo count($player_all_stat_steam->playerstats->achievements); ?> из 167</td>
                                                </tr>
                                                <?php if($total_time_played<1800000 and count($player_all_stat_steam->playerstats->achievements)==167){
                                                    echo "<tr>
                                                            <td align='center' colspan='2'><font color='red'>Возможно взломал достижения</font></td>
                                                         </tr>";
                                                        }else if($total_time_played<360000 and count($player_all_stat_steam->playerstats->achievements)==167){
                                                             echo "<tr>
                                                                    <td>Достижения</td>
                                                                    <td align='center' colspan='2'><font color='red'>Взлом достижений 99%</font></td>
                                                                   </tr>";
                                                        }else{

                                                        }
                                                ?>
                                            </tbody>
                                        </table>
                                </div>
                                <?php }else if($steam_id!=NULL) { ?>
                                    <div class="row col-md-12 recent_game">
                                        <h4>По данным Steam</h4>    
                                        <p>Профиль скрыт</p>
                                    </div>                  
                                <?php } else { } ?>
                            </div>

                            <div class="col-md-4">
                            <div class="game"><?php if(isset($steam_playr_info->response->players[0]->gameextrainfo)) echo "<span class='profile_in_game_header'>В игре</span> <br /><span class='profile_in_game_name'>". $steam_playr_info->response->players[0]->gameextrainfo ."</span>";?></div>
                            <?php if(DEBUG): ?>
                            <pre>
                                <?php var_dump($steam_id); ?>
                                <br/>
                                <?php var_dump($steam_playr_info); ?> 
                            </pre>
                            <?php endif; ?>
                            </div>
                        </div>
                     </div>
                </div>  
                
            </div>
        </div>

        <?php include_once("footer.php");?>
        
    </div>
    <div class="footer-margin"></div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="include/js/bootstrap.js"></script>
</body>

</html>