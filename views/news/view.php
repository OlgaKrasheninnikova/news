<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'description',
                [                      // the owner name of the model
                    'label' => 'Изображение',
                    'value' => "<img src='{$imgPath}{$model->image}' />",
                    'format' => 'raw'
                ],
                'text:ntext',
                'date'
            ],
            //'viewParams' => ['imgPath' => $imgPath]
        ]) ?>
    </div>

</div>
