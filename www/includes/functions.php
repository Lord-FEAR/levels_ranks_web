<?php
    function steam32to64($steamId){
        list( , $m1, $m2) = explode(':', $steamId, 3);
        list($steam_cid, ) = explode('.', bcadd((((int) $m2 * 2) + $m1), '76561197960265728'), 2);
        return $steam_cid;
    }

    function steam32to64js($steamId){
        list( , $m1, $m2) = explode(':', $steamId, 3);
        list($steam_cid, ) = explode('.', bcadd((((int) $m2 * 2) + $m1), '76561197960265728'), 2);
        $cacheTime = (24*60*60);
        $cacheFile = "./cache/$steam_cid.jpg.cache";
        if (file_exists($cacheFile)) {
            if ((time() - $cacheTime) < filemtime($cacheFile)) {
                return 0;
            }
        }            
        
        return $steam_cid;
    }

    function SteamID3SteamID64($steamid3) {
        $args = explode(":", $steamid3);
        $accountid = substr($args[2], 0, -1);
    
        if (($accountid % 2) == 0) {
            $Y = 0;
            $Z = ($accountid / 2);
        } else {
            $Y = 1;
            $Z = (($accountid - 1) / 2);
        }
    
        return "7656119".(($Z * 2) + (7960265728 + $Y));
    }

    function checkAva($id){
        $cacheTime = (24*60*60);
        $cacheFile = "./cache/$id.jpg.cache";
        if (file_exists($cacheFile)) {
            if ((time() - $cacheTime) < filemtime($cacheFile)) {
                return "./cache/$id.jpg.cache";
                header('content-type: image/jpg');
                echo file_get_contents($cacheFile);
                exit;
            }            
        }else{
            return "./includes/img/noname40.png";
            header('content-type: image/png');
            echo file_get_contents("./img/noname40.png");
            exit;
        }
    }

    function dellCahe(){
        $cacheTime = (24*60*60);
        $data = scandir("./cache");
        foreach ($data as $value) {
            if($value == "." || $value == ".."){
                continue;
            }
            $cacheFile = "./cache/$value";
            if(file_exists($cacheFile)){
                if ((time() - $cacheTime) > filemtime($cacheFile)) {
                    unlink($cacheFile);
                }
            }
        }
    }

    
?>