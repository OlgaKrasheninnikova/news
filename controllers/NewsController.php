<?php

namespace app\controllers;

use Yii;
use app\models\News;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\Config;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{

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
        $numOnPage = Yii::$app->request->get('per-page') ?? Config::getInstance()->getParam('itemsOnPageDefault', 'news');
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->where(['is_active' => true]),
            'pagination' => [
                'pageSize' => $numOnPage,
            ],
        ]);

        return $this->render('index', [
            'itemsOnPage' => Config::getInstance()->getParam('itemsOnPage', 'news'),
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
