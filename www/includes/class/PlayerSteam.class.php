<?php
/**
 * @author LordF
 */
 
class PlayerSteam extends Player {
    public $steamid64, $totalTime, $headshotPercent, $killsBlinded, $killsEnemyWeapons, $totalDamageDone, $bombPlanted, $bombDefused, $wins, $winsKnifeFight, $winPistolRrounds, $modeyEarned, $dominations, $revenges, $achivements, $personaname, $profileurl, $avatar, $avatarmedium, $avatarfull, $realname;
    
    public function __construct($steamid){
        if(preg_match("/STEAM/", $steamid)){
            $this->steamid = $steamid;
            list( , $m1, $m2) = explode(':', $this->steamid, 3);
            list($steam_cid, ) = explode('.', bcadd((((int) $m2 * 2) + $m1), '76561197960265728'), 2);
            $this->steamid64 = $steam_cid;
        }else{
            $this->steamid64 = $steamid;
        }
        $shit = @file_get_contents(sprintf("http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=%s&steamid=%s",constant("STEAM_WEB_API"),$this->steamid64));
	    $json = json_decode($shit);
        //return var_dump($json);
        if(isset($json)){
            foreach ($json->playerstats->stats as $key => $value) {
                if($value->name == "total_kills" ) {
                    $this->kills = $value->value;
                }
                if($value->name == "total_deaths" ) {
                    $this->death = $value->value;
                }
                if($value->name == "total_time_played" ) {
                    $this->totalTime = $value->value;
                }
                if($value->name == "total_planted_bombs" ) {
                    $this->bombPlanted = $value->value;
                }
                if($value->name == "total_defused_bombs" ) {
                    $this->bombDefused = $value->value;
                }
                if($value->name == "total_wins" ) {
                    $this->wins = $value->value;
                }
                if($value->name == "total_damage_done" ) {
                    $this->totalDamageDone = $value->value;
                }
                if($value->name == "total_money_earned" ) {
                    $this->modeyEarned = $value->value;
                }
                if($value->name == "total_dominations" ) {
                    $this->dominations = $value->value;
                }
                if($value->name == "total_revenges" ) {
                    $this->revenges = $value->value;
                }
                if($value->name == "total_shots_hit" ) {
                    $this->hit = $value->value;
                }
                if($value->name == "total_shots_fired" ) {
                    $this->shot = $value->value;
                }
                if($value->name == "total_kills_headshot" ) {
                    $this->inhead = $value->value;
                }
                if($value->name == "total_kills_enemy_blinded" ) {
                    $this->killsBlinded = $value->value;
                }
                if($value->name == "total_kills_knife_fight" ) {
                    $this->winsKnifeFight = $value->value;
                }
                if($value->name == "total_kills_enemy_weapon" ) {
                    $this->killsEnemyWeapons = $value->value;
                }    
                if($value->name == "total_wins_pistolround" ) {
                    $this->winPistolRrounds = $value->value;
                }
                $this->achivements = count($json->playerstats->achievements);
                if($this->death!=0){
                    $this->kd = round($this->kills / $this->death, 2);
                }else{
                    $this->kd = $this->kills;
                }
                if($this->shot!=0){
                    $this->acc = round($this->hit * 100 / $this->shot);
                }else{
                    $this->acc = 0;
                }
                $this->headshotPercent = round($this->inhead * 100 / $this->kills, 2);
            }
        }else{
            $this->totalTime = NULL;
            $this->headshotPercent = NULL;
        }
        
        
	$json2 = json_decode(file_get_contents(sprintf("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s", constant("STEAM_WEB_API"), $this->steamid64)));
        if(!empty($json2->response->players[0])){
            $this->personaname = $json2->response->players[0]->personaname;
            $this->profileurl = $json2->response->players[0]->profileurl;
            $this->avatar = $json2->response->players[0]->avatar;
            $this->avatarmedium = $json2->response->players[0]->avatarmedium;
            $this->avatarfull = $json2->response->players[0]->avatarfull;
            if(isset($json2->response->players[0]->realname)){
                $this->realname = $json2->response->players[0]->realname;
            }            
        } else {
            $this->personaname = "Top Secret";
            $this->profileurl = "#";
            $this->avatar = NULL;
            $this->avatarmedium = NULL;
            $this->avatarfull = "includes/img/noname.png";
            $this->realname = NULL;
        }
    }   
    
    function convert32to64(){
        list( , $m1, $m2) = explode(':', $this->steamid, 3);
	list($steam_cid, ) = explode('.', bcadd((((int) $m2 * 2) + $m1), '76561197960265728'), 2);
        return $steam_cid;
    }
}