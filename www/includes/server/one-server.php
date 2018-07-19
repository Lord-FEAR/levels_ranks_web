<?php
    $server1 = new Server($config['server'][$cur_serv]['name'], $config['server'][$cur_serv]['host'], $config['server'][$cur_serv]['dbName'], $config['server'][$cur_serv]['login'], $config['server'][$cur_serv]['pass'], $config['server'][$cur_serv]['port']);
    $cur_page = 1;
    if (isset($_GET['page']) && $_GET['page'] > 0) 
    {
        $cur_page = $_GET['page'];
    }
    $start = ($cur_page - 1) * $config['RECORD_ON_PAGE'];
    $num_pages=ceil($server1->countRow()/$config['RECORD_ON_PAGE']); 
    
    //$data = lr_array($start);
    $data = $server1->lr_array($start, $config['RECORD_ON_PAGE']);
    
    //test
    //$cur_serv = 1;
?>
<script>
    ava.push([]);
    //console.log("log ava - ", ava);
</script>
<div class="row">
    <div class="content col-sm-12">
        <div class="profile_content">
            <div class="profile_content_inner">
                <div class="row">
                    <div class="row col-sm-12 recent_game table-responsive">
                        <h5><?=$server1->name;?></h5>
                        Всего игроков: <?=$server1->allPlayers();?>
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Игрок</th>
                                    <th>Очки</th>
                                    <th>Уровень</th>
                                    <th>Убийства</th>
                                    <th>Смертеи</th>
                                    <th>K/D</th>
                                    <th>Выстрелы</th>
                                    <th>Попадания</th>
                                    <th>Точность</th>
                                    <th>Последняя игра</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php for ($i = 0; $i < count($data); $i++): ?>
                                <script>
                                    playerId = "<?=steam32to64js($data[$i]["steam"])?>";
                                    if(playerId != 0){
                                        ava[0].push("<?=steam32to64($data[$i]["steam"])?>");
                                    }
                                </script>
                                    <tr>
                                        <td><?=$start+1?></td>
                                        <td><img id="<?=steam32to64($data[$i]["steam"])?>" src="<?=checkAva(strval(steam32to64($data[$i]["steam"])))?>" width="32px" height="32px"></td>
                                        <td><a href="player.php?sid=<?=$data[$i]["steam"]?>"><?=$data[$i]["name"]?></a></td>
                                        <td><?=$data[$i]["value"]?></td>
                                        <td><?=$data[$i]["rank"]?></td>
                                        <td><?=stristr($data[$i]["kills"], ';', TRUE)?></td>
                                        <td><?=$data[$i]["deaths"]?></td>
                                        <td><?php if($data[$i]["deaths"]!=0){echo round(stristr($data[$i]["kills"], ';', TRUE)/$data[$i]["deaths"],2);}else{echo 0;}?></td>
                                        <td><?=$data[$i]["shoots"]?></td>
                                        <td><?=stristr($data[$i]["hits"], ';', TRUE)?></td>
                                        <td><?php if($data[$i]["shoots"]!=0){echo round(stristr($data[$i]["hits"], ';', TRUE)*100/$data[$i]["shoots"]);}else{echo 0;} ?>%</td>
                                        <td><?=date("d.m.y", $data[$i]["lastconnect"])?></td>
                                    </tr> 
                                <?php $start++; endfor; ?>                 
                            </tbody>
                        </table>

                        <div class="pagination-nav">
                            <nav aria-label="nav-test">
                                <ul class="pagination pagination-sm">
                                    <li><a href="?serv=<?=$cur_serv?>&page=1" aria-label="Previous"><span aria-hidden="true">&laquo;&laquo;</span></a></li>
                                    <li><a href="?serv=<?=$cur_serv?>&page=<?=$cur_page-1?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                    
                                    <?php //Меньше 15 страниц
                                        if($num_pages<15){
                                            for ($a=1; $a < $num_pages; $a++){
                                                    if($a == $cur_page){
                                                        ?>
                                                            <li class="active"><a href="#!"><?=$a?></a></li>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <li class="waves-effect"><a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a></li>
                                                        <?php
                                                    }
                                                }
                                        }else{
                                            if($cur_page<=6){ //Первые страницы
                                                for ($a=1; $a < 12; $a++){
                                                    if($a == $cur_page){
                                                        ?>
                                                            <li class="active"><a href="#!"><?=$a?></a></li>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <li class="waves-effect"><a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                            <li class="disabled"><a href="#!">...</a></li>
                                                            <li class="waves-effect"><a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>"><?=$num_pages?></a></li>
                                                <?php
                                            }elseif ($cur_page >= ($num_pages - 6)){ //Последние страницы
                                                ?>
                                                            <li class="waves-effect"><a href="?page=1">1</a></li>
                                                            <li class="disabled"><a href="#!">...</a></li>
                                                    
                                                <?php
                                                for($a=($num_pages - 10); $a <= $num_pages; $a++){
                                                    if($a == $cur_page){
                                                        ?>
                                                            <li class="active"><a href="#!"><?=$a?></a></li>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <li class="waves-effect"><a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a></li>
                                                        <?php
                                                    }
                                                }
                                            }else{
                                                ?>
                                                            <li class="waves-effect"><a href="?serv=<?=$cur_serv?>&page=1">1</a></li>
                                                            <li class="disabled"><a href="#!">...</a></li>
                                                <?php
                                                    for($a=($cur_page - 5); $a < ($cur_page + 6); $a++){
                                                        if($a == $cur_page){
                                                            ?>
                                                                <li class="active"><a href="#!"><?=$a?></a></li>
                                                            <?php
                                                        }else{
                                                            ?>
                                                                <li class="waves-effect"><a href="?serv=<?=$cur_serv?>&page=<?=$a?>"><?=$a?></a></li>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                                            <li class="disabled"><a href="#!">...</a></li>
                                                            <li class="waves-effect"><a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>"><?=$num_pages?></a></li>
                                                <?php
                                            }
                                        }
                                    ?>
                                    <li><a href="?serv=<?=$cur_serv?>&page=<?=$cur_page+1?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                    <li><a href="?serv=<?=$cur_serv?>&page=<?=$num_pages?>" aria-label="Next"><span aria-hidden="true">&raquo;&raquo;</span></a></li>
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php unset($server1); unset($data); ?>