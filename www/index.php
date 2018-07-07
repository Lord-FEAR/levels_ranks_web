<?php
    $config = include_once("./includes/configV2.php");
    include_once("./includes/steamauth/steamauth.php");
    include_once('./includes//SourceQuery/bootstrap.php');
    spl_autoload_register(function ($class){
        include './includes/class/' . $class . '.class.php';
    });

    use xPaw\SourceQuery\SourceQuery;

    if($config['DEBUG']){
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    function getServerInfo($ip, $port){
        $timeout = 1;
        $Query = new SourceQuery();
        try {
            $Query->Connect($ip, $port, $timeout, SourceQuery :: GOLDSOURCE);
        } catch(Exception $e) {
            return $e->getMessage();
        }
    
        try {
            $info = $Query->GetInfo();
            $players = $Query->GetPlayers();

        } catch(Exception $e) {
            return $e->getMessage();
        }
    
        // Отключение от сервера
        $Query->Disconnect();
        $allInfo = array((object)$info, $players);
        return $allInfo;
    }

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
        <link rel="stylesheet" type="text/css" href="./includes/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="./includes/css/bootstrap-theme.css">
        <link rel="stylesheet" type="text/css" href="./includes/css/style-steam.css">  

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

            <?php if($config['DEBUG']=="TRUE"): ?>
            <div class="row">
                <div class="col s12">
                    <div class="card-panel">
                        <span class="black-text">
                            <pre>
                                <?php
                                    //var_dump($config['server']);
                                ?>
                            </pre>
                        </span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php
                include_once("./includes/server/server-status.php"); 
            ?>

            <?php
                // Footer
                include("./includes/footer.php");
            ?>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="./includes/js/bootstrap.min.js"></script>
    </body>
</html>