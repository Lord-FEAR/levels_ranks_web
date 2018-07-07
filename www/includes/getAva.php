<?php
    $config = include("configV2.php");

    if(isset($_POST['data']) && isset($_POST['page'])) {
        $page = $_POST['page'];
        if($page == "top"){
            $arrData = $_POST['data'];
            $returnData = [];
            
            for($i = 0; $i < count($arrData); $i++){
                $dataStr = "";
                foreach ($arrData[$i] as $key => $value) {
                    $dataStr .= $value.",";
                }

                $json2 = json_decode(file_get_contents(sprintf("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s", constant("STEAM_WEB_API"), $dataStr)));
                foreach ($json2->response->players as $key1 => $value1) {
                    $returnData[$i][$key1] = ["id" => $json2->response->players[$key1]->steamid, "url" => $json2->response->players[$key1]->avatar];
                    $cacheFile = "./../cache/".$json2->response->players[$key1]->steamid.".jpg.cache";
                    file_put_contents($cacheFile, file_get_contents($json2->response->players[$key1]->avatar));
                }
            }
            
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($returnData);
            exit;
        }

        if($page == "one"){
            $arrData = $_POST['data'];
            $dataStr = "";
            foreach ($arrData[0] as $key => $value) {
                $dataStr .= $value.",";
            }
            //var_dump($dataStr);
            $json2 = json_decode(file_get_contents(sprintf("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s", constant("STEAM_WEB_API"), $dataStr)));
            foreach ($json2->response->players as $key1 => $value1) {
                $returnData[0][$key1] = ["id" => $json2->response->players[$key1]->steamid, "url" => $json2->response->players[$key1]->avatar];
                $cacheFile = "./../cache/".$json2->response->players[$key1]->steamid.".jpg.cache";
                file_put_contents($cacheFile, file_get_contents($json2->response->players[$key1]->avatar));
            }
            
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($returnData);
        }
        
    }
    
?>