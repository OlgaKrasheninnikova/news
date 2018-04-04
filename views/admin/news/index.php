<?php

use yii\helpers\Html;
use \yii\helpers\Url;
use \yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

$this->registerJsFile('/js/admin/news/activation.js',  ['depends' =>'yii\web\JqueryAsset']);
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>


        <p>
            <?php
            if ($canCreate) {
                Modal::begin([
                    'header' => '<h2>Добавление новости</h2>',
                    'toggleButton' => ['label' => 'Добавить новость', 'class' => 'btn btn-success'],
                ]);
                echo $this->render('_form', [
                    'model' => $newItemModel,
                    'action' => Url::to('/admin/news/create')
                ]);
                Modal::end();
            }
            ?>
        </p>

<?php Pjax::begin() ?>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
                'id',
                'name',
                'description',
                [
                    'label' => 'Изображение',
                    'attribute' => 'image',
                    'format' => 'raw',
                    'value' => function ($model) use ($smallImgPath) {
                         return "<img alt='Изображение' src='/uploads/news/small/{$model->image}' />";
                    }
                ],
                'date',
                [
                    'label' => 'Активна',
                    'attribute' => 'is_active',
                    'format' => 'raw',
                    'filter' => [0 => 'Нет', 1 => 'Да'],
                    'value' => function ($model) {
                        $label = $model->is_active ? 'Да' : 'Нет';
                        if (Yii::$app->user->can('updateNews', ['news' => $model])) {
                            $value = $model->is_active ? 0 : 1;
                            return Html::a($label, "javascript:void(0)", ['class' => 'activation', 'rel' => $value, 'data' => $model->id ]);
                        } else {
                            return $label;
                        }
                    }
                ],
                [
                        'class'=>'yii\grid\ActionColumn',
                        'header'=>'Действия',
                        'template' => '{updateModal} {deleteCustom} {view}',
                        'buttons' => [
                            'updateModal' => function ($url, $model, $key) {
                                if (!Yii::$app->user->can('updateNews', ['news' => $model])) {
                                    return '';
                                }
                                ob_start();
                                Modal::begin([
                                    'header' => '<h2>Редактирование новости</h2>',
                                    'toggleButton' => ['label' => '', 'class' => 'glyphicon glyphicon-pencil'],
                                ]);
                                echo $this->render('_form', [
                                    'model' => $model,
                                    'action' => Url::to(['/admin/news/update', 'id' => $model->id])
                                ]);
                                Modal::end();
                                $content = ob_get_contents();
                                ob_end_clean();
                                return $content;

                                },
                            'deleteCustom' => function ($url, $model, $key)  {
                                if (!Yii::$app->user->can('deleteNews', ['news' => $model])) {
                                    return '';
                                }
                                return Html::a('<span class=\'glyphicon glyphicon-trash\'></span>',
                                    Url::to(['/admin/news/delete', 'id' => $model->id]),
                                    ['title' => 'Delete', 'data-confirm' => 'Are you sure you want to delete this item?', 'data-method' => 'post']
                                );
                            },
                        ]

                ],
        ]
    ]) ?>
</div>
<?php Pjax::end() ?>
