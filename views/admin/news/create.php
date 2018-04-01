<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\News */


$this->title = 'Create News';
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
Modal::begin([
'header' => '<h2>'.Html::encode($this->title).'</h2>',
'toggleButton' => ['label' => 'click me'],
]);

    echo $this->render('_form', [
        'model' => $model,
    ]);

Modal::end();
?>