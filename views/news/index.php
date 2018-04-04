<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <div>Новостей на странице:
        <?php foreach ($itemsOnPage as $n => $num): ?>
            <a href="/?per-page=<?=$num?>"><?=$num?></a>
            <?= ($n<count($itemsOnPage)-1) ? ' / ' : '' ?>
        <?php endforeach ?>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{pager}<div class='container row'>{items}</div>",
        'itemOptions' => ['class' => 'col-md-4'],
        'itemView' => 'item',
        'pager' => [
                'hideOnSinglePage' => false
        ],
        'viewParams' => ['imgPath' => $imgPath]

    ]) ?>


</div>
