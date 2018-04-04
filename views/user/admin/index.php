<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \yii\bootstrap\Modal;


/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \dektrium\user\models\UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Управление пользователями');
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
            'attribute' => 'notifications_email',
            'label' => 'Оповещать по email',
            'value' => function ($model) {
                return $model->notifications_email ? 'Да' : 'Нет';
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'notifications_push',
            'label' => "Оповещать в \n браузере (push)",
            'value' => function ($model) {
                return $model->notifications_push ? 'Да' : 'Нет';
            },
            'format' => 'html',
        ],
        [
            'label' => "Роль",
            'value' => function ($model) {
                if (Yii::$app->authManager->checkAccess($model->id,'admin')) {
                    return 'admin';
                } elseif (Yii::$app->authManager->checkAccess($model->id,'manager')) {
                    return 'manager';
                } else {
                    return 'user';
                }
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'confirmed_at',
            'value' => function ($model) {
                    return $model->confirmed_at ? Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->confirmed_at]) : '-';
            },
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
            'class' => 'yii\grid\ActionColumn',
            'template' => '{resend_password} {updateModal} {delete}',
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
