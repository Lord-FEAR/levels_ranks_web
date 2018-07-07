<?php
    $config = include("./includes/configV2.php");
	include("./includes/steamauth/steamauth.php");
    spl_autoload_register(function ($class){
        include './includes/class/' . $class . '.class.php';
    });
    $cur_serv = "search";
    
    if($config['DEBUG']){
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    
    //Проверяем данные
    if(isset($_GET['search-data']) AND !empty($_GET['search-data']) AND isset($_GET['metod']) AND !empty($_GET['metod'])){
        $search_data = htmlspecialchars($_GET['search-data']);
        $metod = $_GET['metod'];
        
        if($metod == 'steam'){
            header("Location: ./player.php?sid=".$search_data);
            exit;
        }
    }else{
        $search_data = NULL;
        $metod = NULL;
    }
?>

<!DOCTYPE html>
<html lang="ru">
<html>
    <head>
        <meta charset="UTF-8">
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
												<div class="name">&nbsp;</div>
												<div class="xp">&nbsp;</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row col-md-12"></div>
									<div class="row col-md-12">&nbsp;</div>
								</div>
                            </div>
                        </div>                    
                    </div>
					
					<div class="profile_content">
						<div class="profile_content_inner">
							<div class="row">                       
								<div class="col-md-12">
									
									<?php
										if(isset($search_data)){
											for ($i = 0; $i < count($config['server']); $i++) {
												$server = new Server($config['server'][$i]['name'], $config['server'][$i]['host'], $config['server'][$i]['dbName'], $config['server'][$i]['login'], $config['server'][$i]['pass'], $config['server'][$i]['port']);
												$data = $server->searchPlayer($search_data, $metod);
												//var_dump($data);
												if(!empty($data)){
													echo '<h5>', $server->name , '</h5>';
													for ($i1=0; $i1 < count($data); $i1++) { 
														echo $i1+1 ." <a href=./player.php?sid=". $data[$i1]['steam'] .">". $data[$i1]['name'] ."</a><br/>";
													}
												}else{
													echo '<h5>', $server->name , '</h5> Не найдено!';
												}
												unset($server);
											}
										}
									?>
									
									<form action="search.php" method="get">                                
										<p>
											<input class="with-gap" name="metod" type="radio" id="optionsRadios1" value="name" checked/>
											<label for="optionsRadios1">По имени</label>
										</p>
										<p>
											<input class="with-gap" name="metod" type="radio" id="optionsRadios2" value="steam"/>
											<label for="optionsRadios2">По Steam ID</label>
										</p>
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

            <?php
                // Footer
                include("./includes/footer.php");
            ?>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="./includes/js/bootstrap.min.js"></script>
    </body>
</html>