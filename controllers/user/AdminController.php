<?php
namespace app\controllers\user;

use dektrium\user\controllers\AdminController as BaseAdminController;
use dektrium\user\models\User;
use dektrium\user\models\UserSearch;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\Url;


/**
 * Переопределяем частично CRUD контроллер пользователей
 *
 * Class AdminController
 * @package app\controllers\user
 */
class AdminController extends BaseAdminController
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete'          => ['post'],
                    'confirm'         => ['post'],
                    'resend-password' => ['post'],
                    'block'           => ['post'],
                    'switch'          => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['switch'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Переопределяем для добавления виджетов модальных окон
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel  = \Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'newUserModel' => new User()
        ]);
    }



    /**
     * Переопределяем для работы в модальном окне
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var \app\models\User $user */
        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'create',
        ]);
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);

        if ($user->load(\Yii::$app->request->post()) && $user->create()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been created'));
            $this->trigger(self::EVENT_AFTER_CREATE, $event);
            return $this->redirect('index');
        }
    }


    /**
     * Переопределяем для работы в модальном окне
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $user->scenario = 'update';
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_UPDATE, $event);
        if ($user->load(\Yii::$app->request->post()) && $user->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Account details have been updated'));
            $this->trigger(self::EVENT_AFTER_UPDATE, $event);
            return $this->redirect('index');
        }
    }
}