<div class="row hidden-sm hidden-xs">
    <div class="header col-sm-12">
        <div class="header-content">

            <span class="pull-right profile-nav">
                    <?php if(isset($_SESSION['steamid'])): ?>
                    <?php include_once('./includes/steamauth/userInfo.php'); ?>
                        <a class="user_name" href="./player.php"><?=$steamprofile['personaname']?></a>
                        <img class="user_avatar" src="<?=$steamprofile['avatar']?>">
                    <?php else:     ?>
                        <a class="user_name" href="./player.php?login" title="Авторизация">Войти Steam</a>
                    <?php endif;   ?>
            </span>

            <nav>
                <ul class="topmenu">
                <li><a class="navA" href="./">ГЛАВНАЯ</a></li>
                <?php if($config['menuTop']) echo '<li><a class="navA" href="./top.php" title="Лучшие игроки">TOP</a></li>'?>

                <li><a class="navA" href="#" class="down">СЕРВЕРЫ</a>
                    <ul class="submenu">
                        <?php for($i = 0; $i < count($config['server']); $i++): ?>
                            <li><a href="./top.php?serv=<?=$i?>"><?=$config['server'][$i]['name']?></a></li>
                        <?php endfor; ?>
                    </ul>
                </li>

                <?php if($config['vip_database']['status']) echo '<li><a class="navA" href="./vip.php" title="Список VIP">VIP</a></li>'?>
                <?php if($config['ban_database']['status']) echo '<li><a class="navA" href="./ban.php" title="Список банов">BANS</a></li>'?>
                <li><a class="navA" href="./search.php" title="SEARCH">ПОИСК</a></li>
                </ul>
            </nav>
            
        </div>
    </div>
</div>

<nav class="navbar navbar-default navbar-static-top navbar-fixed-top navbar-inverse hidden-lg hidden-md">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?=$config['NAME_PROJ']?></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="./">ГЛАВНАЯ</a></li>
                <?php if($config['menuTop']) echo '<li><a href="./top.php" title="Лучшие игроки">TOP</a></li>'?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">СЕРВЕРЫ<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php for($i = 0; $i < count($config['server']); $i++): ?>
                            <li><a href="./top.php?serv=<?=$i?>"><?=$config['server'][$i]['name']?></a></li>
                        <?php endfor; ?>
                    </ul>
                </li>
                <?php if($config['vip_database']['status']) echo '<li><a class="navA" href="./vip.php" title="Список VIP">VIP</a></li>'?>
                <?php if($config['ban_database']['status']) echo '<li><a class="navA" href="./ban.php" title="Список банов">BANS</a></li>'?>
                <li><a class="navA" href="./search.php" title="SEARCH">ПОИСК</a></li>

                <?php if(isset($_SESSION['steamid'])): ?>
                <?php include_once('./includes/steamauth/userInfo.php'); ?>
                    <li><a class="user_name" href="./player.php"><?=$steamprofile['personaname']?></a>
                    <img class="user_avatar" src="<?=$steamprofile['avatar']?>"></li>
                <?php else:     ?>
                    <li><a class="user_name" href="./player.php?login" title="Авторизация">Steam login</a></li>
                <?php endif;   ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

<?php
    include_once("./includes/functions.php");
    dellCahe();
?>
<style>
nav {
  text-align: center;
  padding: 0px 0 0;
}
nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
}
nav a {
  text-decoration: none;
  display: block;
  color: #222;
}
.topmenu > li {
  display: inline-block;
  position: relative;
  font-size: 12px;
}
.topmenu > li > a {
  position: relative;
  padding: 10px 15px;
  font-size: 1.5em;
  line-height: 1;
  letter-spacing: 3px;
}
.topmenu > li > a:before {
  content: "";
  position: absolute;
  z-index: 5;
  left: 50%;
  top: 100%;
  width: 10px;
  height: 10px;
  background: white;
  border-radius: 50%;
  transform: translate(-50%, 20px);
  opacity: 0;
  transition: .3s;
}
.topmenu li:hover a:before {
  transform: translate(-50%, 0);
  opacity: 1;
}
.submenu {
  position: absolute;
  z-index: 4;
  left: 50%;
  top: 100%;
  width: 150px;
  padding: 15px 0 15px;
  margin-top: 5px;
  background: black;
  border-radius: 5px;
  box-shadow: 0 0 30px rgba(0,0,0,.2);
  box-sizing: border-box;
  visibility: hidden;
  opacity: 0;
  transform: translate(-50%, 20px);
  transition: .3s;
}
.topmenu > li:hover .submenu {
  visibility: visible;
  opacity: 1;
  transform: translate(-50%, 0);
}
.submenu a {
  font-family: 'Libre Baskerville', serif;
  font-size: 15px;
  letter-spacing: 1px;
  padding: 5px 10px;
  transition: .3s linear;
}
.submenu a:hover {background: #e8e8e8;}
</style>