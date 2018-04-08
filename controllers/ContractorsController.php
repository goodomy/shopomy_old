<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\Usersearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use app\models\Contractortypes;
use app\models\Certificationcontractor;



/**
 * UsersController implements the CRUD actions for Users model.
 */
class ContractorsController extends Controller
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
                        'actions' => ['create','index','update','change','view','page','active','deleteimage'],
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
        $dataProvider = $searchModel->contractors_search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProfessional_details($id)
    {
        $certification_contractor = Certificationcontractor::find()->where(['contractor_id'=>$id])->all();
        return $this->render('professional_details', [
            'model' => $this->findModel($id),
            'certification_contractor'=>$certification_contractor,
        ]);
    }

    public function actionPreferences($id)
    {
        return $this->render('preferences', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionServices()
    {
        $searchModel = new Usersearch();
        $dataProvider = $searchModel->services_search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTransactions()
    {
        $searchModel = new Usersearch();
        $dataProvider = $searchModel->transactions_search(Yii::$app->request->queryParams);

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

        if ($model->load(Yii::$app->request->post()))
        {
            $model->user_type = 'C';
            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->password = md5($_POST['Users']['password']);

            if(isset($_FILES['Contractors']['name']['image']) && $_FILES['Contractors']['name']['image'] != null)
            {
                list($width, $height) = getimagesize($_FILES['Contractors']['tmp_name']['image']);

                $new_image['name'] = $_FILES['Contractors']['name']['image'];
                $new_image['type'] = $_FILES['Contractors']['type']['image'];
                $new_image['tmp_name'] = $_FILES['Contractors']['tmp_name']['image'];
                $new_image['error'] = $_FILES['Contractors']['error']['image'];
                $new_image['size'] = $_FILES['Contractors']['size']['image'];
                $image = $new_image;

                $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['contractorimage'], $width, $width);
                $model->image = Yii::$app->params['contractorimage'].$name['image'];

                    /*if($model->save())
                    {
                       return $this->redirect(['index']);
                    }*/
            }

            if($model->save())
            {
                if($model->user_type=='U')
                {
                    $link="www.google.com";
                }
                else if($model->user_type=='C')
                {
                    $link="www.yahoo.com";
                }
                //$link = Url::to("@web/site/resetpassword?args=".$post->forgot_password_token,true);
                Yii::$app->mailer->compose('@app/mail/layouts/welcomeemail', [
                        'username' => $model->full_name,
                        'email' => $model->email,
                        'password' => $_POST['Users']['password'],
                        //'link_token' => $post->forgot_password_token,
                        'link' => $link,
                ])
                ->setTo($model->email)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject(Yii::$app->params['apptitle'].' : Welcome mail')
                //->setTextBody("Your new Password is : ".$pass)
                ->send();

                //$msg = "Password Link has been sent to your email";
                //$flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                //\Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

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

        if(isset($_FILES['Contractors']['name']['image']) && $_FILES['Contractors']['name']['image'] != null)
        {
            if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path))
            {
                unlink(Yii::getAlias('@webroot')."/".$old_media_path);
            }

            list($width, $height) = getimagesize($_FILES['Contractors']['tmp_name']['image']);

            $new_image['name'] = $_FILES['Contractors']['name']['image'];
            $new_image['type'] = $_FILES['Contractors']['type']['image'];
            $new_image['tmp_name'] = $_FILES['Contractors']['tmp_name']['image'];
            $new_image['error'] = $_FILES['Contractors']['error']['image'];
            $new_image['size'] = $_FILES['Contractors']['size']['image'];
            $image = $new_image;

            $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['contractorimage'], $width, $width);
            $model->image = Yii::$app->params['contractorimage'].$name['image'];
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

    public function actionCheckemail(){
        $this->layout = false;

        if(isset($_REQUEST["email"])){
            $username = Users::find()->where(["email"=>$_REQUEST["email"],"is_deleted"=>"N","user_type"=>"C"]);
            if(isset($_REQUEST["id"]) && !empty($_REQUEST["id"])){
               $username->andWhere(['not',['id'=>$_REQUEST['id']]]);
            }
            $username = $username->one();
            if($username==array()){
                echo true;
                die;
            }else{
                echo false;
                die;
            }
        }
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

    public function actionVerification()
    {
        if(isset($_REQUEST['id']))
        {
            $model = $this->findModel($_REQUEST['id']);
            $model->verification_status = $_REQUEST['val'];
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

    //********************************************************************************
    //Title : Export Users
    //Developer:Shahnavaz Patni
    //Email:shahnavaz@peerbits.com
    //Company:Peerbits Solution
    //Project:Nursigo
    //Created By : Shahnavaz Patni
    //Created Date : 23-11-2017
    //Updated Date :
    //Updated By :
    //********************************************************************************
    public function actionExport()
    {
            $date=time();
            $query = Users::find();
            $query->where(['is_deleted'=>'N','user_type'=>'C']);

            $params=$_REQUEST;



            if(isset($params['Contractorssearch']['status']) && $params['Contractorssearch']['status']!=null){
                $status=$params['Contractorssearch']['status'];
                $query->andFilterWhere(['like', 'is_active', $status]);
            }

            if(isset($params['Contractorssearch']['type']) && $params['Contractorssearch']['type']!=null){
                $type=$params['Contractorssearch']['type'];
                $query->andWhere('find_in_set('.$type.',service_type_id)');
            }

            if(isset($params['Contractorssearch']['verification']) && $params['Contractorssearch']['verification']!=null){
                $verification=$params['Contractorssearch']['verification'];
                $query->andFilterWhere(['like', 'verification_status', $verification]);
            }

            if(isset($params['Contractorssearch']['strikes']) && $params['Contractorssearch']['strikes']!=null){
                $strikes=$params['Contractorssearch']['strikes'];
                $query->andFilterWhere(['like', 'total_strikes', $strikes]);
            }

            if(isset($params['Contractorssearch']['keyword']) && $params['Contractorssearch']['keyword']!=null)
            {
                $keyword=$params['Contractorssearch']['keyword'];
                $query->andFilterWhere([
                    'or',
                    ['like', 'full_name', $keyword],
                    ['like', 'email', $keyword],
                    ['like', 'mobile_number', $keyword],
                ]);
            }

            $data=$query->orderBy('id desc')->all();


            $filename='Contractors_'.time().'.csv';


            header('Content-Encoding: UTF-8');
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment;filename="'.$filename.'"');  //File name extension was wrong
            header('Pragma: public');
            header("Expires: 0");
            header('Content-Transfer-Encoding: binary');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);



            echo "NO,Contractor,Contact Number,Email,Contractor Type,Verification,Status \n";

            //$getRestuarantList=Yii::$app->frontcomponent->getBranch($restaurantid);


            //$getJobtypeList=Yii::$app->mycomponent->getJobtypelist();
            if(isset($data) && $data!=array())
            {
                // echo "<pre>";
                // print_r($data);die;
                $i=1;
                foreach($data as $model)
                {

                    $full_name=$mobile_number=$email=$service_type=$verification_status=$status='-';


                    $full_name=(isset($model->full_name) && $model->full_name!= "")?$model->full_name:"-";




                    $mobile_number=(isset($model->mobile_number) && $model->mobile_number!= "")?$model->mobile_number:"-";

                    $email=(isset($model->email) && $model->email!= "")?$model->email:"-";

                    $user_subtype=(isset($model->user_subtype) && $model->user_subtype!= "")?$model->user_subtype:"-";

                    if(isset($model->service_type_id) && $model->service_type_id!=null)
                    {
                        $service_type_array = explode(',',$model->service_type_id);
                        $service_type='';
                        $service_type_list= array();
                        if(isset($service_type_array) && $service_type_array!=array())
                        {

                            foreach($service_type_array as $each_type)
                            {
                                $service_type_data =  Contractortypes::findOne($each_type);
                                if(isset($service_type_data) && $service_type_data!=array())
                                {

                                    $service_type_list[]= $service_type_data['abbrevation'];
                                }

                            }
                        }

                        if(isset($service_type_list) && $service_type_list!=array())
                            $service_type = implode('-',$service_type_list);


                    }
                    //echo $service_type;
                        // print_r($service_type);
                        //die;



                    if(isset($model->verification_status) && $model->verification_status!=null)
                    {
                        if($model->verification_status=='P')
                        {
                            $verification_status = "Pending";
                        }
                        else if($model->verification_status=='N')
                        {
                            $verification_status = "Not Verified";
                        }
                        else if($model->verification_status=='V')
                        {
                            $verification_status = "Verified";
                        }

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


                    echo $i.",".$full_name.",".$mobile_number.",".$email.','.$service_type.','.$verification_status.','.$status;
                    echo "\n";
                $i++;
                }
            }
            exit();

    }

    public function actionResetpassword()
    {
        if(isset($_REQUEST['id']) && $_REQUEST['id'] != null)
        {
            $data = Users::find()->where(['id'=>$_REQUEST['id'],'is_deleted'=>'N'])->one();
            $oldmodel = $data;
            $data->scenario='resetpassword';
            $data->password= "";

            if($data)
            {
                $this->layout='admin';
                $data->scenario='resetpassword';
                if(isset($_POST['Users']) && $_POST['Users']!=array())
                {
                    if(isset($_POST['Users']['password'])&& $_POST['Users']['password']!=null)
                    {
                        $data->password=md5($_POST['Users']['password']);
                    }
                    else
                    {
                        $data->password=$oldmodel->password;
                    }
                    $data->u_date=time();
                    if($data->save(false))
                    {
                        Yii::$app->mailer->compose('@app/mail/layouts/passwordchange', [
                                'username' => $data->full_name,
                                'email' => $data->email,
                                'password' => $_POST['Users']['password'],
                                //'link_token' => $post->forgot_password_token,
                                'link' => Yii::$app->params['applink'],
                        ])
                        ->setTo($data->email)
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setSubject(Yii::$app->params['apptitle'].' : Change Password')
                        //->setTextBody("Your new Password is : ".$pass)
                        ->send();

                        //return $this->render('resetpassword',['model'=>$model]);
                        $msg = 'Password has been successfully changed.';
                        $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                        \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                        return $this->redirect(Yii::$app->request->baseUrl.'/users/resetpassword?id='.$_REQUEST['id']);
                    }
                    else
                    {
                        $msg = 'Something went wrong please try again.';
                        $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                        \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                        return $this->render(Yii::$app->request->baseUrl.'/users/resetpassword?id='.$_REQUEST['id']);
                    }
                }
                else
                    return $this->render('resetpassword',['model'=>$oldmodel]);

            }
            else
            {
                $msg = Yii::$app->params['This user was deleted. Please add new entry.'];
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                return $this->redirect(Yii::$app->request->baseUrl.'/users/resetpassword?id='.$_REQUEST['id']);
            }
        }
        else
        {
            //echo '3';die;
            //$flash_msg = \Yii::$app->params['msg_error'].' No such data found.'.\Yii::$app->params['msg_end'];
            //\Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
            //return $this->render('login');
            $msg = 'No such data found.';
            $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
            \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
            //return $this->redirect('acknowledgement');
            return $this->redirect(Yii::$app->request->baseUrl.'/users/resetpassword?id='.$_REQUEST['id']);
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


}
