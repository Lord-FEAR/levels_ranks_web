<?php
/**
 * @author LordF
 */

class PlayerSteamSatas extends Player {
	public $avaUrl, $status, $steamid, $steamid64, $personastate;
	public function __construct($steamid){
		if(preg_match("/STEAM/", $steamid)){
            $this->steamid = $steamid;
			list( , $m1, $m2) = explode(':', $this->steamid, 3);
			list($steam_cid, ) = explode('.', bcadd((((int) $m2 * 2) + $m1), '76561197960265728'), 2);
			$this->steamid64 = $steam_cid;
        }else{
            $this->steamid64 = $steamid;
        }
		$json2 = json_decode(file_get_contents(sprintf("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s", constant("STEAM_WEB_API"), $this->steamid64)));
		if(!empty($json2->response->players[0])){
            $this->avaUrl = $json2->response->players[0]->avatarfull;
			$this->personastate = $json2->response->players[0]->personastate;
          
        } else {
            $this->avaUrl = "includes/img/noname.png";
        }
	}
}