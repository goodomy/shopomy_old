<?php

namespace app\controllers;

use Yii;
use app\models\Contractortypes;
use app\models\ContractortypesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\ContractorTypeRequirements;
use yii\helpers\ArrayHelper;

/**
 * ContractortypesController implements the CRUD actions for Contractortypes model.
 */
class ContractortypesController extends Controller
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
     * Lists all Contractortypes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContractortypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contractortypes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $test = $this->findModel($id);
        $requirements_name_list = "";
        //print_r($test);die;
        if(isset($test['requirements']) && ($test['requirements'] != ""))
        {
            $requirements_array = explode(',',$test['requirements']);
            $requirements_deatils = ContractorTypeRequirements::find()->where(["is_deleted"=>"N","id"=>$requirements_array])->all();
            $requirements_names = ArrayHelper::getColumn($requirements_deatils,"name");
            $requirements_name_list = implode(' , ',$requirements_names);
            //print_r($requirements_name_list);die;
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'requirements_name_list' => $requirements_name_list,
        ]);
    }

    /**
     * Creates a new Contractortypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Contractortypes();

        $requirements_model = new ContractorTypeRequirements();
        $requirements_list = ContractorTypeRequirements::find()->where(['is_deleted' => 'N','is_active' => 'Y'])->all();

        if ($model->load(Yii::$app->request->post()))
        {
            $post = Yii::$app->request->post();

            if(isset($post['ContractorTypeRequirements']['name']) &&(!empty($post['ContractorTypeRequirements']['name'])))
            {
             $post_requirement_id_list = $post['ContractorTypeRequirements']['name'];
             $model->requirements = implode(',',$post_requirement_id_list);

            }

            if($model->save(false))
            {
              return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'requirements_list' => $requirements_list,
                    'requirements_model' => $requirements_model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'requirements_list' => $requirements_list,
                'requirements_model' => $requirements_model,
            ]);
        }
    }

    /**
     * Updates an existing Contractortypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // $model->requirements=explode(',',$model->requirements);

        //$requirements_list = Contractortypes::find()->where(['is_active' => 'Y'])->all();
        $requirements_model = new ContractorTypeRequirements();
        $requirements_list = ContractorTypeRequirements::find()->where(['is_deleted' => 'N','is_active' => 'Y'])->all();


        if ($model->load(Yii::$app->request->post()))
        {
            //$model->requirements=implode(',',$model->requirements);
            $post = Yii::$app->request->post();
            if(isset($post['ContractorTypeRequirements']['name']) &&(!empty($post['ContractorTypeRequirements']['name'])))
            {
             $post_requirement_id_list = $post['ContractorTypeRequirements']['name'];
             $model->requirements = implode(',',$post_requirement_id_list);
             //print_r($model->requirements);die;

            }else{

                 $model->requirements = "";
            }
            if($model->save(false)){
                return $this->redirect(['index']);
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                    'requirements_list' => $requirements_list,
                    'requirements_model' => $requirements_model,
                ]);

            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'requirements_list' => $requirements_list,
                'requirements_model' => $requirements_model,
            ]);
        }
    }

    /**
     * Deletes an existing Contractortypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // if(isset($_REQUEST['id']))
        // {
        //     $model = $this->findModel($_REQUEST['id']);
        //     $model->is_deleted = "Y";
        //     $model->u_by = Yii::$app->user->id;
        //     $model->u_date = time();
        //     $model->save(false);
        // }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contractortypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contractortypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contractortypes::findOne($id)) !== null) {
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
    public function actionActive()
   {
       if(isset($_REQUEST['id']))
       {
           $model = $this->findModel($_REQUEST['id']);
           $model->is_active = $_REQUEST['val'];
           $model->save(false);
       }
   }
   public function actionExport()
   {
           $date=time();
           $query = Contractortypes::find();


           $params=$_REQUEST;



           if(isset($params['ContractortypesSearch']['status']) && $params['ContractortypesSearch']['status']!=null){
               $status=$params['ContractortypesSearch']['status'];
               $query->andFilterWhere(['like', 'is_active', $status]);
           }
           $data=$query->orderBy('service_type_id desc')->all();
           $filename='ContractorsTypes_'.time().'.csv';

           header('Content-Encoding: UTF-8');
           header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
           header('Content-Disposition: attachment;filename="'.$filename.'"');  //File name extension was wrong
           header('Pragma: public');
           header("Expires: 0");
           header('Content-Transfer-Encoding: binary');
           header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
           header("Cache-Control: private",false);
           echo "NO,Contractor Type,Abbrevation,Requirements,Status \n";
           if(isset($data) && $data!=array())
           {
               $i=1;
               foreach($data as $model)
               {
                   $service_type_title=$abbrevation=$requirements=$status='-';

                   $service_type_title=(isset($model->service_type_title) && $model->service_type_title !="")?$model->service_type_title:"-";
                   $abbrevation=(isset($model->abbrevation) && $model->abbrevation!= "")?$model->abbrevation:"-";

                   if(isset($model->requirements) && $model->requirements!=null)
                   {
                       $service_type_array = explode(',',$model->requirements);
                       $requirements='';
                       if(isset($service_type_array) && $service_type_array!=array())
                       {
                           foreach($service_type_array as $each_type)
                           {
                               $service_type_data =  ContractorTypeRequirements::findOne($each_type);
                               if(isset($service_type_data) && $service_type_data!=array())
                               {
                                   $service_type_list[]= $service_type_data['name'];
                               }
                           }
                       }
                       if(isset($service_type_list) && $service_type_list!=array())
                           $requirements = implode('-',$service_type_list);
                   }
                   if(isset($model->is_active) && $model->is_active!=null)
                   {
                       if($model->is_active=='Y')
                       {
                           $status = "Active";
                       }
                       else if($model->is_active=='N')
                       {
                           $status = "Inactive";
                       }
                   }
                   echo $i.",".$service_type_title.",".$abbrevation.",".$requirements.','.$status;
                   echo "\n";
                   $i++;
               }
           }
           exit();
   }
   public function actionView_export($id)
   {
          $model = $this->findModel($id);

           $date=time();
           $query = Contractortypes::findOne($id);

           $params=$_REQUEST;

           if(isset($params['ContractortypesSearch']['status']) && $params['ContractortypesSearch']['status']!=null){
               $status=$params['ContractortypesSearch']['status'];
               $query->andFilterWhere(['like', 'is_active', $status]);
           }
           //$data=$query->orderBy('service_type_id desc')->all();
           $data = $query->findOne($id);
          //echo "<pre>";
          //print_r($data);die;
           $filename='ContractorsTypes_'.time().'.csv';

           header('Content-Encoding: UTF-8');
           header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
           header('Content-Disposition: attachment;filename="'.$filename.'"');  //File name extension was wrong
           header('Pragma: public');
           header("Expires: 0");
           header('Content-Transfer-Encoding: binary');
           header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
           header("Cache-Control: private",false);
           echo "NO,Contractor Type,Abbrevation,Requirements,Status \n";
           if(isset($data) && $data!=array())
           {
               $i=1;
               $model=$data;
               $service_type_title=$abbrevation=$requirements=$status='-';

               $service_type_title=(isset($model->service_type_title) && $model->service_type_title !="")?$model->service_type_title:"-";
               $abbrevation=(isset($model->abbrevation) && $model->abbrevation!= "")?$model->abbrevation:"-";

               if(isset($model->requirements) && $model->requirements!=null)
               {
                   $service_type_array = explode(',',$model->requirements);
                   $requirements='';
                   foreach($service_type_array as $each_type)
                   {
                       $service_type_data =  ContractorTypeRequirements::findOne($each_type);
                       if(isset($service_type_data) && $service_type_data!=array())
                       {
                           $service_type_list[]= $service_type_data['name'];
                       }
                   }
                   if(isset($service_type_list) && $service_type_list!=array())
                       $requirements = implode('-',$service_type_list);
               }
               if(isset($model->is_active) && $model->is_active!=null)
               {
                   if($model->is_active=='Y')
                   {
                       $status = "Active";
                   }
                   else if($model->is_active=='N')
                   {
                       $status = "Inactive";
                   }
               }
               echo $i.",".$service_type_title.",".$abbrevation.",".$requirements.','.$status;
               echo "\n";
               $i++;
           }
           exit();
   }

}
