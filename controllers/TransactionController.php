<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\Users;
use app\models\TransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Servicerequest;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
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
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transaction model.
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
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transaction();

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
     * Updates an existing Transaction model.
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
     * Deletes an existing Transaction model.
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
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
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
            $query = Transaction::find();

            $params=$_REQUEST;
            $data=$query->orderBy('id desc')->all();
            $filename='Transaction_'.time().'.csv';

            header('Content-Encoding: UTF-8');
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment;filename="'.$filename.'"');  //File name extension was wrong
            header('Pragma: public');
            header("Expires: 0");
            header('Content-Transfer-Encoding: binary');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);
            echo "NO,Id,Pyee,Payor,Transaction Date & Time,Description,Amount,Status \n";
            if(isset($data) && $data!=array())
            {
                $i=1;
                foreach($data as $model)
                {
                    //$service_type_title=$abbrevation=$requirements=$status='-';

                    $id=$payee_id=$payor_id=$transaction_date=$description=$amount=$status='-';
                    $id = $model->id;
                    if(isset($model->payee_id) && $model->payee_id != ""){
                      $payee_id = Users::findOne($model->payee_id)->full_name;
                    }
                    if(isset($model->payor_id) && $model->payor_id != ""){
                      $payor_id = Users::findOne($model->payor_id)->full_name;
                    }
                    if(isset($model->transaction_date) && $model->transaction_date!=null)
                    {
                        $transaction_date =  date('d-m-Y h:i A', $model->transaction_date);
                    }
                    if(isset($model->description) && $model->description != ""){
                      if($model->description == "U"){
                        $description='Payment from User';
                      }else{
                        $description='Payment to Contractors';
                      }
                    }
                    if(isset($model->amount) && $model->amount != ""){
                      $string = "USD";
                      $amount = $string." ".$model->amount;
                    }
                    if(isset($model->status) && $model->status!="")
                    {
                        if($model->status=='S')
                        {
                            $status='Successful';
                        }
                        else if($model->status=='N')
                        {
                            $status='Failed';
                        }
                    }
                    //$payee_id=$payor_id=$transaction_date=$description=$amount=$status=
                    echo $i.",".$id.",".$payee_id.",".$payor_id.",".$transaction_date.",".$description.",".$amount.",".$status;
                    echo "\n";
                    $i++;
                }
            }
            exit();
    }
    public function actionView_export($id){
      $model = $this->findModel($id);
      $date=time();
      $query = Transaction::find($id);
      $params=$_REQUEST;

      $data=$query->orderBy('id desc')->all();
      $filename='TransactionDetail_'.time().'.csv';

      header('Content-Encoding: UTF-8');
      header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
      header('Content-Disposition: attachment;filename="'.$filename.'"');  //File name extension was wrong
      header('Pragma: public');
      header("Expires: 0");
      header('Content-Transfer-Encoding: binary');
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private",false);
      echo "NO,Transaction Number,Pyee,Payor,Description,Amount,Service ID \n";
      if(isset($data) && $data!=array())
      {
        $i=1;
        $id=$payee_id=$payor_id=$description=$amount=$serviceid='-';

        if(isset($model->payee_id) && $model->payee_id != ""){
          $payee_id = Users::findOne($model->payee_id)->full_name;
        }
        if(isset($model->payor_id) && $model->payor_id != ""){
          $payor_id = Users::findOne($model->payor_id)->full_name;
        }
        if(isset($model->description) && $model->description != ""){
          if($model->description == "U"){
            $description='Payment from User';
          }else{
            $description='Payment to Contractors';
          }
        }
        if(isset($model->amount) && $model->amount != ""){
          $string = "USD";
          $amount = $string." ".$model->amount;
        }

        if(isset($model->service_request_master_id) && $model->service_request_master_id != ""){
          $id = Servicerequest::findOne($model->service_request_master_id)->service_type_id;
          $serviceid = Yii::$app->mycomponent->getServiceTypeTitle($id)->service_type_title;

        }
        echo $i.",".$id.",".$payee_id.",".$payor_id.",".$description.",".$amount.",".$serviceid;
        echo "\n";
        $i++;

      }
      exit();
    }
}
