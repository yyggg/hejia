<?php
    use yii\helpers\Url;
?>
    <!--当前位置-->
    <div class="position_ab">
        <b>所在位置：</b>
        <a href="/">首页</a> >
        <a class="yellow">
            家庭服务
        </a>
    </div>
    <!--当前位置-->
    <!--左边重要导航盒子-->
    <div class="second">
    <div class="sidenav">
        <div class="side_m">
            <div class="side_h">
                <p>Family Service</p>
                <img src="frontend/web/images/family_left.png"/>
            </div>
            <div class="line_01">&nbsp;</div>
            <?php if($nenus):;?>
            <ul class="side_nav_l">
                <?php foreach ($nenus as $v):?>
                <li <?php if($v->title == $model->title) echo 'class="now"';?>><a href="<?=Url::to(['family/view','id' => $v->id])?>"><?= $v->title;?></a></li>
                <?php endforeach;?>
            </ul>
            <div class="line_02">&nbsp;</div>
            <?php endif;?>
        </div>
    </div>

    <!--左边重要导航盒子-->
    <!--右边主要内容-->
    <div class="s_main">
        <?php if($model):;?>
        <h1><?= $model->title;?></h1>
        <div class="new-content">
            <?= $model->content;?>
        </div>
        <div class="space_hx">&nbsp;</div>
        <?php endif;?>
    </div>
    <!--右边主要内容-->
</div>
</div>
<!--主体盒子-->
<div class="space_hx">&nbsp;</div>