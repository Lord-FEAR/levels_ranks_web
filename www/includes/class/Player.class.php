<?php
/**
 * @author Lord FEAR
 */

class Player {
    public $steamid, $name, $value, $lvl, $kills, $death, $kd, $shot, $hit, $acc, $inhead, $help, $lastgame, $maxLvl;

    public function __construct($steamid){
        if(preg_match("/STEAM/", $steamid)){
            $this->steamid = $steamid;
        }else {
            $srx = (substr($steamid, -1) % 2 == 0) ? 0 : 1;
            $arx = bcsub($steamid, "76561197960265728");
            if (bccomp($arx, "0") != 1) { throw new InvalidArgumentException("Invalid SteamID"); }
            $arx = bcsub($arx, $srx);
            $arx = bcdiv($arx, 2);            
            $this->steamid = sprintf("STEAM_1:%s:%s", $srx, $arx);
        }
    }    

    public function getMaxLvl($config){
        for($i=0; $i<count($config['server']); $i++){
            $server = new Server($config['server'][$i]['name'], $config['server'][$i]['host'], $config['server'][$i]['dbName'], $config['server'][$i]['login'], $config['server'][$i]['pass'], $config['server'][$i]['port']);
            $lvlarr[$i] = $this->getLvlOnServer($server);
            unset($server);
        }
        return $lvlarr;
    }

    public function getVip($config){
        for($i=0; $i<count($config['server']); $i++){
            $server = new Server($config['server'][$i]['name'], $config['server'][$i]['host'], $config['server'][$i]['dbName'], $config['server'][$i]['login'], $config['server'][$i]['pass'], $config['server'][$i]['port']);
            $vipArr[$i] = ["server" => $config['server'][$i]['name'], "vip" => $this->getVipOnServer($server)];
            unset($server);
        }
        return $vipArr;
    }

    function getVipOnServer($server){
        $pdo = $this->connect($server);
        $stmt = $pdo->prepare("SELECT vip FROM lvl_base WHERE steam = :steam");
        $stmt->execute(array(':steam' => $this->steamid));	
	    unset($pdo);
        $vip = $stmt->fetchColumn();
        return $vip;
    }

    function getLvlOnServer($server){
        $pdo = $this->connect($server);
        $stmt = $pdo->prepare("SELECT rank FROM lvl_base WHERE steam = :steam");
        $stmt->execute(array(':steam' => $this->steamid));	
	    unset($pdo);
        $lvl = $stmt->fetchColumn();
        return $lvl;
    }
    
    function getStat($server) {
    $pdo = $this->connect($server);
    $stmt = $pdo->prepare("SELECT * FROM lvl_base WHERE steam = :steam");
	$stmt->execute(array(':steam' => $this->steamid));	
	unset($pdo);
	$stat = $stmt->fetch();
        if(!$stat){
            $this->name = NULL;
            $this->value = NULL;
            $this->lvl = NULL;
            $this->kills = NULL;
            $this->death = NULL;
            $this->kd = NULL;
            $this->shot = NULL;
            $this->hit = NULL;
            $this->acc = NULL;
            $this->inhead = NULL;
            $this->help = NULL;
            $this->lastgame = NULL;
        }else{
            $this->name = $stat['name'];
            $this->value = $stat['value'];
            $this->lvl = $stat['rank'];
            $this->kills = $stat['kills'];
            $this->death = $stat['deaths'];
            if($this->death!=0){
                $this->kd = round($this->kills / $this->death, 2);
            }else{
                $this->kd = $this->kills;
            }
            $this->shot = $stat['shoots'];
            $this->hit = $stat['hits_all'];
            if($this->shot!=0){
                $this->acc = round($this->hit * 100 / $this->shot);
            }else{
                $this->acc = 0;
            }
            $this->inhead = $stat['headshots'];
            $this->help = $stat['assists'];
            $this->lastgame = $stat['lastconnect']; 
        }     
    }

    function clearRanks($server){
        $pdo = $this->connect($server);
        $stmt = $pdo->prepare('UPDATE lvl_base SET `value`=1000, `rank`=0, `kills`=0, `deaths`=0, `shoots`=0, `hits_all`=0, `hits_head`=0, `hits_chest`=0, `hits_stomach`=0, `hits_arms`=0, `hits_legs`=0, `headshots`=0, `assists`=0 WHERE steam=:steamid');
        $stmt->bindParam(':steamid', $this->steamid, PDO::PARAM_STR);
        $stmt->execute();
        
        unset($pdo);
    }

    function getSteamid(){
        return $this->steamid;
    }
    function setSteamid($steamid){
        $this->steamid = $steamid;
    }

    function getName(){
        return $this->name;
    }
    function setName($name){
        $this->name = $name;
    }

    function getValue(){
        return $this->value;
    }
    function setValue($value){
        $this->value = $value;
    }

    function getLvl(){
        return $this->lvl;
    }
    function setLvl($lvl){
        $this->lvl = $lvl;
    }

    function getKills(){
        return $this->kills;
    }
    function setKills($kills){
        $this->kills = $kills;
    }

    function getDeath(){
        return $this->death;
    }
    function setDeath($death){
        $this->death = $death;
    }

    function getKd(){
        return $this->kd;
    }
    function setKd(){
        $this->kd = round($this->kills / $this->death, 2);
    }

    function getShot(){
        return $this->shot;
    }
    function setShot($shot){
        $this->shot = $shot;
    }

    function getHit(){
        return $this->hit;
    }
    function setHit($hit){
        $this->hit = $hit;
    }

    function getAcc(){
        return $this->acc;
    }
    function setAcc(){
        $this->acc = round($this->hit * 100 / $this->shot);
    }

    function getInhead(){
        return $inhead->inhead;
    }
    function setInhead($inhead){
        $this->inhead = $inhead;
    }

    function getHelp(){
        return $help->help;
    }
    function setHelp($help){
        $this->help = $help;
    }

    function getLastgame(){
        return $this->lastgame;
    }
    function setLastgame($lastgame){
        $this->lastgame = $lastgame;
    }
    
    function connect($server){
        $host = $server->host;
        $charset = "UTF8";
        $db = $server->dbName;
        $user = $server->login;
        $pass = $server->pass;
        $port = $server->port;
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $opt = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        try {
            $pdo = new PDO($dsn, $user, $pass, $opt);
            return $pdo;
        } catch (Exception $ex) {
            echo 'Соединение оборвалось: ' , $ex->getMessage() , '<br><a href="mailto:', EMAIL ,'">При повторной ошибке сообщите администратору!</a>';
            exit();
        }
        
    }
    
    function convert64to32($id){
        $srx = (substr($sid, -1) % 2 == 0) ? 0 : 1;
        $arx = bcsub($sid, "76561197960265728");
        if (bccomp($arx, "0") != 1) { throw new InvalidArgumentException("Invalid SteamID"); }
        $arx = bcsub($arx, $srx);
        $arx = bcdiv($arx, 2);
        return sprintf("STEAM_1:%s:%s", $srx, $arx);
    }
}

?>