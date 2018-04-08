<?php

namespace app\controllers;

use Yii;
use app\models\Notification;
use app\models\Notificationsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Users;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends Controller
{
    public $layout="admin";
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','index','update','change','view','page','active'],
                'rules' => [
                    [
                        'actions' => ['create','index','update','change','view','page','active'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Notification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Notificationsearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notification model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Notification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Notification();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->datetime = time();
            $model->type = 'admin';
        
            if($model->save(false))
            {
                $message = $model->message;
                $users = Users::find()->where(['is_active'=>'Y','is_deleted'=>'N'])->andwhere('device_id IS NOT NULL')->all();
                if($users != array())
                {
                    $badge = 1;
                    $type = $model->type;
                    $body = array();
                    $body['aps'] = array('alert' =>  $message);
                    $body['aps']['type'] = $type;
                    //$body['aps']['badge'] = $badge;
                    $body['aps']['sound'] = 'default';
                    
                    $body1 = array();
                    $body1['data'] = array('text' =>  $message);
                    $body1['data']['type'] = $type;
                    
                    $ij = $ijk = 0;
                    $device_token_iphone = $device_token_android = array();
                    foreach($users as $user)
                    {
                        if($user->device_type == 'I' && $user->device_id != '' && $user->device_id != null)// && $user->notification == 'Y'
                        {
                            if(isset($device_token_iphone[$ijk]) && count($device_token_iphone[$ijk]) == 500)
                            $ijk++;
                            $device_token_iphone[$ijk][$user->id] = $user->device_id;
                        }
                        if($user->device_type == 'A' && $user->device_id != '' && $user->device_id != null)// && $user->notification == 'Y'
                        {
                            if(isset($device_token_android[$ij]) && count($device_token_android[$ij]) == 500)
                            $ij++;
                            $device_token_android[$ij][$user->id] = $user->device_id;
                        }
                    }
                    
                    if($device_token_android != array())
                    Yii::$app->mycomponent->pushnotification_android_array($device_token_android,$body1);
                    
                    if($device_token_iphone != array())
                    Yii::$app->mycomponent->pushnotification_iphone_array($device_token_iphone,$body);
                }
                
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Notification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            if($model->save()){
                return $this->redirect(['index']);
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                ]); 
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Notification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(isset($_REQUEST['id']))
        {
            $model = $this->findModel($_REQUEST['id']);
            $model->is_deleted = "Y";
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->save(false);
        }
        //$this->findModel($id)->delete();

        //return $this->redirect(['index']);
    }

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPage()
    {
        if(isset($_REQUEST['size']) && $_REQUEST['size']!=null)
        {
            \Yii::$app->session->set('user.size',$_REQUEST['size']);
        }
    }
}
