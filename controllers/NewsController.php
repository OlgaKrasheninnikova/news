<?php

namespace app\controllers;

use dektrium\user\models\Profile;
use dektrium\user\models\User;
use Yii;
use app\models\News;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{

    const ITEMS_ON_PAGE = [6, 12, 24];

    const ITEMS_ON_PAGE_DEFAULT = 12;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view'],
                'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['view'],
                            'roles' => ['@'],
                        ]
                    ],
            ]
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {

        if (\Yii::$app->user->can('createNews')) {
            echo 'ok';
        } else {
            echo 'no';
        }


        $numOnPage = $_GET['per-page'] ?? self::ITEMS_ON_PAGE_DEFAULT;
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->where(['is_active' => true]),
            'pagination' => [
                'pageSize' => $numOnPage,
            ],
        ]);

        return $this->render('index', [
            'itemsOnPage' => self::ITEMS_ON_PAGE,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
