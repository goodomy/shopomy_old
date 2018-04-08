<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Users;
use app\models\Usersearch;
use app\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response; // Add This line
use yii\widgets\ActiveForm; //Add This Line

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Usersearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    /**
     * Displays a single Users model.
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
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();

        /*if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) 
            {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            }*/
        
       if ($model->load(Yii::$app->request->post()))
        {
            $model->user_type = 'U'; 
            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->password = md5($_POST['Users']['password']);

            if(isset($_FILES['Users']['name']['image']) && $_FILES['Users']['name']['image'] != null)
            {
                list($width, $height) = getimagesize($_FILES['Users']['tmp_name']['image']);

                $new_image['name'] = $_FILES['Users']['name']['image'];
                $new_image['type'] = $_FILES['Users']['type']['image'];
                $new_image['tmp_name'] = $_FILES['Users']['tmp_name']['image'];
                $new_image['error'] = $_FILES['Users']['error']['image'];
                $new_image['size'] = $_FILES['Users']['size']['image'];
                $image = $new_image;

                $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['userimage'], $width, $width);
                $model->image = Yii::$app->params['userimage'].$name['image'];

                    /*if($model->save())
                    {
                       return $this->redirect(['index']);
                    }*/
            }
        
            if($model->save())
            {
                Yii::$app->mailer->compose('@app/mail/layouts/welcomeemail', [
                        'username' => $model->user_name,
                        'email' => $model->email,
                        'password' => $_POST['Users']['password'],
                        //'link_token' => $post->forgot_password_token,
                        'link' => Yii::$app->params['applink'],
                ])
                ->setTo($model->email)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject(Yii::$app->params['apptitle'].' : Welcome to Shopomy App')
                ->send();
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
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_media_path = $model->image;

        if(isset($_FILES['Users']['name']['image']) && $_FILES['Users']['name']['image'] != null)
        {
            if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path))
            {
                unlink(Yii::getAlias('@webroot')."/".$old_media_path);
            }

            list($width, $height) = getimagesize($_FILES['Users']['tmp_name']['image']);

            $new_image['name'] = $_FILES['Users']['name']['image'];
            $new_image['type'] = $_FILES['Users']['type']['image'];
            $new_image['tmp_name'] = $_FILES['Users']['tmp_name']['image'];
            $new_image['error'] = $_FILES['Users']['error']['image'];
            $new_image['size'] = $_FILES['Users']['size']['image'];
            $image = $new_image;

            $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['userimage'], $width, $width);
            $model->image = Yii::$app->params['userimage'].$name['image'];
        }
        else
        {
            $model->image = $old_media_path;
        }

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


    public function actionDeleteimage(){

        if(isset($_REQUEST['id']) && $_REQUEST['id'] != '' && isset($_REQUEST['image_name']) && $_REQUEST['image_name'] != ''
           && isset($_REQUEST['image_path']) && $_REQUEST['image_path'] != ''){

            $old_media_path = $_REQUEST['image_path'];
            if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path)){

                unlink(Yii::getAlias('@webroot')."/".$old_media_path);
            }

            $model = Users::find()->where(['id'=>$_REQUEST['id']])->one();
            $model->image = null;
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->save(false);
            echo json_encode(array('id'=>$_REQUEST['id']));
            die;
        }

    }

    /**
     * Deletes an existing Users model.
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

    //  public function actionCheckemail(){
    //     $this->layout = false;
        
    //     if(isset($_REQUEST["email"])){
    //         $username = Users::find()->where(["email"=>$_REQUEST["email"],"is_deleted"=>"N"]);
    //         if(isset($_REQUEST["id"]) && !empty($_REQUEST["id"])){
    //            $username->andWhere(['not',['id'=>$_REQUEST['id']]]);
    //         }
    //         $username = $username->one();
    //         if($username==array()){
    //             echo true;
    //             die;
    //         }else{
    //             echo false;
    //             die;
    //         }
    //     }
    //     die;
    // }

    public function actionCheckemail()
    {
        if (Yii::$app->request->isAjax)
        {
            if(isset($_REQUEST['email']) && $_REQUEST['email']!=null)
            {

              if(isset($_REQUEST['id']) && $_REQUEST['id']!=null)
              {
                $data=Users::find()->where(['is_deleted'=>'N','email'=>$_REQUEST['email']])->andWhere(['not',['id'=>$_REQUEST['id']]])->one();
              }
              else {
                $data=Users::find()->where(['is_deleted'=>'N','email'=>$_REQUEST['email']])->one();
              }
              if($data)
              {
                echo "0";
                die;
              }
              else {
                echo '1';
                die;
              }
            }
            echo '0';
            die;
        }
        echo '0';
        die;
    }
    
     public function actionOldpasswordcheck(){
        $this->layout = false;
        if(isset($_REQUEST["pass"])){
            
            
            $getpass = md5($_REQUEST['pass']);
            $model = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            /*echo "$getpass ------";
            print_r($model);
            exit;*/
            
            //$username = Users::find()->where(["user_type"=>"A"]);
            /*if(isset($_REQUEST["id"]) && !empty($_REQUEST["id"])){
               $username->andWhere(['not',['id'=>$_REQUEST['id']]]);
            }*/
            //$username = $username->one();
            
            if($getpass == $model->password){
                echo true;
                die;
            }else{
                echo false;
                die;
            }
        }
        die;
    }
    
    
    
    
    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     public function actionChange()
        {
        $str = $_REQUEST['str'];
        $field =$_REQUEST['field'];
        $val = $_REQUEST['val'];
        
        if($str!= null)
        {
            $cond = [$field => $val];
                
            if(Users::updateAll($cond,'id IN('.$str.')'))
            {
                if($_REQUEST['field'] == 'is_deleted')
                {
                    $msg = 'Data successfully deleted';
                    Users::updateAll($cond,'id IN('.$str.')');
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
    
    
    public function actionSettings()
    {
        //$model = $this->findModel($id);
           $this->layout = 'admin';
            $model = new Users();
            return $this->render('settings',[
                'model'=>$model]);
        
    }
     public function actionProfileview()
    {
        
        $model = Users::find()->where(['id'=>Yii::$app->user->id])->one();
        $type =  $model->user_type;
        
            return $this->render('profile_view', [
                'model' => $model,
            ]);
    }
    
     public function actionEditprofile()
    {
       
        $model = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            
        if ($model->load(Yii::$app->request->post()))
        {
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            if($model->save()){
                return $this->redirect(['profileview']);
            }
            else{
                //echo " not saved";
                //exit;
                return $this->render('profile_view_edit', [
                    'model' => $model,
                ]); 
            }
        } else {
            
            return $this->render('profile_view_edit', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionChangepassword()
      {
            $model = new Users();
        //if ($model->load(Yii::$app->request->post()))
        if($model->load(Yii::$app->request->post()) && ($model->validate()))
        {
             
           $model = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            if($model->password == md5($_POST['Users']['password']))
            {
               
                if(trim($_POST['Users']['new_password']) ==trim($_POST['Users']['PasswordConfirm']))
                {
                    //echo "new password match";
                    //exit;
                    $model->u_by = Yii::$app->user->id;
                    $model->u_date = time();
                    $model->password = md5($_POST['Users']['new_password']);
                     
                }
            }
            if($model->save()){
                return $this->redirect(['site/index']);
            }
            else{
                //print_r($model->getErrors());
                return $this->render('password_edit', [
                    'model' => $model,
                ]); 
            }
        } else {
            
            return $this->render('password_edit', [
                'model' => $model,
            ]);
        }
    }
    
    
    public function actionPage()
    {
        if(isset($_REQUEST['size']) && $_REQUEST['size']!=null)
        {
            \Yii::$app->session->set('user.size',$_REQUEST['size']);
        }
    }

    public function actionExportexcel()
   {

    $query=Users::find()->where(['is_deleted'=>'N','user_type'=>'U']);

    $params=$_REQUEST;

    if(isset($params['Userssearch']['status']) && $params['Userssearch']['status']!=null){
        $status=$params['Userssearch']['status'];
        $query->andFilterWhere(['like', 'is_active', $status]);
    }

    if(isset($params['Userssearch']['keyword']) && $params['Userssearch']['keyword']!=null)
    {
        $keyword=$params['Userssearch']['keyword'];
        $query->andFilterWhere([
            'or',
            ['like', 'user_name', $keyword],
            ['like', 'email', $keyword],
            //['like', 'mobile_number', $keyword],
        ]);
    }

     
     

     $data=$query->orderBy('id DESC')->all();



     include Yii::getAlias('@vendor').'/PHPExcel/Classes/PHPExcel/IOFactory.php';
     include Yii::getAlias('@vendor').'/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

     $filename='user_report-'.date('_d-m-Y').'.xls';
     $lastCol='C';
     $objPHPExcel = new \PHPExcel();

     $objSheet = $objPHPExcel->getActiveSheet();
     $objSheet->setTitle('subscribers_report');
     $objSheet->getStyle('A1:'.$lastCol.'1')->getFont()->setBold(true)->setSize(12);

     $objPHPExcel->setActiveSheetIndex(0);

     $rowCount = 1;
     $objPHPExcel->getActiveSheet()->getDefaultRowDimension(1)->setRowHeight(25);
     $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
     $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
     $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
     $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    //  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
     $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,'User Report');
     $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':'.$lastCol.$rowCount);
     $objSheet->getStyle('A'.($rowCount))->getFont()->setBold(true);
     $rowCount++;

     $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,'Name');
     $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,'Email');
     $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,'Status');
    //  $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,'Date of Joining');
     $objSheet->getStyle('A'.$rowCount.':'.$lastCol.$rowCount)->getFont()->setBold(true);

     $rowCount++;

     if(isset($data) && $data!=null)
     {
       foreach ($data as $list)
       {
         $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,ucfirst($list['user_name']));
         $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$list['email']);
         $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$list['is_active']=='Y'?"Active":"Inactive");
        //  $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,date(Yii::$app->params['date_format'],$list['i_date']));
         $rowCount++;
       }
     }

     $objSheet->getStyle('A1:'.$lastCol.($rowCount-1))->applyFromArray(
               array('borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN,
                 'color' => array('rgb' => '000')))));

     $objSheet->getStyle('A1:'.$lastCol.$rowCount)->getAlignment()->applyFromArray(
             array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
     );
     $objPHPExcel->getActiveSheet()
       ->getStyle('A2:'.$lastCol.$rowCount)
       ->getAlignment()
       ->setWrapText(true);
      header('Content-type: application/vnd.ms-excel');
      header('Content-Disposition: attachment; filename="'.$filename.'"');
      header('Cache-Control: max-age=0');
      // header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
      header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
      header ('Cache-Control: cache, must-revalidate');
      header ('Pragma: public');
     $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

     $objWriter->save('php://output');
     exit;
   }


   public function actionPosts()
    {
        
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('post', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
