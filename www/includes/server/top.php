<div class="row">
    <div class="content col-sm-12">
        <div class="profile_content">
            <div class="profile_content_inner">
                <div class="row">                    
                        
                    <?php for($i=0; $i < count($config['server']); $i++): ?>
                        <?php $serer = new Server($config['server'][$i]['name'], $config['server'][$i]['host'], $config['server'][$i]['dbName'], $config['server'][$i]['login'], $config['server'][$i]['pass'], $config['server'][$i]['port']); ?>
                        <div class="row col-sm-12 recent_game table-responsive">
                            <script>
                                ava.push([]);
                                //console.log("log ava - ", ava);
                            </script>
                            <h4><?=$serer->name?></h4>
                            Всего игроков: <b><?=$serer->allPlayers()?></b>
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th></th>
                                        <th>Игрок</th>
                                        <th>Очки</th>
                                        <th>Уровень</th>
                                        <th>Убийств</th>
                                        <th>Смертей</th>
                                        <th>K/D</th>
                                        <th>Выстрелов</th>
                                        <th>Попаданий</th>
                                        <th>Точность</th>
                                        <th>Последняя игра</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $data = $serer->top();
                                        for ($index = 0; $index < count($data); $index++): ?>
                                        <script>
                                            page = "top";
                                            servID = "<?=$i?>";
                                            playerId = "<?=steam32to64js($data[$index]["steam"])?>";
                                            //console.log(playerId);
                                            if(playerId != 0){
                                                ava[servID].push("<?=steam32to64($data[$index]["steam"])?>");
                                            }
                                            
                                        </script>
                                        <tr>
                                            <td><?=$index+1?></td>
                                            <td><img id="<?=steam32to64($data[$index]["steam"])?>" src="<?=checkAva(strval(steam32to64($data[$index]["steam"])))?>" width="32px" height="32px"></td>
                                            <td><a href="player.php?sid=<?=$data[$index]["steam"]?>"><?=$data[$index]["name"]?></a></td>
                                            <td><?=$data[$index]["value"]?></td>
                                            <td><?=$data[$index]["rank"]?></td>
                                            <td><?=stristr($data[$index]["kills"], ';',true)?></td>
                                            <td><?=$data[$index]["deaths"]?></td>
                                            <td><?php if($data[$index]["deaths"]!=0) { echo round(stristr($data[$index]["kills"], ';',true)/$data[$index]["deaths"],2); }else{ echo 0; } ?></td>
                                            <td><?=$data[$index]["shoots"]?></td>
                                            <td><?=stristr($data[$index]["hits"], ';', TRUE)?></td>
                                            <td><?php if($data[$index]["shoots"]!=0) { echo round(stristr($data[$index]["hits"], ';', TRUE)*100/$data[$index]["shoots"]); }else{ echo 0; } ?>%</td>
                                            <td><?=date("d.m.y", $data[$index]["lastconnect"])?></td>
                                        </tr>
                                    <?php endfor; ?>                        
                                </tbody>
                            </table>
                        </div>
                    <?php unset($serer); endfor; ?>

                </div>
            </div>
        </div>
    </div>
</div>