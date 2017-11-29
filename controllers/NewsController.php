<?php

namespace app\controllers;

use app\models\Branch;
use app\models\Category;
use app\models\Country;
use app\models\User;
use Yii;
use app\models\News;
use app\models\NewsSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'logout'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->identity->status == User::STATUS_DELETED){
            throw new HttpException(403, 'Ваша учётная запись удалена!');
        }elseif(Yii::$app->user->identity->status == User::STATUS_NOT_ACTIVE){
            throw new HttpException(403, 'Ваша учётная запись не активирована!');
        }
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->user->identity->role == 2) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }else{
            return $this->redirect('/main/login');
        }
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->identity->role == 2){
            $model = new News();
            $model->scenario = 'create';
            $post = Yii::$app->request->post();
            if ($model->load($post)) {
                if ($model->save()){
                    $branches = $post['News']['bs'];
                    foreach (Branch::findAll($branches) as $branch) {
                        $model->link('branches', $branch);
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    vd($model->errors);
                }
            } else {
                $countries = Country::find()->select('id, cc_iso, concat_ws(" ", `cc_iso`,`country_name`) as country_name')->all();
                $geos = ArrayHelper::map($countries, 'id', 'country_name');
                $categoriesQuery = Category::find()->select('id, title')->all();
                $categories = ArrayHelper::map($categoriesQuery, 'id', 'title');
                $statuses = News::getStatuses();
                $priorities = News::getPriorities();
                return $this->render('create', [
                    'model' => $model,
                    'geos' => $geos,
                    'categories' => $categories,
                    'statuses' => $statuses,
                    'priorities' => $priorities,
                ]);
            }
        }else{
            return $this->redirect('/main/login');
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->identity->role == 2){
            $model = $this->findModel($id);
            $model->scenario = 'update';
            $post = Yii::$app->request->post();
            if ($model->load($post)) {
                $branches = $post['News']['bs'];
                if($model->save()){
                    $model->unlinkAll('branches', true);
                    foreach (Branch::findAll($branches) as $branch) {
                        $model->link('branches', $branch);
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    vd($model->errors);
                }
            } else {
                $countries = Country::find()->select('id, cc_iso, concat_ws(" ", `cc_iso`,`country_name`) as country_name')->all();
                $geos = ArrayHelper::map($countries, 'id', 'country_name');
                $categoriesQuery = Category::find()->select('id, title')->all();
                $categories = ArrayHelper::map($categoriesQuery, 'id', 'title');
                $statuses = News::getStatuses();
                $priorities = News::getPriorities();
                $model->bs = $model->branches;
                return $this->render('update', [
                    'model' => $model,
                    'geos' => $geos,
                    'categories' => $categories,
                    'statuses' => $statuses,
                    'priorities' => $priorities,
                ]);
            }
        }else{
            return $this->redirect('/main/login');
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->identity->role == 2){
            $model = $this->findModel($id);//->delete();
            $model->status = 0;
            if(!$model->save())vd($model->errors);
            return $this->redirect(['index']);
        }else{
            return $this->redirect('/main/login');
        }

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
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
