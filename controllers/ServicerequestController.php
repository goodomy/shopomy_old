<?php

namespace app\controllers;

use Yii;
use app\models\Servicerequest;
use app\models\Users;
use app\models\Contractortypes;
use app\models\Servicerequestsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ServicerequestController implements the CRUD actions for Servicerequest model.
 */
class ServicerequestController extends Controller
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
     * Lists all Servicerequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Servicerequestsearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servicerequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Servicerequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Servicerequest();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
        
            if($model->save())
            {
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
     * Updates an existing Servicerequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing Servicerequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
     * Finds the Servicerequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Servicerequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Servicerequest::findOne($id)) !== null) {
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

    public function actionExport()
    {

            $date=time();
            
            $query = Servicerequest::find();
            $query->select(["service_request_master.*", "u.full_name", "u.mobile_number","d.full_name"]);
            $query->leftjoin('user_master u', 'u.id=service_request_master.selected_contractor_id');
            $query->leftjoin('user_master d', 'd.id=service_request_master.user_id');

            $params=$_REQUEST;
            
            if(isset($params['Servicessearch']['booking_type']) && $params['Servicessearch']['booking_type']!=null){
                $booking_type=$params['Servicessearch']['booking_type'];
                $query->andFilterWhere(['like', 'service_request_master.booking_type', $booking_type]);
            }

            if(isset($params['Servicessearch']['type']) && $params['Servicessearch']['type']!=null){
                $type=$params['Servicessearch']['type'];
                $query->andFilterWhere(['like', 'service_request_master.service_type_id', $type]);
            }

            if(isset($params['Servicessearch']['date']) && $params['Servicessearch']['date']!=null)
            {
                $arr=explode('-',$params['Servicessearch']['date']);
               
                $date1 = $arr[0];
                $date1 = str_replace('/', '-', $date1);
                $start =  date('Y-m-d', strtotime($date1));
                
                $date2 = $arr[1];
                $date2 = str_replace('/', '-', $date2);
                $end =  date('Y-m-d', strtotime($date2));
                
               $query->andFilterWhere([
                  'AND',
                  ['>=','date(from_unixtime(service_request_master.i_date))',$start],
                  ['<=','date(from_unixtime(service_request_master.i_date))',$end],
                ]);
            }

            if(isset($params['Servicessearch']['verification']) && $params['Servicessearch']['verification']!=null){
                $status=$params['Servicessearch']['verification'];
                $query->andFilterWhere(['like', 'service_request_master.status', $status]);
            }

            if(isset($params['Servicessearch']['keyword']) && $params['Servicessearch']['keyword']!=null)
            {
                $keyword=$params['Servicessearch']['keyword'];
                $query->andFilterWhere([
                    'or',
                    ['like', 'u.full_name', $keyword],
                    ['like', 'd.full_name', $keyword],
                    //['like', 'u.mobile_number', $keyword],
                    ['like', 'location', $keyword],
                ]);
            }
    
            $data=$query->orderBy('service_request_master.service_request_master_id desc')->all();
            
            
            $filename='Services_'.time().'.csv';
            
            
            header('Content-Encoding: UTF-8');
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment;filename="'.$filename.'"');  //File name extension was wrong
            header('Pragma: public');
            header("Expires: 0");
            header('Content-Transfer-Encoding: binary');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);
            
            
            
            echo "NO,Service id,Contractor,Contact number,Service type,Booking type,Date,Location,Status \n";

            //$getRestuarantList=Yii::$app->frontcomponent->getBranch($restaurantid);


            //$getJobtypeList=Yii::$app->mycomponent->getJobtypelist();
            if(isset($data) && $data!=array())
            {
                $i=1;
                foreach($data as $model)
                {
                    
                    $service_id=$contractor=$user=$service_type=$booking_type=$date=$location=$status='-';
                    
                    if(isset($model->service_request_master_id) && $model->service_request_master_id!=null)
                    {
                        $service_id = $model->service_request_master_id;
                    }

                    if(isset($model->selected_contractor_id) && $model->selected_contractor_id!=null)
                    {
                        $contractor =  Users::findOne($model->selected_contractor_id)->full_name;
                    }

                    if(isset($model->user_id) && $model->user_id!= "")
                    {
                        $user =  Users::findOne($model->user_id)->full_name;
                    }

                    if(isset($model->service_type_id) && $model->service_type_id!= "")
                    {
                        $service_type =  Contractortypes::findOne($model->service_type_id)->abbrevation;;
                    }
                    
                    if(isset($model->booking_type) && $model->booking_type!=null)
                    {
                        if($model->booking_type=='O')
                        {
                            $booking_type = "On Demand";
                        }
                        else if($model->booking_type=='S')
                        {
                            $booking_type = "Scheduled";
                        }
                    }


                    if(isset($model->i_date) && $model->i_date!=null)
                    {
                        $date =  date('d-m-Y', $model->i_date);
                    }

                    if(isset($model->location) && $model->location!=null)
                    {
                        $location =  $model->location;
                    }

                    if(isset($model->status) && $model->status!=null)
                    {
                        $service_status_list = Yii::$app->mycomponent->getServicestatuslist();
                        $status = $service_status_list[$model->status];
                    }


                    echo $i.",".$service_id.",".$user.",".$contractor.','.$service_type.','.$booking_type.','.$date.','.$location.','.$status;
                    echo "\n";
                $i++;
                }
            }
            exit();
        
    }
}
