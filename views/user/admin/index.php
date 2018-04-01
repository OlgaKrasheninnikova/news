<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \yii\bootstrap\Modal;


/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \dektrium\user\models\UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Управление пользователями');
//$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?php
    Modal::begin([
        'header' => '<h2>Добавление пользователя</h2>',
        'toggleButton' => ['label' => 'Добавить пользователя', 'class' => 'btn btn-success'],
    ]);
    echo $this->render('create', [
        'user' => $newUserModel,
        'action' => Url::to('/user/admin/create')
    ]);
    Modal::end();
    ?>
</p>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>


<?php Pjax::begin() ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'layout'       => "{items}\n{pager}",
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:90px;'], # 90px is sufficient for 5-digit user ids
        ],
        'username',
        'email:email',
        [
            'attribute' => 'registration_ip',
            'value' => function ($model) {
                return $model->registration_ip == null
                    ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                    : $model->registration_ip;
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                if (extension_loaded('intl')) {
                    return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                } else {
                    return date('Y-m-d G:i:s', $model->created_at);
                }
            },
        ],

        [
          'attribute' => 'last_login_at',
          'value' => function ($model) {
            if (!$model->last_login_at || $model->last_login_at == 0) {
                return Yii::t('user', 'Never');
            } else if (extension_loaded('intl')) {
                return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->last_login_at]);
            } else {
                return date('Y-m-d G:i:s', $model->last_login_at);
            }
          },
        ],
        [
            'header' => Yii::t('user', 'Confirmation'),
            'value' => function ($model) {
                if ($model->isConfirmed) {
                    return '<div class="text-center">
                                <span class="text-success">' . Yii::t('user', 'Confirmed') . '</span>
                            </div>';
                } else {
                    return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                    ]);
                }
            },
            'format' => 'raw',
            'visible' => Yii::$app->getModule('user')->enableConfirmation,
        ],
        [
            'header' => Yii::t('user', 'Block status'),
            'value' => function ($model) {
                if ($model->isBlocked) {
                    return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                    ]);
                } else {
                    return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                    ]);
                }
            },
            'format' => 'raw',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{resend_password} {updateModal} {view} {delete}',
            'buttons' => [
                'resend_password' => function ($url, $model, $key) {
                    if (!$model->isAdmin) {
                        return '
                    <a data-method="POST" data-confirm="' . Yii::t('user', 'Вы уверены?') . '" href="' . Url::to(['resend-password', 'id' => $model->id]) . '">
                    <span class="glyphicon glyphicon-refresh" title="' . Yii::t('user', 'Сгенерировать и отправить пользователю новый пароль') . '">
                    </span> 
                    </a>';
                    }
                },
                'updateModal' => function ($url, $model, $key) {
                    ob_start();
                    Modal::begin([
                        'header' => '<h2>Редактирование пользователя</h2>',
                        'toggleButton' => ['label' => '', 'class' => 'glyphicon glyphicon-pencil'],
                    ]);
                    echo $this->render('_account', [
                        'user' => $model,
                        'action' => Url::to(['/user/admin/update', 'id' => $model->id])
                    ]);
                    Modal::end();
                    $content = ob_get_contents();
                    ob_end_clean();
                    return $content;

                },

            ]
        ],
    ],
]); ?>

<?php Pjax::end() ?>
