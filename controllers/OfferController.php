<?php

namespace app\controllers;

use app\models\Branch;
use app\models\Category;
use app\models\Country;
use app\models\News;
use app\models\User;
use Yii;
use app\models\Offer;
use app\models\OfferSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OfferController implements the CRUD actions for Offer model.
 */
class OfferController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
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
     * Lists all Offer models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->identity->status == User::STATUS_DELETED){
            throw new HttpException(403, 'Ваша учётная запись удалена!');
        }elseif(Yii::$app->user->identity->status == User::STATUS_NOT_ACTIVE){
            throw new HttpException(403, 'Ваша учётная запись не активирована!');
        }
        $searchModel = new OfferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Offer model.
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
     * Creates a new Offer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->identity->role == 2) {
            $model = new Offer();
            $model->scenario = 'create';
            $post = Yii::$app->request->post();
            if ($model->load($post)) {
                if ($model->save()){
                    $branches = $post['Offer']['bs'];
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
     * Updates an existing Offer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->identity->role == 2) {
            $model = $this->findModel($id);
            $model->scenario = 'update';
            $post = Yii::$app->request->post();
            if ($model->load($post)) {
                $branches = $post['Offer']['bs'];
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
     * Deletes an existing Offer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->identity->role == 2) {
            $model = $this->findModel($id);
            $model->status = 0;
            $model->save();
            return $this->redirect(['index']);
        }else{
            return $this->redirect('/main/login');
        }
    }

    /**
     * Finds the Offer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Offer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Offer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
