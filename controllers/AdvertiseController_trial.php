<?php

namespace app\controllers;

use Yii;
use app\models\Advertise;
use app\models\Advertisesearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AdvertiseController implements the CRUD actions for Advertise model.
 */
class AdvertiseController extends Controller
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
                        'matchCallback' => function ($rule, $action)
                        {
                            $response=Yii::$app->mycomponent->authenticate($action->controller->id,$action->id);
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
     * Lists all Advertise models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Advertisesearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Advertise model.
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
     * Creates a new Advertise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Advertise();

        if ($model->load(Yii::$app->request->post()))
        {
         
            if($model->validate())
            {
                $model->i_by = Yii::$app->user->id;
                $model->i_date = time();
                $model->u_by = Yii::$app->user->id;
                $model->u_date = time();
            
            
                /*echo "<pre>";
                print_r($_POST);
                print_r($_FILES);
                exit;*/
            
                if(isset($_FILES['Advertise']['name']['image']) && $_FILES['Advertise']['name']['image'] != null)
                {
                    list($width, $height) = getimagesize($_FILES['Advertise']['tmp_name']['image']);
                    
                    $new_image['name'] = $_FILES['Advertise']['name']['image'];
                    $new_image['type'] = $_FILES['Advertise']['type']['image'];
                    $new_image['tmp_name'] = $_FILES['Advertise']['tmp_name']['image'];
                    $new_image['error'] = $_FILES['Advertise']['error']['image'];
                    $new_image['size'] = $_FILES['Advertise']['size']['image'];
                    $image = $new_image;
                    
                    $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['advertiseimage'], $width, $width);
                    $model->media_path = Yii::$app->params['advertiseimage'].$name['image'];
                    
                    
                    /* ----------- shrikant temparary code for upload  start ------------- */
                       /* $file_name = $_FILES["Advertise"]["name"]['image'];
                        $target_file = Yii::getAlias('@webroot')."/"."img/uploads/advertise/".basename($file_name);
                        
                        if (move_uploaded_file($_FILES["Advertise"]["tmp_name"]['image'], $target_file)) {
                            echo "The file". basename( $_FILES["Advertise"]["name"]['image']). " has been uploaded.";
                        $model->media_path = "img/uploads/advertise/".$new_image['name'];
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }*/
                        /* -----------  temparary code for upload  end ------------- */
                
                        if($model->save())
                        {
                           return $this->redirect(['index']);
                        }
                }
                elseif(isset($_FILES['Advertise']['name']['video']) && $_FILES['Advertise']['name']['video'] != null)
                {
                    //list($width, $height) = getimagesize($_FILES['Advertise']['tmp_name']['image']);
                    
                    //$name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['advertiseimage'], $width, $width);
                    //$model->media_path = Yii::$app->params['advertiseimage'].$name['image'];
                    
                    
                    $image = $_FILES['Advertise']['name']['video'];
                    $ext = substr(strrchr($image, "."), 1);
                    $fileName = md5(rand() * time()) . ".$ext";
                    $model->media_path = Yii::$app->params['advertiseimage'].$fileName;
                    //echo $_FILES['Advertise']['tmp_name']['video'];
                    //echo "<br/ >";
                    //echo Yii::getAlias('@webroot').'/'.$model->media_path;
                    //die;
                    move_uploaded_file($_FILES['Advertise']['tmp_name']['video'], Yii::getAlias('@webroot').'/'.$model->media_path);
                    
                 
                    
                    // $extn_arr = explode('.',$_FILES['Advertise']['name']['video']);
                    // $extn = end($extn_arr);
                    // $no = time();
                    // $new_file_name = hash('sha256', $no);
                    //
                    ///* ----------- shrikant temparary code for upload  start ------------- */
                    //    $file_name = $_FILES["Advertise"]["name"]['video'];
                    //    $target_file = Yii::getAlias('@webroot')."/"."img/uploads/advertise/".$new_file_name.'.'.$extn;
                    //    $target_file1 = Yii::getAlias('@webroot');
                    //   
                    //   
                    //    echo "$target_file<br><pre>";
                    //    echo "$target_file1<br>";
                    //    print_r($_FILES["Advertise"]["tmp_name"]['video']);
                    //    print_r($_FILES);
                    //    //print_r($_SERVER['DOCUMENT_ROOT']).'/apps/almaha/web/img/uploads/advertise/'.$new_file_name.'.'.$extn;
                    //    $server_full_path = $_SERVER['DOCUMENT_ROOT'].'/apps/almaha/web/img/uploads/advertise/'.$new_file_name.'.'.$extn;
                    //    echo "$server_full_path";
                    //    exit;
                    //    
                    //    if (move_uploaded_file($_FILES["Advertise"]["tmp_name"]['video'], Yii::getAlias('@webroot')."/".Yii::$app->params['advertiseimage'].$new_file_name.'.'.$extn)) {
                    //        echo "The file". basename( $_FILES["Advertise"]["name"]['video']). " has been uploaded.";
                    //    $model->media_path = "img/uploads/advertise/".$new_file_name.'.'.$extn;
                    //    } else {
                    //        echo "Sorry, there was an error uploading your file.";
                    //    }
                        //exit;
                        /* -----------  temparary code for upload  end ------------- */
                
                     if($model->save())
                    {
                    return $this->redirect(['index']);
                     }
                     else {
                        echo " error is not saved";
                        print_r($model->getErrors());
                        exit;
                     }
                }
                else{
                    $file_msg = 'Please Select File';
                     \Yii::$app->getSession()->setFlash('flashfilr_msg', $file_msg);
                    return $this->render('create', [
                    'model' => $model,
                    ]);
                }
                
            }
            
            
            else {
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
     * Updates an existing Advertise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_media_path = $model->media_path;
        $old_media_type = $model->media_type;
        
        
                

        if ($model->load(Yii::$app->request->post()))
        {
            $params = Yii::$app->request->post();
        
                
            if($model->validate())
            {
                $model->is_active = 'Y';
                $model->is_deleted = 'N';
                $model->u_by = Yii::$app->user->id;
                $model->u_date = time();
                
                 if($_POST['Advertise']['media_type'] !== $old_media_type )
                {
                   
                   if( ($_POST['Advertise']['media_type'] == 'I') && ($_FILES['Advertise']['name']['image'] == null))
                   {
                      //echo "Please Select Image File";
                    // exit;
                     
                     $file_msg = 'Please Select Image File';
                     \Yii::$app->getSession()->setFlash('flashfilr_msg', $file_msg);
                     return $this->render('create', [
                     'model' => $model,
                    ]);
                   }
                   if(($_POST['Advertise']['media_type'] == 'V') && ($_FILES['Advertise']['name']['video'] == null))
                   {
                     //echo "Please Select Video File";
                    // exit;
                     
                     $file_msg = 'Please Select Video File';
                     \Yii::$app->getSession()->setFlash('flashfilr_msg', $file_msg);
                     return $this->render('create', [
                     'model' => $model,
                    ]);
                   }
                   
                }
                
                if(isset($_FILES['Advertise']['name']['image']) && $_FILES['Advertise']['name']['image'] != null)
                {
                    if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path))
                    {
                        unlink(Yii::getAlias('@webroot')."/".$old_media_path);
                    }
                    
                    list($width, $height) = getimagesize($_FILES['Advertise']['tmp_name']['image']);
                    
                    $new_image['name'] = $_FILES['Advertise']['name']['image'];
                    $new_image['type'] = $_FILES['Advertise']['type']['image'];
                    $new_image['tmp_name'] = $_FILES['Advertise']['tmp_name']['image'];
                    $new_image['error'] = $_FILES['Advertise']['error']['image'];
                    $new_image['size'] = $_FILES['Advertise']['size']['image'];
                    $image = $new_image;
                    
                    $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['advertiseimage'], $width, $width);
                    $model->media_path = Yii::$app->params['advertiseimage'].$name['image'];
                    
                     /* ----------- shrikant temparary code for upload  start ------------- */
                       /* $file_name = $_FILES["Advertise"]["name"]['image'];
                        $target_file = Yii::getAlias('@webroot')."/"."img/uploads/advertise/".basename($file_name);
                        
                        if (move_uploaded_file($_FILES["Advertise"]["tmp_name"]['image'], $target_file)) {
                            echo "The file". basename( $_FILES["Advertise"]["name"]['image']). " has been uploaded.";
                        $model->media_path = "img/uploads/advertise/".$new_image['name'];
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                     //exit;*/
                        /* -----------  temparary code for upload  end ------------- */
                    
                }
                elseif(isset($_FILES['Advertise']['name']['video']) && $_FILES['Advertise']['name']['video'] != null)
                {
                    if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path))
                    {
                        unlink(Yii::getAlias('@webroot')."/".$old_media_path);
                        echo "old file unlink <br>";
                    }
                    
                     $extn_arr = explode('.',$_FILES['Advertise']['name']['video']);
                     $extn = end($extn_arr);
                     $no = time();
                     $new_file_name = hash('sha256', $no);
                    
                    //list($width, $height) = getimagesize($_FILES['Advertise']['tmp_name']['image']);
                    
                    
                    //$name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['advertiseimage'], $width, $width);
                    //$model->image = Yii::$app->params['advertiseimage'].$new_image['name'];
                    
                     /* ----------- shrikant temparary code for upload  start ------------- */
                        $file_name = $_FILES["Advertise"]["name"]['video'];
                        $target_file = Yii::getAlias('@webroot')."/"."img/uploads/advertise/".$new_file_name.'.'.$extn;
                        
                        
                        if (move_uploaded_file($_FILES["Advertise"]["tmp_name"]['video'], $target_file)) {
                            echo "The file". basename( $_FILES["Advertise"]["name"]['video']). " has been updated successfully.";
                        $model->media_path = "img/uploads/advertise/".$new_file_name.'.'.$extn;
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                      //exit;  
                        /* -----------  temparary code for upload  end ------------- */
                }
                else{
                    $model->media_path = $old_media_path;
                }
                
                if($model->save())
                {
                    return $this->redirect(['index']);
                }
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            //return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Advertise model.
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
                
            if(Advertise::updateAll($cond,'id IN('.$str.')'))
            {
                if($_REQUEST['field'] == 'is_deleted')
                {
                    $msg = 'Data successfully deleted';
                    Advertise::updateAll($cond,'id IN('.$str.')');
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
    
    
    
    
    
    /**
     * Finds the Advertise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advertise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Advertise::findOne($id)) !== null) {
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
