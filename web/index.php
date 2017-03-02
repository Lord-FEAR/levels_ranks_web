<?php
include_once('include/config.php');
include_once('include/function.php');
include_once('include/steamauth/steamauth.php');
if(isset($_SESSION['steamid'])) { include_once('include/steamauth/userInfo.php'); }
if(DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

$cur_serv = 0;
if (isset($_GET['serv']) && $_GET['serv'] > 0 && (int)$_GET['serv'] < (constant("SERV_COUNT")+1)){
    $cur_serv = (int)$_GET['serv'];
}
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
            <div class="header col-sm-12">
                <div class="header-content"><span class="logo"><img src="include/img/<?=constant("LR_LOGO");?>" width="176" height="44"></span><!--<span class="steam-ava"><img src="<?php //if(isset($_SESSION['steamid'])) { echo $steamprofile['avatarmedium']; }else{ echo "./include/img/ava.png"; } ?>" alt="ava"></span>-->
                    <?php if(SERV_COUNT != 1): ?>
                        <?php if($cur_serv == 0 AND SERV_COUNT != 1) echo "<font color='#ababab'>Главная</font>"; else echo "<a href='./'>Главная</a>"; ?>
                        <?php for($i = 1; $i < SERV_COUNT + 1; $i++): ?>
                            <?php if($cur_serv == $i): ?>
                            <font color="gray">|</font> <font color="#ababab"><?=constant("SERV_NAME_".$i)?></font>
                            <?php else:?>
                            <font color="gray">|</font> <a href=".?serv=<?=$i?>"><?=constant("SERV_NAME_".$i)?></a>
                            <?php endif;?>
                        <?php endfor; ?>                        
                        <span class="search"><a href="./search.php">Поиск</a></span>                        
                    <?php else:?>
                        <?=SERV_NAME_1?><span class="search"><a href="./search.php">Поиск</a></span>
                    <?php endif;?>
                    <div class="profile"><a href="./player.php"><?php if(isset($_SESSION['steamid'])) { echo "Моя статистика"; } else { echo "Войти"; } ?></a></div>
                </div>                
            </div>
        </div>

        <div class="row">
            <div class="content col-sm-12">
                <div class="profile_content">
                     <div class="profile_content_inner">
                        <div class="row">               
                            <?php if(SERV_COUNT != 1): // Если больше одного сервера ?>
                            
                                <?php if($cur_serv != 0): // Если открыта странийа сервера ?>
                                    <?php
                                        //получаем номер страницы и значение для лимита 
                                        $cur_page = 1;
                                        if (isset($_GET['page']) && $_GET['page'] > 0) 
                                        {
                                            $cur_page = $_GET['page'];
                                        }
                                        $start = ($cur_page - 1) * RECORD_ON_PAGE;
                                        $num_pages=ceil(count_row($cur_serv)/RECORD_ON_PAGE); 

                                        $title_lr = array(0=>"Нет звания", 1=>"Серебро I", 2=>"Серебро II", 3=>"Серебро III", 4=>"Серебро IV", 5=>"Серебро-Элита", 6=>"Серебро-Великий Магистр", 7=>"Золотая Звезда I", 8=>"Золотая Звезда II", 9=>"Золотая Звезда III", 10=>"Золотая Звезда — Магистр", 11=>"Магистр-хранитель I", 12=>"Магистр-хранитель II", 13=>"Магистр-хранитель Элита", 14=>"Заслуженный Магистр-хранитель", 15=>"Легендарный Беркут", 16=>"Легендарный Беркут — Магистр", 17=>"Великий Магистр Высшего Ранга", 18=>"Всемирная Элита", );
                                    ?>

                                    <?php if(DEBUG): ?>                
                                        <pre>
                                            <h3 align="center"><font color="red">Включен режим отладки!</font></h3>
                                            <?php 
                                                //echo $cur_serv;
                                                echo "<br />config.php 123<br />";
                                                echo "<font color='green'>// Имя сайта</font> <br />";
                                                echo "NAME_PROJ - " . NAME_PROJ . "<br />";
                                                echo "<font color='green'>// Тип званий</font> <br />";
                                                echo "LVL_18_OR_55 - " . LVL_18_OR_55 . "<br />";
                                                echo "<font color='green'>// Записей на странице</font> <br />";
                                                echo "RECORD_ON_PAGE - " . RECORD_ON_PAGE . "<br />";
                                                echo "<font color='green'>// Подключено серверов</font> <br />";
                                                echo "SERV_COUNT - " . SERV_COUNT . "<br />";
                                                echo "<font color='green'>// Название сервера</font> <br />";
                                                echo "SERV_NAME_" .$cur_serv. ": " . constant("SERV_NAME_" . $cur_serv) . "<br />";
                                                echo "<font color='green'>// Игроков в стате</font> <br />";
                                                echo "Игроков в стате - " . count_row($cur_serv) . "<br />";
                                                var_dump($num_pages);          
                                                var_dump($cur_page);     
                                                //echo "страниц - " . $num_pages=ceil(count_row()/RECORD_ON_PAGE);                       
                                            ?>
                                        </pre>
                                    <?php endif; ?>
                                
                                    <div class="row col-sm-12 recent_game table-responsive">
                                        <?php if(constant("STATS_TYPE") == "lvlnew"): //если новый тип статистики?> 
                                            <table class="table table-condensed table-striped">
                                                <?php $data = lr_array($start, $cur_serv); ?>
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Игрок</th>
                                                        <th>Очки</th>
                                                        <?php //<th>Звание</th> ?>
                                                        <th>Уровень</th>
                                                        <th>Убийств</th>
                                                        <th>Смертей</th>
                                                        <th>K/D</th>
                                                        <th>Выстрелов</th>
                                                        <th>Попаданий</th>
                                                        <th>Точность</th>
                                                        <th>Последняя игра</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for ($i=0; $i < RECORD_ON_PAGE; $i++):  ?>
                                                        <?php if(!isset($data[$i]["steam"])) {} else { ?>
                                                            <tr> <?php //ссылка в строке  player.php?sid=<?php echo $data[$i]["steam"]; ?> 
                                                                <td><?php $start++; echo $start ?> </td>
                                                                <td class="tb-player"><a href="player.php?sid=<?php echo $data[$i]["steam"]; ?>"><?php echo $data[$i]["name"]; ?></a></td>
                                                                <td><?php echo $data[$i]["exp"]; ?></td>
                                                                <?php //<td><?php $rank = round($data[$i]["rank"] / 3); ?/><img src="include/img/rank/<?php echo round($data[$i]["rank"] / 3); ?/>.png" alt="<?php echo $title_lr[$rank]; ?/>" title="<?php echo $title_lr[$rank]; ?/>" height="35"></td> ?>
                                                                <td><?php echo $data[$i]["rank"]; ?></td>
                                                                <td><?php echo $data[$i]["kills"]; ?></td>
                                                                <td><?php echo $data[$i]["deaths"]; ?></td>
                                                                <td><?php if($data[$i]["deaths"] == 0) {echo $data[$i]["kills"]; } else { echo round($data[$i]["kills"] / $data[$i]["deaths"], 2); } ?></td>
                                                                <td><?php echo $data[$i]["shoots"]; ?></td>
                                                                <td><?php echo $data[$i]["hits"]; ?></td>
                                                                <td><?php if($data[$i]["hits"] == 0 || $data[$i]["shoots"] == 0) { echo "-"; } else { echo round($data[$i]["hits"] * 100 / $data[$i]["shoots"]) . "%"; } ?></td>
                                                                <td><?php echo date("d.m.y", $data[$i]["lastconnect"]); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php endfor; ?>
                                                </tbody>
                                            </table>
                                        <?php elseif(constant("STATS_TYPE") == "lvlold"): // если старый тип статистики?>
                                            <table class="table table-condensed table-striped">
                                            <?php $data = lr_array($start, $cur_serv); ?>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Игрок</th>
                                                    <th>Очки</th>
                                                    <?php //<th>Звание</th> ?>
                                                    <th>Уровень</th>                                                    
                                                    <th>Последняя игра</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for ($i=0; $i < RECORD_ON_PAGE; $i++):  ?>
                                                    <?php if(!isset($data[$i]["steam"])) {} else { ?>
                                                        <tr> <?php //ссылка в строке  player.php?sid=<?php echo $data[$i]["steam"]; ?> 
                                                            <td><?php $start++; echo $start ?> </td>
                                                            <td class="tb-player"><a href="player.php?sid=<?php echo $data[$i]["steam"]; ?>"><?php echo $data[$i]["name"]; ?></a></td>
                                                            <td><?php echo $data[$i]["exp"]; ?></td>
                                                            <?php //<td><?php $rank = round($data[$i]["rank"] / 3); ?/><img src="include/img/rank/<?php echo round($data[$i]["rank"] / 3); ?/>.png" alt="<?php echo $title_lr[$rank]; ?/>" title="<?php echo $title_lr[$rank]; ?/>" height="35"></td> ?>
                                                            <td><?php echo $data[$i]["rank"]; ?></td>                                                            
                                                            <td><?php echo date("d.m.y", $data[$i]["lastconnect"]); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                        <?php endif; ?>
                                    </div>

                                    <div class="pagination-nav">
                                        <nav aria-label="nav-test">
                                            <ul class="pagination pagination-sm">
                                                <li>
                                                    <a href="?serv=<?=$cur_serv?>&page=1" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;&laquo;</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="?serv=<?=$cur_serv?>&page=<?=$cur_page-1?>" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <?php if($num_pages<15): // если страниц меньше 15 показываем все?>
                                                    <?php
                                                        $page = 0;
                                                        while ($page++ < $num_pages) {
                                                            if($page == $cur_page){
                                                                echo sprintf('<li class="active"><a href="#">%s <span class="sr-only">(current)</span></a></li>', $page);
                                                            }else{
                                                                echo sprintf('<li><a href="?serv=%s&page=%s">%s<span aria-hidden="true"></span></a></li>', $cur_serv, $page, $page);
                                                            }
                                                        }
                                                    ?>
                                                <?php else: // Если страниц больше 15 выводим по 11 переходов?>
                                                    <?php if($cur_page<=6): // Первые страницы?>
                                                        <?php for ($a=1; $a < 12; $a++): ?>
                                                            <?php if($a == $cur_page):?>
                                                                <li class="active"><a href="#"><?=$cur_page?> <span class="sr-only">(current)</span></a></li>
                                                            <?php else:?>
                                                            <li>
                                                                <a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a>
                                                            </li>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                        <li>
                                                            <a href="#">...</a>
                                                        </li>
                                                        <li>
                                                            <a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>"><?=$num_pages?></a>
                                                        </li>
                                                    <?php elseif($cur_page>=($num_pages - 6)): // Последние страницы?>
                                                        <li>
                                                            <a href="?serv=<?=$cur_serv?>&page=1">1</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">...</a>
                                                        </li>
                                                        
                                                        <?php for ($a=($num_pages-10); $a <= $num_pages; $a++): ?>
                                                            <?php if($a == $cur_page):?>
                                                                <li class="active"><a href="#"><?=$cur_page?> <span class="sr-only">(current)</span></a></li>
                                                            <?php else:?>
                                                            <li>
                                                                <a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a>
                                                            </li>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>                                                                                                    
                                                    <?php else: // Страницы в центре ?>
                                                        <li>
                                                            <a href="?serv=<?=$cur_serv?>&page=1">1</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">...</a>
                                                        </li>   
                                                        <?php for ($a=($cur_page - 5); $a < ($cur_page+6); $a++): ?>
                                                            <?php if(($a) == $cur_page):?>
                                                                <li class="active"><a href="#"><?=$cur_page?> <span class="sr-only">(current)</span></a></li>
                                                            <?php else:?>
                                                            <li>
                                                                <a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a>
                                                            </li>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                        <li>
                                                            <a href="#">...</a>
                                                        </li>
                                                        <li>
                                                            <a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>"><?=$num_pages?></a>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endif; ?>                                                
                                                <li>
                                                    <a href="?serv=<?=$cur_serv?>&page=<?=$cur_page+1?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>

                                <?php else: //Топ на главной если не открыта страница сервера ?>

                                    <?php if(DEBUG): ?>                
                                    <pre>
                                        <h3 align="center"><font color="red">Включен режим отладки!</font></h3>
                                        <?php 
                                            //echo $cur_serv;
                                            echo "<br />config.php 321<br />";
                                            echo "<font color='green'>// Имя сайта</font> <br />";
                                            echo "NAME_PROJ - " . NAME_PROJ . "<br />";
                                            echo "<font color='green'>// Тип званий</font> <br />";
                                            echo "LVL_18_OR_55 - " . LVL_18_OR_55 . "<br />";
                                            echo "<font color='green'>// Записей на странице</font> <br />";
                                            echo "RECORD_ON_PAGE - " . RECORD_ON_PAGE . "<br />";
                                            echo "<font color='green'>// Подключено серверов</font> <br />";
                                            echo "SERV_COUNT - " . SERV_COUNT . "<br />";
                                            echo "<font color='green'>// Название сервера</font> <br />";
                                            //echo "SERV_NAME_" .$cur_serv. ": " . constant("SERV_NAME_" . $cur_serv) . "<br />";
                                        // echo "<font color='green'>// Игроков в стате</font> <br />";
                                        // echo "Игроков в стате - " . count_row($cur_serv) . "<br />";
                                            //var_dump(lr_array(10));          
                                            //var_dump(count_row());     
                                            //echo "страниц - " . $num_pages=ceil(count_row()/RECORD_ON_PAGE);                       
                                        ?>
                                    </pre>
                                    <?php endif; ?>

                                    <?php for($s=0; $s <  SERV_COUNT; $s++):?>                                        
                                        <div class="row col-sm-12 recent_game table-responsive">
                                           <h4> <?=constant("SERV_NAME_".($s+1))?></h4>
                                           <p>Всего игроков - <?php echo count_row($s+1);?></p>
                                            <?php $data = top10($s+1); ?>
                                            <?php if(constant("STATS_TYPE") == "lvlnew"): //если новый тип статистики?>
                                                <table class="table table-condensed table-striped">                                                
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Игрок</th>
                                                            <th>Очки</th>
                                                            <?php //<th>Звание</th> ?>
                                                            <th>Уровень</th>
                                                            <th>Убийств</th>
                                                            <th>Смертей</th>
                                                            <th>K/D</th>
                                                            <th>Выстрелов</th>
                                                            <th>Попаданий</th>
                                                            <th>Точность</th>
                                                            <th>Последняя игра</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $start1=0; ?>
                                                        <?php for ($i=0; $i < RECORD_ON_PAGE; $i++):  ?>
                                                            <?php if(!isset($data[$i]["steam"])) {} else { ?>
                                                                <tr> <?php //ссылка в строке  player.php?sid=<?php echo $data[$i]["steam"]; ?> 
                                                                    <td><?php $start1++; echo $start1 ?> </td>
                                                                    <td class="tb-player"><a href="player.php?sid=<?php echo $data[$i]["steam"]; ?>"><?php echo $data[$i]["name"]; ?></a></td>
                                                                    <td><?php echo $data[$i]["exp"]; ?></td>
                                                                    <?php //<td><?php $rank = round($data[$i]["rank"] / 3); ?/><img src="include/img/rank/<?php echo round($data[$i]["rank"] / 3); ?/>.png" alt="<?php echo $title_lr[$rank]; ?/>" title="<?php echo $title_lr[$rank]; ?/>" height="35"></td> ?>
                                                                    <td><?php echo $data[$i]["rank"]; ?></td>
                                                                    <td><?php echo $data[$i]["kills"]; ?></td>
                                                                    <td><?php echo $data[$i]["deaths"]; ?></td>
                                                                    <td><?php if($data[$i]["deaths"] == 0) {echo $data[$i]["kills"]; } else { echo round($data[$i]["kills"] / $data[$i]["deaths"], 2); } ?></td>
                                                                    <td><?php echo $data[$i]["shoots"]; ?></td>
                                                                    <td><?php echo $data[$i]["hits"]; ?></td>
                                                                    <td><?php if($data[$i]["hits"] == 0 || $data[$i]["shoots"] == 0) { echo "-"; } else { echo round($data[$i]["hits"] * 100 / $data[$i]["shoots"]) . "%"; } ?></td>
                                                                    <td><?php echo date("d.m.y", $data[$i]["lastconnect"]); ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php endfor; ?>
                                                    </tbody>
                                                </table>
                                            <?php elseif(constant("STATS_TYPE") == "lvlold"): //если старый тип статистики?>
                                                <table class="table table-condensed table-striped">                                                
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Игрок</th>
                                                            <th>Очки</th>
                                                            <?php //<th>Звание</th> ?>
                                                            <th>Уровень</th>                                                            
                                                            <th>Последняя игра</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $start1=0; ?>
                                                        <?php for ($i=0; $i < RECORD_ON_PAGE; $i++):  ?>
                                                            <?php if(!isset($data[$i]["steam"])) {} else { ?>
                                                                <tr> <?php //ссылка в строке  player.php?sid=<?php echo $data[$i]["steam"]; ?> 
                                                                    <td><?php $start1++; echo $start1 ?> </td>
                                                                    <td class="tb-player"><a href="player.php?sid=<?php echo $data[$i]["steam"]; ?>"><?php echo $data[$i]["name"]; ?></a></td>
                                                                    <td><?php echo $data[$i]["exp"]; ?></td>
                                                                    <?php //<td><?php $rank = round($data[$i]["rank"] / 3); ?/><img src="include/img/rank/<?php echo round($data[$i]["rank"] / 3); ?/>.png" alt="<?php echo $title_lr[$rank]; ?/>" title="<?php echo $title_lr[$rank]; ?/>" height="35"></td> ?>
                                                                    <td><?php echo $data[$i]["rank"]; ?></td>                                                                    
                                                                    <td><?php echo date("d.m.y", $data[$i]["lastconnect"]); ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php endfor; ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        </div>
                                    <?php endfor;?>
                                    
                                <?php endif; // Конец проверки топ или сервер, если серверов больше 1?>

                            <?php else: // Если 1 сервер ?>
                                <?php
                                    //получаем номер страницы и значение для лимита 
                                    $cur_page = 1;
                                    if (isset($_GET['page']) && $_GET['page'] > 0) 
                                    {
                                        $cur_page = $_GET['page'];
                                    }
                                    $start = ($cur_page - 1) * RECORD_ON_PAGE;
                                    $num_pages=ceil(count_row(1)/RECORD_ON_PAGE); 

                                    $title_lr = array(0=>"Нет звания", 1=>"Серебро I", 2=>"Серебро II", 3=>"Серебро III", 4=>"Серебро IV", 5=>"Серебро-Элита", 6=>"Серебро-Великий Магистр", 7=>"Золотая Звезда I", 8=>"Золотая Звезда II", 9=>"Золотая Звезда III", 10=>"Золотая Звезда — Магистр", 11=>"Магистр-хранитель I", 12=>"Магистр-хранитель II", 13=>"Магистр-хранитель Элита", 14=>"Заслуженный Магистр-хранитель", 15=>"Легендарный Беркут", 16=>"Легендарный Беркут — Магистр", 17=>"Великий Магистр Высшего Ранга", 18=>"Всемирная Элита", );
                                ?>

                                <?php if(DEBUG): ?>                
                                    <pre>
                                        <h3 align="center"><font color="red">Включен режим отладки!</font></h3>
                                        <?php 
                                            //echo $cur_serv;
                                            echo "<br />config.php 123<br />";
                                            echo "<font color='green'>// Имя сайта</font> <br />";
                                            echo "NAME_PROJ - " . NAME_PROJ . "<br />";
                                            echo "<font color='green'>// Тип званий</font> <br />";
                                            echo "LVL_18_OR_55 - " . LVL_18_OR_55 . "<br />";
                                            echo "<font color='green'>// Записей на странице</font> <br />";
                                            echo "RECORD_ON_PAGE - " . RECORD_ON_PAGE . "<br />";
                                            echo "<font color='green'>// Подключено серверов</font> <br />";
                                            echo "SERV_COUNT - " . SERV_COUNT . "<br />";
                                            echo "<font color='green'>// Название сервера</font> <br />";
                                            echo "SERV_NAME_1" . constant("SERV_NAME_1") . "<br />";
                                            echo "<font color='green'>// Игроков в стате</font> <br />";
                                            echo "Игроков в стате - " . count_row(1) . "<br />";
                                            //var_dump(lr_array(10));          
                                            //var_dump(count_row());     
                                            //echo "страниц - " . $num_pages=ceil(count_row()/RECORD_ON_PAGE);                       
                                        ?>
                                    </pre>
                                <?php endif; ?>

                                <div class="row col-sm-12 recent_game table-responsive">
                                    <?php if(constant("STATS_TYPE") == "lvlnew"): //если новый тип статистики?>
                                        <p>Всего игроков - <?php echo count_row(1);?></p>
                                        <table class="table table-condensed table-striped">
                                            <?php $data = lr_array($start, 1); ?>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Игрок</th>
                                                    <th>Очки</th>
                                                    <?php //<th>Звание</th> ?>
                                                    <th>Уровень</th>
                                                    <th>Убийств</th>
                                                    <th>Смертей</th>
                                                    <th>K/D</th>
                                                    <th>Выстрелов</th>
                                                    <th>Попаданий</th>
                                                    <th>Точность</th>
                                                    <th>Последняя игра</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for ($i=0; $i < RECORD_ON_PAGE; $i++):  ?>
                                                    <?php if(!isset($data[$i]["steam"])) {} else {?>
                                                        <tr> <?php //ссылка в строке  player.php?sid=<?php echo $data[$i]["steam"]; ?> 
                                                            <td><?php $start++; echo $start ?> </td>
                                                            <td class="tb-player"><a href="player.php?sid=<?php echo $data[$i]["steam"]; ?>"><?php echo $data[$i]["name"]; ?></a></td>
                                                            <td><?php echo $data[$i]["exp"]; ?></td>
                                                            <?php //<td><?php $rank = round($data[$i]["rank"] / 3); ?/><img src="include/img/rank/<?php echo round($data[$i]["rank"] / 3); ?/>.png" alt="<?php echo $title_lr[$rank]; ?/>" title="<?php echo $title_lr[$rank]; ?/>" height="35"></td> ?>
                                                            <td><?php echo $data[$i]["rank"]; ?></td>
                                                            <td><?php echo $data[$i]["kills"]; ?></td>
                                                            <td><?php echo $data[$i]["deaths"]; ?></td>
                                                            <td><?php if($data[$i]["deaths"] == 0) {echo $data[$i]["kills"]; } else { echo round($data[$i]["kills"] / $data[$i]["deaths"], 2); } ?></td>
                                                            <td><?php echo $data[$i]["shoots"]; ?></td>
                                                            <td><?php echo $data[$i]["hits"]; ?></td>
                                                            <td><?php if($data[$i]["hits"] == 0 || $data[$i]["shoots"] == 0) { echo "-"; } else { echo round($data[$i]["hits"] * 100 / $data[$i]["shoots"]) . "%"; } ?></td>
                                                            <td><?php echo date("d.m.y", $data[$i]["lastconnect"]); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    <?php elseif(constant("STATS_TYPE") == "lvlold"): //если старый тип статистики?>
                                        <p>Всего игроков - <?php echo count_row(1);?></p>
                                        <table class="table table-condensed table-striped">
                                            <?php $data = lr_array($start, 1); ?>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Игрок</th>
                                                    <th>Очки</th>
                                                    <?php //<th>Звание</th> ?>
                                                    <th>Уровень</th>                                                    
                                                    <th>Последняя игра</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for ($i=0; $i < RECORD_ON_PAGE; $i++):  ?>
                                                    <?php if(!isset($data[$i]["steam"])) {} else {?>
                                                        <tr> <?php //ссылка в строке  player.php?sid=<?php echo $data[$i]["steam"]; ?> 
                                                            <td><?php $start++; echo $start ?> </td>
                                                            <td class="tb-player"><a href="player.php?sid=<?php echo $data[$i]["steam"]; ?>"><?php echo $data[$i]["name"]; ?></a></td>
                                                            <td><?php echo $data[$i]["exp"]; ?></td>
                                                            <?php //<td><?php $rank = round($data[$i]["rank"] / 3); ?/><img src="include/img/rank/<?php echo round($data[$i]["rank"] / 3); ?/>.png" alt="<?php echo $title_lr[$rank]; ?/>" title="<?php echo $title_lr[$rank]; ?/>" height="35"></td> ?>
                                                            <td><?php echo $data[$i]["rank"]; ?></td>                                                            
                                                            <td><?php echo date("d.m.y", $data[$i]["lastconnect"]); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>

                                <div class="pagination-nav">
                                        <nav aria-label="nav-test">
                                            <ul class="pagination pagination-sm">
                                                <li>
                                                    <a href="?serv=<?=$cur_serv?>&page=1" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;&laquo;</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="?serv=<?=$cur_serv?>&page=<?=$cur_page-1?>" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <?php if($num_pages<15): // если страниц меньше 15 показываем все?>
                                                     <?php
                                                        $page = 0;
                                                        while ($page++ < $num_pages) {
                                                            if($page == $cur_page){
                                                                echo sprintf('<li class="active"><a href="#">%s <span class="sr-only">(current)</span></a></li>', $page);
                                                            }else{
                                                                echo sprintf('<li><a href="?serv=%s&page=%s">%s<span aria-hidden="true"></span></a></li>', $cur_serv, $page, $page);
                                                            }
                                                        }
                                                    ?>
                                                <?php else: // Если страниц больше 15 выводим по 11 переходов?>
                                                    <?php if($cur_page<=6): // Первые страницы?>
                                                        <?php for ($a=1; $a < 12; $a++): ?>
                                                            <?php if($a == $cur_page):?>
                                                                <li class="active"><a href="#"><?=$cur_page?> <span class="sr-only">(current)</span></a></li>
                                                            <?php else:?>
                                                            <li>
                                                                <a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a>
                                                            </li>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                        <li>
                                                            <a href="#">...</a>
                                                        </li>
                                                        <li>
                                                            <a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>"><?=$num_pages?></a>
                                                        </li>
                                                    <?php elseif($cur_page>=($num_pages - 6)): // Последние страницы?>
                                                        <li>
                                                            <a href="?serv=<?=$cur_serv?>&page=1">1</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">...</a>
                                                        </li>
                                                        
                                                        <?php for ($a=($num_pages-10); $a <= $num_pages; $a++): ?>
                                                            <?php if($a == $cur_page):?>
                                                                <li class="active"><a href="#"><?=$cur_page?> <span class="sr-only">(current)</span></a></li>
                                                            <?php else:?>
                                                            <li>
                                                                <a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a>
                                                            </li>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>                                                                                                    
                                                    <?php else: // Страницы в центре ?>
                                                        <li>
                                                            <a href="?serv=<?=$cur_serv?>&page=1">1</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">...</a>
                                                        </li>   
                                                        <?php for ($a=($cur_page - 5); $a < ($cur_page+6); $a++): ?>
                                                            <?php if(($a) == $cur_page):?>
                                                                <li class="active"><a href="#"><?=$cur_page?> <span class="sr-only">(current)</span></a></li>
                                                            <?php else:?>
                                                            <li>
                                                                <a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a>
                                                            </li>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                        <li>
                                                            <a href="#">...</a>
                                                        </li>
                                                        <li>
                                                            <a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>"><?=$num_pages?></a>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endif; ?>                                                
                                                <li>
                                                    <a href="?serv=<?=$cur_serv?>&page=<?=$cur_page+1?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                            <?php endif; // Конец проверки неколько или один сервер ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once("footer.php"); ?>
        
    </div>
    <div class="footer-margin"></div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="include/js/bootstrap.js"></script>
</body>

</html>