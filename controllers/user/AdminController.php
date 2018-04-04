<?php
namespace app\controllers\user;

use app\models\UserManager;
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
                'class' => VerbFilter::class,
                'actions' => [
                    'delete'          => ['post'],
                    'confirm'         => ['post'],
                    'resend-password' => ['post'],
                    'block'           => ['post'],
                    'switch'          => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['switch'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => [UserManager::ROLE_ADMIN],
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
        $searchModel  = \Yii::createObject(UserSearch::class);
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
            'class'    => User::class,
            'scenario' => 'create',
        ]);
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);
        echo 'mm' . get_class($user);
        if ($user->load(\Yii::$app->request->post()) && $user->create()) {
            echo 'ok!';
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