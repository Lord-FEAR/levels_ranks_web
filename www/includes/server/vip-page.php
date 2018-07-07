<?php
    $server1 = new Vip($config['vip_database']['host'], $config['vip_database']['dbName'], $config['vip_database']['login'], $config['vip_database']['pass'], $config['vip_database']['port']);
    $cur_page = 1;
    if (isset($_GET['page']) && $_GET['page'] > 0) 
    {
        $cur_page = $_GET['page'];
    }
    $start = ($cur_page - 1) * $config['RECORD_ON_PAGE'];
    $num_pages=ceil($server1->countRow()/$config['RECORD_ON_PAGE']); 
    $data = $server1->getVips($start, $config['RECORD_ON_PAGE']);
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
                        Всего игроков: <?=$server1->countRow()?>
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Игрок</th>
                                    <th>Группа</th>
                                    <th>Срок</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php for ($i = 0; $i < count($data); $i++): ?>
                                <script>
                                    playerId = "<?=steam32to64js($data[$i]["auth"])?>";
                                    if(playerId != 0){
                                        ava[0].push("<?=steam32to64($data[$i]["auth"])?>");
                                    }
                                </script>
                                <tr>
                                    <td><?=$start+1?></td>
                                    <td><img id="<?=steam32to64($data[$i]["auth"])?>" src="<?=checkAva(strval(steam32to64($data[$i]["auth"])))?>" width="32px" height="32px"></td>
                                    <td><a href="player.php?sid=<?=$data[$i]["auth"]?>"><?=$data[$i]["name"]?></a></td>
                                    <td><?=$data[$i]['group']?></td>
                                    <td>
                                        <?php
                                            $remaining = $data[$i]['expires'] - time();
                                            $days_remaining = floor($remaining / 86400);
                                            $hours_remaining = floor(($remaining % 86400) / 3600);
                                            echo "Осталось $days_remaining дней $hours_remaining часов";
                                        ?>
                                    </td>
                                </tr> 
                                <?php $start++; endfor; ?>
                            </tbody>
                        </table>
                        
                        <?php if($num_pages > 1): ?>
                            <div class="pagination-nav">
                                <nav aria-label="nav-test">
                                    <ul class="pagination pagination-sm">
                                        <li><a href="?page=1" aria-label="Previous"><span aria-hidden="true">&laquo;&laquo;</span></a></li>
                                        <li><a href="?page=<?=$cur_page-1?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                        
                                        <?php //Меньше 15 страниц
                                            if($num_pages<15){
                                                for ($a=1; $a < $num_pages; $a++){
                                                        if($a == $cur_page){
                                                            ?>
                                                                <li class="active"><a href="#!"><?=$a?></a></li>
                                                            <?php
                                                        }else{
                                                            ?>
                                                                <li class="waves-effect"><a href="?page=<?=$a?>"><?=$a?></a></li>
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
                                                                <li class="waves-effect"><a href="?page=<?=$a?>"><?=$a?></a></li>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                                <li class="disabled"><a href="#!">...</a></li>
                                                                <li class="waves-effect"><a href="?page=<?=$num_pages?>"><?=$num_pages?></a></li>
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
                                                                <li class="waves-effect"><a href="?page=<?=$a?>"><?=$a?></a></li>
                                                            <?php
                                                        }
                                                    }
                                                }else{
                                                    ?>
                                                                <li class="waves-effect"><a href="?page=1">1</a></li>
                                                                <li class="disabled"><a href="#!">...</a></li>
                                                    <?php
                                                        for($a=($cur_page - 5); $a < ($cur_page + 6); $a++){
                                                            if($a == $cur_page){
                                                                ?>
                                                                    <li class="active"><a href="#!"><?=$a?></a></li>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                    <li class="waves-effect"><a href="?page=<?=$a?>"><?=$a?></a></li>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                                <li class="disabled"><a href="#!">...</a></li>
                                                                <li class="waves-effect"><a href="?page=<?=$num_pages?>"><?=$num_pages?></a></li>
                                                    <?php
                                                }
                                            }
                                        ?>
                                        <li><a href="?page=<?=$cur_page+1?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                        <li><a href="?page=<?=$num_pages?>" aria-label="Next"><span aria-hidden="true">&raquo;&raquo;</span></a></li>
                                    </ul>
                                </nav>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php unset($server1); unset($data); ?>