<?php
    use yii\helpers\Url;
?>
    <!--当前位置-->
    <div class="position_ab">
        <b>所在位置：</b>
        <a href="/">首页</a>>
        <a class="yellow">活动花絮</a>
    </div>
    <!--当前位置-->
    <!--左边重要导航盒子-->
    <div class="second">
    <div class="sidenav">
        <div class="side_m">
            <div class="side_h">
                <p>Activities</p>
                <img src="frontend/web/images/huaxu.png" />
            </div>
            <div class="line_01">&nbsp;</div>
            <ul class="side_nav_l">
                <li class="now"><a href="<?=Url::to(['interesting/index'])?>">活动资讯</a></li>
            </ul>
            <div class="line_02">&nbsp;</div>
        </div>
    </div>

    <!--左边重要导航盒子-->
    <!--右边主要内容-->
    <div class="s_main">
        <h1><?= $model->title;?></h1>
        <div class="new-content">
            <?= $model->content;?>
        </div>
        <div class="space_hx">&nbsp;</div>


        <!--新闻列表-->
    </div>
    <!--右边主要内容-->
</div>
</div>
<!--主体盒子-->
<div class="space_hx">&nbsp;</div>