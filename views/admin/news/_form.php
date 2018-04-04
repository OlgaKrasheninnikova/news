<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \dosamigos\datepicker\DatePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['action' => $action, 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Заголовок') ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true])->label('Описание') ?>

    <?php // $form->field($model, 'image')->fileInput()->label('Изображение') ?>

    <?= $form->field($model, 'image')->widget(FileInput::class, [
    'options' => ['accept' => 'image/*', 'id' => 'image-'.$model->id],
    ]); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6])->label('Полный текст новости') ?>

    <?= $form->field($model, 'date')->widget(
        DatePicker::class, [
        // inline too, not bad
        //'inline' => true,
        // modify template for custom rendering
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'options' => ['id'=>'datepicker-'.$model->id],
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->label('Дата');?>


    <?= $form->field($model, 'is_active')->checkbox() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
