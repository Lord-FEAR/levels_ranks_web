<?php
include_once('config.php');

$status_type = constant("STATS_TYPE");

function connect_lr($serv_id){
	$host = constant("DB_HOST_".$serv_id);
	$charset = "UTF8";
	$db = constant("DB_DB_".$serv_id);
	$user = constant("DB_LOGIN_".$serv_id);
	$pass = constant("DB_PASSWD_".$serv_id);
	$port = constant("DB_PORT_".$serv_id);
	$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
	$opt = array(
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	);
	try {
		$pdo = new PDO($dsn, $user, $pass, $opt);
		return $pdo;
	} catch (Exception $e) {
		echo '<br><b>Соединение оборвалось: ' , $e->getMessage() , '</b>';
		exit();
	}
}
function count_row($serv_id){
	$pdo = connect_lr($serv_id);
	$stmt = $pdo->query("SELECT COUNT(*) as count FROM " . constant("STATS_TYPE") )->fetchColumn();
	//$pdo = NULL;
	unset($pdo);
	return $stmt;
}

function lr_array($page, $serv_id){
	$pdo = connect_lr($serv_id);
	//$sql = "SELECT * FROM " . constant("STATS_TYPE") . " ORDER BY exp DESC LIMIT " . $page . "," . RECORD_ON_PAGE;
	//$stmt = $pdo->prepare($sql);
	//$stmt->execute();

	$test = $pdo->query("SELECT * FROM " . constant("STATS_TYPE") . " ORDER BY exp DESC LIMIT " . $page . "," . RECORD_ON_PAGE)->fetchAll(PDO::FETCH_ASSOC);
	//$pdo = NULL;
	unset($pdo);
	//$test = $stmt->fetchAll();
	return $test;
}

function get_stat($steamid, $serv_id){
	$pdo = connect_lr($serv_id);
	$stmt = $pdo->prepare("SELECT * FROM " . constant("STATS_TYPE") . " WHERE steam = :steam");
	$stmt->execute(array(':steam' => $steamid));
	//$pdo = NULL;
	unset($pdo);
	$stat = $stmt->fetch();
	return $stat;
}

function get_lvl($steam_id){
	$lvl_arr = array();
	for ($i=1; ($i-1) < SERV_COUNT; $i++) { 
		$pdo = connect_lr($i);
		//$stmt = $pdo->query("SELECT rank FROM " . constant("STATS_TYPE") . " WHERE steam = '$steam_id'");		
		//$stmt->execute();
		//$pdo = NULL;
		$stmt = $pdo->prepare("SELECT rank FROM " . constant("STATS_TYPE") . " WHERE steam = :steam");
		$stmt->execute(array(':steam' => $steam_id));
		unset($pdo);
		//$stat="stat_".$i;
		$stat = $stmt->fetch(PDO::FETCH_LAZY);
		$lvl_arr[$i] = $stat['rank'];
	}
	
	return max($lvl_arr);
}

function player_activ($linux_time){
	$time = time() - $linux_time;
	if($time > 2629743) return 0;
	$day = round($time/86400);
	if($day==0) return 100;
	$percent = 100;
	for ($i=0; $i < $day; $i++) { 
		$percent = $percent - 3.3;
	}
	return $percent;
}

function top10($serv_id){
	$pdo = connect_lr($serv_id);
	$stmt = $pdo->prepare("SELECT * FROM " . constant("STATS_TYPE") . " ORDER BY exp DESC LIMIT 10");
	$stmt->execute();
	//$pdo = NULL;
	unset($pdo);
	$stat = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $stat;
}

function search_player($serv_id, $search, $metod){
	$pdo = connect_lr($serv_id);
	if($metod == "steam"){
		//$stmt = $pdo->query("SELECT steam FROM " . constant("STATS_TYPE") . " WHERE steam = '$search'");
		$stmt = $pdo->prepare("SELECT steam FROM " . constant("STATS_TYPE") . " WHERE steam = :steam");
		$stmt->execute(array(':steam' => $search));
		//$pdo = NULL;
		unset($pdo);
		$stat = $stmt->fetch(PDO::FETCH_LAZY);
		return $stat;
	}elseif($metod == "name"){
		//$stmt = $pdo->query("SELECT name, steam FROM lvlnew WHERE name = '$search'");
		//$pdo = NULL;
		//$stat = $stmt->fetch();
		$name = "%$search%";
		$stmt = $pdo->prepare("SELECT name, steam FROM " . constant("STATS_TYPE") . " WHERE name LIKE ?");
		$stmt->execute(array($name));
		//$pdo = NULL;
		unset($pdo);
		$stat = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $stat;
	}else{
		return 0;
	}
}

//steam 
function get_player_all_stat_steam($id){
	$id64 = convert32to64($id);
	$shit = file_get_contents("http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=" . constant("STEAM_WEB_API") . "&steamid=$id64");
	$json = json_decode($shit);
	return $json;
}
function get_player_all_stat_steam_login($id){
	$shit = file_get_contents("http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=" . constant("STEAM_WEB_API") . "&steamid=$id");
	$json = json_decode($shit);
	return $json;
}

function get_player_info_steam($id){
	$id64 = convert32to64($id);
	$shit = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . constant("STEAM_WEB_API") . "&steamids=$id64");
	$json = json_decode($shit);
	return $json;
}

function convert32to64($steam_id){
	list( , $m1, $m2) = explode(':', $steam_id, 3);
	list($steam_cid, ) = explode('.', bcadd((((int) $m2 * 2) + $m1), '76561197960265728'), 2);
	return $steam_cid;
}

function convert64to32($sid){
	if(!$sid){
		return "Ошибка";
	}
	$srx = (substr($sid, -1) % 2 == 0) ? 0 : 1;
    $arx = bcsub($sid, "76561197960265728");
    if (bccomp($arx, "0") != 1) { throw new InvalidArgumentException("Invalid SteamID"); }
    $arx = bcsub($arx, $srx);
    $arx = bcdiv($arx, 2);
    return sprintf("STEAM_0:%s:%s", $srx, $arx);
}
?>