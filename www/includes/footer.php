<div class="row">
    <div class="col-sm-12">
        <div class="navbar-fixed-bottom row-fluid">
            <div class="navbar-inner">
                <div class="container footer">
                    <div class="col-sm-8">
                        <h5>Levels Ranks WEB <?=$config['LR_VERS'];?></h5>
                        <p>Levels Ranks - это отличный плагин, который имеет в своем арсенале поддержку несколько типов статистики на вкус и цвет каждого админа сервера.</p>
                        <p>2017 - <?=date('Y');?> © <a href="<?php if(isset($config['MAIN_SITE'])){ echo $config['MAIN_SITE'];}else{ echo $config['MAIN_PAGE'];}?>"><?=$config['NAME_PROJ']?></a></p>
                    </div>
                    <div class="col-sm-4 right">
                        <h5 class="white-text">Ссылки</h5>
                            <?php // Сайтам с уделёнными/измененными ссылками на темы и копирайтом в поддержке отказываю ?>
                            <p><a href="https://hlmod.ru/forums/levels-ranks.108/">Levels Ranks Plugins</a></p>
                            <p><a href="https://github.com/Lord-FEAR/levels_ranks_web">Levels Ranks Web</a></p>
                        <p>Developed by <a href="https://lordfear.ru/">Lord FEAR</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>