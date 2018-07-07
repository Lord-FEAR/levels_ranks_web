<div class="row">
    <div class="header col-sm-12">
        <div class="header-content">

            <span class="pull-right profile-nav">
                    <?php if(isset($_SESSION['steamid'])): ?>
                    <?php include_once('./includes/steamauth/userInfo.php'); ?>
                        <a class="user_name" href="./player.php"><?=$steamprofile['personaname']?></a>
                        <img class="user_avatar" src="<?=$steamprofile['avatar']?>">
                    <?php else:     ?>
                        <a class="user_name" href="./player.php?login" title="Авторизация">Войти</a>
                    <?php endif;   ?>
            </span>

                <a class="navA" href="./" title="На главную">Главная</a>

                <a class="navA" href="./top.php" title="Лучшие игроки">Top</a>

                <?php if(count($config['server'])>1):?>
                        <div class="dropdown">
                        <a href="#" class="dropbtn" title="Список серверов">Серверы</a>
                        <div class="dropdown-content">
                            <?php for($i = 0; $i < count($config['server']); $i++): ?>
                                <a href="./top.php?serv=<?=$i?>"><?=$config['server'][$i]['name']?></a>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif;?>

                <a class="navA" href="./vip.php" title="Список VIP">VIP</a>
                <a class="navA" href="./ban.php" title="Список банов">BANS</a>
                <a class="navA" href="./search.php" title="Поиск игроков">Поиск</a>

        </div>         
    </div>
</div>
<?php
    include_once("./includes/functions.php");
    dellCahe();
?>