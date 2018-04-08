<?php

namespace app\controllers;

use Yii;
use app\models\Feelingstation;
use app\models\Feelingstationsearch;
use app\models\Service;
use app\models\Product;
use app\models\Facility;
use app\models\Stationreview;
use app\models\Stationreviewsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;


/**
 * FeelingstationController implements the CRUD actions for Feelingstation model.
 */
class FeelingstationController extends Controller
{
    public $layout="admin";
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','index','update','change','view','page','active','reviewsindex'],
                'rules' => [
                    [
                        'actions' => ['create','index','update','change','view','page','active',
                                      'reviewactive','reviews','reviewsindex'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)
                        {
                            $response=Yii::$app->mycomponent->authenticate($action->controller->id,$action->id);
                            /*echo "$response";
                            exit;*/
                            
                            return $response;
                        },
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
     * Lists all Feelingstation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Feelingstationsearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

         
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionReview()
    {
        $searchModel = new Feelingstationsearch();
        $dataProvider = $searchModel->reviews(Yii::$app->request->queryParams);

        return $this->render('reviews', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionReviewsindex()
    {
        $searchModel = new Feelingstationsearch();
        $dataProvider = $searchModel->reviews(Yii::$app->request->queryParams);

        return $this->render('reviewsindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
     public function actionReviews($id)
    {
        
        //$reviews = Stationreview::find()->where(["is_deleted"=>"N","feeling_station_id"=>$id])->all();
        $model_feeling_station = Feelingstation::findOne($id);
        $searchModel = new Feelingstationsearch();
        $dataProvider = $searchModel->reviews(Yii::$app->request->queryParams);
                //$data1 = Users::updateAll(['device_id' => null],'device_id = :device_id and id <> :user_id',array(':device_id'=>$_REQUEST['device_id'],':user_id'=>$data->id));

        $total_star_count = Stationreview::find()
        ->where(['is_deleted' => 'N','feeling_station_id'=>$id,])->count();
        
        $five_star_count = Stationreview::find()
        ->where(['is_deleted' => 'N','feeling_station_id'=>$id,])
        ->andwhere(['=', 'rate',5])
        ->count();
        
        $four_star_count = Stationreview::find()
        ->where(['is_deleted' => 'N','feeling_station_id'=>$id,])
        ->andwhere(['>=', 'rate',4])->andwhere(['<', 'rate',5])
        ->count();
        
         $three_star_count = Stationreview::find()
        ->where(['is_deleted' => 'N','feeling_station_id'=>$id,])
        ->andwhere(['>=', 'rate',3])->andwhere(['<', 'rate',4])
        ->count();
        
         $two_star_count = Stationreview::find()
        ->where(['is_deleted' => 'N','feeling_station_id'=>$id,])
        ->andwhere(['>=', 'rate',2])->andwhere(['<', 'rate',3])
        ->count();
        
         $one_star_count = Stationreview::find()
        ->where(['is_deleted' => 'N','feeling_station_id'=>$id,])
        ->andwhere(['>=', 'rate',1])->andwhere(['<', 'rate',2])
        ->count();
        
        $five_star_per = (($five_star_count != 0)?(($five_star_count/$total_star_count)*100 ):0);
        $four_star_per = (($four_star_count != 0)?(($four_star_count/$total_star_count)*100 ):0);
        $three_star_per = (($three_star_count != 0)?(($three_star_count/$total_star_count)*100 ):0);
        $two_star_per = (($two_star_count != 0)?(($two_star_count/$total_star_count)*100 ):0);
        $one_star_per = (($one_star_count != 0)?(($one_star_count/$total_star_count)*100 ):0);
        
        
        return $this->render('reviews', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'model'=>$reviews,
            'model_feeling_station'=> $model_feeling_station,
            'five_star_per'=> $five_star_per,
            'four_star_per'=> $four_star_per,
            'three_star_per'=> $three_star_per,
            'two_star_per'=> $two_star_per,
            'one_star_per'=> $one_star_per,
            
        ]);
    
        /*$reviews = Stationreview::find()->select(['feeling_station_review.*','user_master.full_name as user_name'])
        ->leftJoin('user_master','user_master.id = feeling_station_review.user_id  ')
        ->where(['feeling_station_review.is_deleted'=>'N'])
        ->all();
        
        $connection = Yii::$app->getDb();
        $query  ->select([
	        'feeling_station_review.*',
	        'user_master.full_name']
	        ) 
	    ->from('feeling_station_review')
	    ->join('LEFT OUTER JOIN', 'user_master',
	                'user_master.id =feeling_station_review.user_id')     
	    ->LIMIT(5)   ;
        $command = $query->createCommand($query);
        $reviews = $command->queryAll(); */
    
    }
    
    
    /**
     * Displays a single Feelingstation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
       $test = $this->findModel($id);
       
       $product_name_list ='';
       $services_name_list ='';
       $facilities_name_list = '';
      
        if(isset($test['products']) && ($test['products'] != ""))
        {
            $products_array = explode(',',$test['products']);
            $product_deatils = Product::find()->where(["is_deleted"=>"N","id"=>$products_array])->all();
            $product_names = ArrayHelper::getColumn($product_deatils,"name");
            $product_name_list = implode(' , ',$product_names);
        }
      
        if(isset($test['services']) && ($test['services'] != ""))
        {
            $services_array = explode(',',$test['services']);
            $services_deatils = Service::find()->where(["is_deleted"=>"N","id"=>$services_array])->all();
            $services_names = ArrayHelper::getColumn($services_deatils,"name");
            $services_name_list = implode(' , ',$services_names);
        }
      
        if(isset($test['facilities']) && ($test['facilities'] != ""))
        {
            $facilities_array = explode(',',$test['facilities']);
            $facilities_deatils = Facility::find()->where(["is_deleted"=>"N","id"=>$facilities_array])->all();
            $facilities_names = ArrayHelper::getColumn($facilities_deatils,"name");
            $facilities_name_list = implode(' , ',$facilities_names);
        }
      
        return $this->render('view', [
            'model' => $this->findModel($id),
            'product_name_list'=>$product_name_list,
            'services_name_list' => $services_name_list,
            'facilities_name_list' => $facilities_name_list
            
        ]);
    }

    /**
     * Creates a new Feelingstation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Feelingstation();
        $service_model = new Service();
        $product_model = new Product();
        $facility_model = new Facility();
        
        $services_list = Service::find()->where(['is_deleted' => 'N','is_active' => 'Y'])->all();
        $product_list = Product::find()->where(['is_deleted' => 'N','is_active' => 'Y'])->all();
        $facility_list = Facility::find()->where(['is_deleted' => 'N','is_active' => 'Y'])->all();
        
        if ($model->load(Yii::$app->request->post()))
        {
            $post = Yii::$app->request->post();
           
            /*echo "<pre>";
            //print_r($post['Service']['name']);
            print_r($post);
            exit;*/
            
           if(isset($post['Service']['name']) &&(!empty($post['Service']['name'])))
           {
            $post_services_id_list = $post['Service']['name'];
            $model->services = implode(',',$post_services_id_list);
           }
            
            if(isset($post['Product']['name']) &&(!empty($post['Product']['name']))){
                $post_products_id_list = $post['Product']['name'];
                $model->products = implode(',',$post_products_id_list);
            }
            
            if(isset($post['Facility']['name']) &&(!empty($post['Facility']['name']))){
                $post_facilities_id_list = $post['Facility']['name'];
                $model->facilities = implode(',',$post_facilities_id_list);
            }
            
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
            return $this->render ('create', array('model' => $model,
                 'service_model' =>  $service_model,
                 'product_model' =>  $product_model,
                 'facility_model' =>  $facility_model,
                 'services_list'=> $services_list,
                 'product_list'=>$product_list,
                 'facility_list'=>$facility_list,
                 ));
           
        }
    }

    /**
     * Updates an existing Feelingstation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $service_model = new Service();
        $product_model = new Product();
        $facility_model = new Facility();
        
        $services_list = Service::find()->where(['is_deleted' => 'N','is_active' => 'Y'])->all();
        $product_list = Product::find()->where(['is_deleted' => 'N','is_active' => 'Y'])->all();
        $facility_list = Facility::find()->where(['is_deleted' => 'N','is_active' => 'Y'])->all();
        
        
        if ($model->load(Yii::$app->request->post()))
        {
            $post = Yii::$app->request->post();
           if(isset($post['Service']['name']) &&(!empty($post['Service']['name'])))
           {
            $post_services_id_list = $post['Service']['name'];
            $model->services = implode(',',$post_services_id_list);
           }else{
                $model->services = "";
           }
            
            if(isset($post['Product']['name']) &&(!empty($post['Product']['name']))){
                $post_products_id_list = $post['Product']['name'];
                $model->products = implode(',',$post_products_id_list);
            }else{
                $model->products  = "";
            }
            
            if(isset($post['Facility']['name']) &&(!empty($post['Facility']['name']))){
                $post_facilities_id_list = $post['Facility']['name'];
                $model->facilities = implode(',',$post_facilities_id_list);
            }else{
                $model->facilities  = "";
            }
            
            
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            if($model->save()){
                return $this->redirect(['index']);
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                    'service_model'=>$service_model,
                    'product_model' =>  $product_model,
                    'facility_model' =>  $facility_model,
                    'services_list'=> $services_list,
                    'product_list'=>$product_list,
                    'facility_list'=>$facility_list,
                ]); 
            }
        } else {
            return $this->render('update', array('model' => $model,
                    'service_model'=>$service_model,
                    'product_model' =>  $product_model,
                    'facility_model' =>  $facility_model,
                    'services_list'=> $services_list,
                    'product_list'=>$product_list,
                    'facility_list'=>$facility_list));
        }
    }

    /**
     * Deletes an existing Feelingstation model.
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
    
     public function actionChange()
    {
        $str = $_REQUEST['str'];
        $field =$_REQUEST['field'];
        $val = $_REQUEST['val'];
        
        if($str!= null)
        {
            $cond = [$field => $val];
                
            if(Feelingstation::updateAll($cond,'id IN('.$str.')'))
            {
                if($_REQUEST['field'] == 'is_deleted')
                {
                    $msg = 'Data successfully deleted';
                    Feelingstation::updateAll($cond,'id IN('.$str.')');
                }
                else{
                    $msg = 'Data successfully updated';
                }
                $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                
            }
            else
            {
                if($_REQUEST['field'] == 'is_deleted')
                    $msg = 'Unable to delete data. Please try again.';
                else
                    $msg = 'Unable to update data. Please try again.';
                    
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
            }
        }
        //print_r($ct); die;
        $this->redirect(['index']);
    }
    
    
     public function actionActive()
     {
        if(isset($_REQUEST['id']))
        {
            $model = $this->findModel($_REQUEST['id']);
            $model->is_active = $_REQUEST['val'];
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->save(false);
        }
     }
   
    
    /*********   function for import in to  tables start created by  shrikant **********/
    public function actionImportindb()
    {
        $station_model = new Feelingstation();
        $service_model = new Service();
        $product_model = new Product();
        $facility_model = new Facility();
        
     if(isset($_FILES['upload']['name']) && $_FILES['upload']['name'] != null)
        {
          if ($station_model->load(Yii::$app->request->post()))
            {
            $post = Yii::$app->request->post();
          
                            $file = $_FILES['upload']['tmp_name'];
                            $handle = fopen($file, "r");
                            $c = 0;
                            
                            /*while(! feof($handle))
                                    {echo "<pre>";
                                    print_r(fgetcsv($handle));
                                    }*/
                                    //exit;
                           
                            while(($each_record = fgetcsv($handle, 1000, ",")) !== false)
                            {
                               $station_model = new Feelingstation();
                               $product_namelist = explode(',',$each_record[2]);
                                if(($c > 0) && (isset($product_namelist)) &&(!empty($product_namelist[0])))
                                {
                                        /*echo "<pre>";
                                        print_r($each_record);
                                        print_r($product_namelist);*/
                                        
                                    $product_list_ids= "";
                                    foreach($product_namelist as $each_product_name)
                                    {
                                        $each_product_name = trim($each_product_name);
                                        $product_list = Product::find()->where(['name'=>$each_product_name,'is_deleted' => 'N','is_active' => 'Y'])->one();            
                                        if($product_list)
                                        {
                                            $product_list_ids[] = $product_list['id']; 
                                        }else{
                                            $product_model = new Product();
                                            $product_model->name = $each_product_name;
                                            $product_model->i_by = Yii::$app->user->id;
                                            $product_model->i_date = time();
                                            $product_model->u_by = Yii::$app->user->id;
                                            $product_model->u_date = time();
                                            if($product_model->save())
                                                {
                                                    $product_id = Yii::$app->db->getLastInsertID();
                                                    $product_list_ids[] = $product_id;
                                                }
                                            else{ echo "not inserted $each_product_name";}
                                            unset($product_model);
                                        }
                                    }
                                    $product_ids = implode(',',$product_list_ids);
                                    $station_model->products = $product_ids;
                                    //echo "<pre>";
                                    //print_r($product_ids);
                                 }
                                 else{  
                                    $station_model->products = null;
                                     }
                                
                                
                                $service_namelist = explode(',',$each_record[3]);
                                 if(($c > 0) && (isset($service_namelist)) &&(!empty($service_namelist[0])))
                                {
                                        /*echo "<pre>";
                                        print_r($each_record);
                                        print_r($service_namelist);*/
                                        
                                    $service_list_ids = "";
                                    foreach($service_namelist as $each_service_name)
                                    {
                                        $each_service_name = trim($each_service_name);
                                        $service_list = Service::find()->where(['name'=>$each_service_name,'is_deleted' => 'N','is_active' => 'Y'])->one();            
                                        if($service_list)
                                        {
                                            $service_list_ids[] = $service_list['id']; 
                                        }else{
                                            $service_model = new Service();
                                            $service_model->name = $each_service_name;
                                            $service_model->i_by = Yii::$app->user->id;
                                            $service_model->i_date = time();
                                            $service_model->u_by = Yii::$app->user->id;
                                            $service_model->u_date = time();
                                            if($service_model->save())
                                                {
                                                    $service_id = Yii::$app->db->getLastInsertID();
                                                    $service_list_ids[] = $service_id;
                                                }
                                            else{ echo "not inserted $each_service_name";}
                                            unset($service_model);
                                        }
                                    }
                                        $service_ids = implode(',',$service_list_ids);
                                        $station_model->services = $service_ids;
                                       // echo "<pre>";
                                        //print_r($service_ids);
                                 }
                                 else{  
                                    $station_model->services = null;
                                     }
                                     
                                     
                                $facility_namelist = explode(',',$each_record[4]);
                                //$working_hrs = 0;
                                //$working_hrs = $facility_namelist[0];
                                if(($c > 0) && (isset($facility_namelist)) &&(!empty($facility_namelist[0])))
                                {
                                        /*echo "<pre>";
                                        print_r($each_record);
                                        print_r($facility_namelist);*/
                                        
                                    $facility_list_ids= "";
                                    foreach($facility_namelist as $each_facility_name)
                                    {
                                        $each_facility_name = trim($each_facility_name);
                                        $facility_list = Facility::find()->where(['name'=>$each_facility_name,'is_deleted' => 'N','is_active' => 'Y'])->one();            
                                        if($facility_list)
                                        {
                                            $facility_list_ids[] = $facility_list['id']; 
                                        }else{
                                            $facility_model = new Facility();
                                            $facility_model->name = $each_facility_name;
                                            $facility_model->i_by = Yii::$app->user->id;
                                            $facility_model->i_date = time();
                                            $facility_model->u_by = Yii::$app->user->id;
                                            $facility_model->u_date = time();
                                            if($facility_model->save())
                                                {
                                                    $facility_id = Yii::$app->db->getLastInsertID();
                                                    $facility_list_ids[] = $facility_id;
                                                }
                                            else{ echo "not inserted $each_facility_name";}
                                            unset($facility_model);
                                        }
                                    }
                                    $facility_ids = implode(',',$facility_list_ids);
                                    $station_model->facilities = $facility_ids;
                                   // echo "<pre>";
                                   // print_r($facility_ids);
                                 }
                                 else{  
                                    $station_model->facilities = null;
                                     }
                                if($c > 0){
                                    $station_model->station_id = trim($each_record[0]);
                                    $station_model->name = trim($each_record[1]);
                                    $station_model->region = trim($each_record[5]);
                                    $station_model->phone_number = trim($each_record[6]);
                                    $station_model->latitude = trim($each_record[7]);
                                    $station_model->longitude = trim($each_record[8]);
                                    $station_model->address = trim($each_record[9]);
                                    $station_model->info = trim($each_record[10]);
                                    $station_model->working_hours = trim($each_record[11]);
                                    if(isset($each_record[12]))
                                    $station_model->map_link = trim($each_record[12]);
                                    $station_model->i_by = Yii::$app->user->id;
                                    $station_model->i_date = time();
                                    $station_model->u_by = Yii::$app->user->id;
                                    $station_model->u_date = time();
                                     /*echo "<pre>";
                                     print_r($station_model);
                                     exit;*/
                                    if($station_model->save())
                                        {
                                        unset($station_model);
                                        }
                                    else{ echo "not inserted $c th Record with name $each_record[1]";
                                        //exit;
                                        }
                                }
                           
                           $c = $c+1; // counter for neglect first row 
                        }//while end
               
                $station_model = new Feelingstation();
                $record_count = $c-1;
                $msg = "You have inserted $record_count records";
                $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                return $this->render ('upload', array('model' => $station_model));
                    //exit;
         }
        } else {
           return $this->render ('upload', array('model' => $station_model
                 ));
        }
    }
   
    /**
     * Finds the Feelingstation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feelingstation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
   
    protected function findModel($id)
    {
        if (($model = Feelingstation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    
    
    /******************* functions start for reviews ********************/
      public function actionReviewactive()
        {
            if(isset($_REQUEST['id']))
            {
                //$model = $this->findModel($_REQUEST['id']);
                $model = Stationreview::findOne($_REQUEST['id']);
                $model->is_active = $_REQUEST['val'];
                $model->u_by = Yii::$app->user->id;
                $model->u_date = time();
                $model->save(false);
                /*if($model->save(false))
                { echo "updated";}
                else{echo "not updated";}*/
                exit;
            }
        }
    
     public function actionDeletereview($id)
    {
        if(isset($_REQUEST['id']))
        {
            $model = Stationreview::findOne($_REQUEST['id']);
            $model->is_deleted = "Y";
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->save(false);
        }
    }
    
     public function actionActivereview()
     {
        if(isset($_REQUEST['id']))
        {
            $model = Stationreview::findOne($_REQUEST['id']);
            $model->is_active = $_REQUEST['val'];
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->save(false);
        }
     }
     
      public function actionChange_review()
    {
        $str = $_REQUEST['str'];
        $field =$_REQUEST['field'];
        $val = $_REQUEST['val'];
        
        if($str!= null)
        {
            $cond = [$field => $val];
                
            if(Stationreview::updateAll($cond,'id IN('.$str.')'))
            {
                if($_REQUEST['field'] == 'is_deleted')
                {
                    $msg = 'Data successfully deleted';
                    Stationreview::updateAll($cond,'id IN('.$str.')');
                }
                else{
                    $msg = 'Data successfully updated';
                }
                $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                
            }
            else
            {
                if($_REQUEST['field'] == 'is_deleted')
                    $msg = 'Unable to delete data. Please try again.';
                else
                    $msg = 'Unable to update data. Please try again.';
                    
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
            }
        }
        //print_r($ct); die;
        $this->redirect(['reviewsindex']);
    }
    
    
    
    public function actionPage()
    {
        if(isset($_REQUEST['size']) && $_REQUEST['size']!=null)
        {
            \Yii::$app->session->set('user.size',$_REQUEST['size']);
        }
    }
}
