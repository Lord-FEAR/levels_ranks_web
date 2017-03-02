<?php
include_once('include/config.php');
include_once('include/function.php');
if(DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

//Проверяем данные
if(isset($_GET['search-data']) AND !empty($_GET['search-data']) AND isset($_GET['metod']) AND !empty($_GET['metod'])){
    $search_data = htmlspecialchars($_GET['search-data']);
    $metod = $_GET['metod'];

    if($metod == 'steam'){
        if(SERV_COUNT == 1){ // Если 1 сервер
            $search_result = search_player(1, $search_data, $metod);
            if($search_result['steam']){
                    header("Location: ./player.php?sid=".$search_result['steam']);
                    exit;
                }

        }else{ // Если много серверов
        //echo"test";
            for ($i=0; $i < SERV_COUNT; $i++) { 
                //echo $i;
                $search_result = search_player($i+1, $search_data, $metod);
                if($search_result['steam']){
                    header("Location: ./player.php?sid=".$search_result['steam']);
                    exit;
                }

            }

        }
    }
    
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
            <div class="header col-md-12">
                    <div class="header-content"><span class="logo"><img src="include/img/<?=constant("LR_LOGO");?>" width="176" height="44"></span>
                        <?php if(SERV_COUNT != 1): ?>
                        <a href='./'>Главная</a>
                        <?php for($i = 1; $i < SERV_COUNT + 1; $i++): ?>
                            <font color="gray">|</font> <a href="./?serv=<?=$i?>"><?=constant("SERV_NAME_".$i)?></a>
                        <?php endfor; ?>
                        <?php else:?>
                            <a href='./'><?=SERV_NAME_1?></a>
                        <?php endif;?>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="content col-md-12">                
                <div class="profile_header_bg_texture">
                    <div class="profile_header_inner">
                        <div class="row">
                            <div class="col-md-3 avatar-div"><img src="include/img/noname.png" alt="Avatar" class="avatar"></div>
                            <div class="col-md-6 persona">
                                <div class="persona_name">Top Secret</div>                            
                                <div class="row col-md-12">Top Secret</div>
                                <div class="row col-md-12">Top Secret</div>
                            </div>
                            <div class="col-md-3">
                                <div class="row col-md-12 persona_name">Уровень: ∞</div>
                                <div class="row col-md-12">Top Secret</div>
                                <div class="row col-md-12">Top Secret</div>
                            </div>
                        </div>
                    </div>                    
                </div>
                
                <div class="profile_content">
                     <div class="profile_content_inner">
                        <div class="row">                       
                            <div class="col-md-12"> 

                                <?php if(DEBUG):?>
                                    <div class="row col-md-12 recent_game">
                                        <h3>Включен режим отладки!</h3>
                                        <pre>
                                           
                                        </pre>
                                    </div>
                                <?php endif;?>                                
                            
                                <div class="row col-md-12 recent_game">                                    
                                    <?php if(isset($_GET['error']) AND !empty($_GET['error'])): ?>
                                        <h3>Произошла ошибка, попробуйте ещё раз</h3>
                                    <?php endif; ?>

                                    <?php if(isset($metod) AND $metod == 'name'):?>
                                        <h3>Найдено:</h3>
                                        <?php
                                            if(SERV_COUNT == 1){ // Если 1 сервер
                                                $search_result = search_player(1, $search_data, $metod);
                                                //echo var_dump($search_result);
                                                //echo "<br/>";
                                                for ($i=0; $i < count($search_result); $i++) { 
                                                    echo $i+1 ." <a href=./player.php?sid=". $search_result[$i]['steam'] .">". $search_result[$i]['name'] ."</a><br/>";
                                                }
                                                echo '1 serv';
                                            }else{ // Если много серверов
                                                for ($i=0; $i < constant("SERV_COUNT"); $i++) {
                                                    echo "<h4>". constant("SERV_NAME_".($i+1)) . "</h4>";
                                                        $search_result = search_player($i+1, $search_data, $metod);
                                                        //var_dump($search_result);
                                                        if(!empty($search_result)){
                                                            for ($i1=0; $i1 < count($search_result); $i1++) { 
                                                                echo $i1+1 ." <a href=./player.php?sid=". $search_result[$i1]['steam'] .">". $search_result[$i1]['name'] ."</a><br/>";
                                                            }
                                                        }else{
                                                            echo "Игрок не найден!";
                                                        }
                                                }
                                            }
                                        ?>
                                    <?php endif; ?>

                                    <?php if(isset($_GET['no-search']) AND !empty($_GET['no-search'])): ?>
                                        <h3>Поиск не дал результатов</h3>
                                    <?php endif; ?>

                                    <form action="search.php" method="get">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="metod" id="optionsRadios1" value="name" checked>
                                                По имени
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="metod" id="optionsRadios2" value="steam">
                                                По Steam ID
                                            </label>
                                        </div>
                                        <input type="text" name="search-data" id="optionsRadios3" class="form-control" placeholder="Введите ник или Steam ID">
                                        <br />
                                        <button type="submit" class="btn btn-default">Найти!</button>
                                        <br />
                                        <br />
                                    </form>
                                </div>
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