<?php

namespace app\controllers\admin;

use app\helpers\Config;
use app\models\NewsSearch;
use app\models\UploadForm;
use app\models\UserManager;
use dektrium\user\models\User;
use Yii;
use app\models\News;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\notifications\NotifyManager;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * NewsAdminController implements the CRUD actions for News model.
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
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    //редактировать (а значит и активировать) можно только собственные новости
                    [
                        'allow' => true,
                        'actions' => ['update', 'activation'],
                        'roles' => [UserManager::PERMISSION_UPDATE_NEWS],
                        'roleParams' => function() {
                            return ['news' => News::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    //удалять можно только собственные новости
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => [UserManager::PERMISSION_DELETE_NEWS],
                        'roleParams' => function() {
                            return ['news' => News::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create'],
                        'roles' => [UserManager::ROLE_ADMIN, UserManager::ROLE_MANAGER],
                    ]
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->layout = 'admin';
        return parent::beforeAction($action);
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel  = \Yii::createObject(NewsSearch::class);
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination->pageSize= 10;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'newItemModel' => new News(),
            'canCreate' => \Yii::$app->user->can(UserManager::PERMISSION_CREATE_NEWS),
            'smallImgPath' => Config::getInstance()->getSmallImgPath()
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
        $item = $this->findModel($id);
        $item->setAttribute('created_user_id', User::findIdentity($item->getAttribute('created_user_id'))->getAttribute('email'));
        if ($item->getAttribute('updated_user_id')) {
            $item->setAttribute('updated_user_id', User::findIdentity($item->getAttribute('updated_user_id'))->getAttribute('email'));
        }

        return $this->render('view', [
            'model' => $item,
            'imgPath' => Config::getInstance()->getBigImgPath()
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->setAttribute('created_user_id', Yii::$app->user->id);
            $model->setAttribute('created_at', date('Y-m-d H:i:s'));
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->save()) {
                if ($imageName = $model->upload()) {
                    $model->image = $imageName;
                    $model->save();
                }
                $notifyManager = new NotifyManager();
                $notifyManager->notifyAboutNewsItem($model);
                return $this->redirect('index');
            }
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $oldFileName = $model->image;
            $model->load(Yii::$app->request->post());
            $model->setAttribute('updated_user_id', Yii::$app->user->id);
            $model->setAttribute('updated_at', date('Y-m-d H:i:s'));

            $model->image = UploadedFile::getInstance($model, 'image');
            if ($imageName = $model->upload()) {
                $model->image = $imageName;
            } else {
                $model->image = $oldFileName;
            }

            $model->save();
            return $this->redirect('index');
        }
    }


    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $newsItem = $this->findModel($id);
        if ($newsItem) $newsItem->delete();

        return $this->redirect(['index']);
    }


    /**
     * Activate or deactivate news item
     *
     * @param $id - news item id
     * @param $value 0 or 1
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionActivation($id, $value) {
        if (!in_array($value, [0,1])) {
            return false;
        }
        $newsItem = $this->findModel($id);
        if (!$newsItem) {
            return false;
        }
        $newsItem->setAttribute('is_active', $value);
        if ($newsItem->save()) {
            return 'OK';
        }
        return 'ERROR';
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
