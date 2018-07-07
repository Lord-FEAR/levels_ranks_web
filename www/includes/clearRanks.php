 <?php
    $config = include("./configV2.php");
    spl_autoload_register(function ($class){
        include './class/' . $class . '.class.php';
    });
    //var_dump($_POST);
    $steam_id = $_POST['user_id'];
    $server = new Server($config['server'][$_POST['server']]['name'], $config['server'][$_POST['server']]['host'], $config['server'][$_POST['server']]['dbName'], $config['server'][$_POST['server']]['login'], $config['server'][$_POST['server']]['pass'], $config['server'][$_POST['server']]['port']);
    $player = new Player($steam_id);

    $player->clearRanks($server);
    //var_dump($player);
    unset($player);
    unset($server);
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
?>