<div class="row">
    <div class="content col-sm-12">
        <div class="profile_content">
            <div class="profile_content_inner">
                <div class="row">                    
                        
                    <?php for($i=0; $i < count($config['server']); $i++): ?>
                        <?php $sererInfo = getServerInfo($config['server'][$i]['gameHost'], $config['server'][$i]['gamePort']);?>
                        <div class="row col-sm-12 recent_game table-responsive">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>VAC</th>
                                        <th>НАЗВАНИЕ СЕРВЕРА</th>
                                        <th>ИГРОКИ</th>
                                        <th>ТЕКУЩАЯ КАРТА</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td><?=$i+1?></td>
                                        <td><?=($sererInfo[0]->Secure ? 'On':'Off')?></td>
                                        <td><?=$sererInfo[0]->HostName?></td>
                                        <?php
                                            if($config['BOT_INFO']){
                                                echo '<td>' . $sererInfo[0]->Players . '/' . $sererInfo[0]->MaxPlayers . '</td>';
                                            }else{
                                                echo '<td>' . ($sererInfo[0]->Players - $sererInfo[0]->Bots) . '/' . ($sererInfo[0]->MaxPlayers - $sererInfo[0]->Bots) . '</td>';
                                            }
                                        ?>
                                        <td><?=$sererInfo[0]->Map?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php unset($sererInfo); endfor; ?>

                </div>
            </div>
        </div>
    </div>
</div>