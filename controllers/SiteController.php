<?php

namespace app\controllers;

use Yii;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use app\models\LoginForm;
use app\models\User;
use app\models\Users;
use app\models\Post;
use app\models\Category;

use app\models\Feelingstation;
use app\models\Feelingstationsearch;
use app\models\Service;
use app\models\Product;
use app\models\Facility;
use app\models\Feedback;
use app\models\Advertise;
use app\models\Cmspage;
use app\models\Role;
use app\models\Stationreview;

use yii\web\Session;

use yii\widgets\ActiveForm;

class SiteController extends Controller
{
    //public $enableCsrfValidation = false;
    public function behaviors()
    {
         return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','logout','timezone' ],
                'rules' => [
                    [
                        'actions' => ['index','logout','timezone'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
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

    public function actionTimezone()
    {
        $session = Yii::$app->session;
        if ($session->isActive)
            $session->open();

        $session->set('admintimezone', $_REQUEST['timezone']);
        die;
    }

    public function actions()
    {
        return [
            //'error' => [
            //    'class' => 'yii\web\ErrorAction',
            //],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
     // public function actionError()
     // {
     //      $exception = Yii::$app->errorHandler->exception;
     //      //echo '<pre>';print_r($exception);die;
     //      if(Yii::$app->user->identity->usertype == 'D')
     //      {
     //          $flash_msg = \Yii::$app->params['msg_error'].' '.$exception->getMessage().\Yii::$app->params['msg_end'];
     //          \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
     //      }elseif(Yii::$app->user->identity->usertype == 'A')
     //      {
     //          $flash_msg = \Yii::$app->params['msg_error'].' '.$exception->getMessage().\Yii::$app->params['msg_end'];
     //          \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
     //      }
     //      elseif(Yii::$app->user->identity->usertype == 'S')
     //      {

     //          $flash_msg = \Yii::$app->params['msg_error'].' '.$exception->getMessage().\Yii::$app->params['msg_end'];
     //          \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
     //      }
     //      else{
     //          Yii::$app->user->logout(false);
     //      }
     //      return $this->redirect(['index']);
     // }

    public function actionError()
    {
        $this->layout = 'admin';
         $exception = Yii::$app->errorHandler->exception;
         if ($exception !== null)
         {
           if($exception->statusCode==404) {
             return $this->render('error_404');
           }
           else {
             return $this->render('error');
           }
         }
         return $this->redirect(['index']);
    }

     public function actionIndex()
     {
           $users_count =  Users::find()->where(['is_deleted' => 'N', 'user_type' => 'U'])->count();
           $posts_count =  Post::find()->where(['is_deleted' => 'N'])->count();
           $category_count =  Category::find()->where(['is_deleted' => 'N', 'parent_id'=>0])->count();

           // $organization_users_count =  Users::find()->where(['is_deleted' => 'N', 'user_type' => 'U', 'user_subtype' => 'O'])->count();
           //$product_count =  Product::find()->where(['is_deleted' => 'N'])->count();
           //$service_count =  Service::find()->where(['is_deleted' => 'N'])->count();
           //$facility_count =  Facility::find()->where(['is_deleted' => 'N'])->count();
           //$feelingstation_count =  Feelingstation::find()->where(['is_deleted' => 'N'])->count();
           //$feedbacks_count =  Feedback::find()->where(['is_deleted' => 'N'])->count();
           //$advertise_count =  Advertise::find()->where(['is_deleted' => 'N'])->count();

           //$role_count =  Role::find()->where(['is_deleted' => 'N'])->count();
           //$subadmin_count =  Users::find()->where(['is_deleted' => 'N','user_type'=>'S'])->count();
           //$reviews_count = Stationreview::find()->where(['is_deleted' => 'N'])->count();
           //$notification_count =  Users::find()->where(['is_deleted' => 'N','user_type'=>'S'])->count();

           // $feedbacks=Yii::$app->mycomponent->getfeedbacklist(4);
           // $count=count(Yii::$app->mycomponent->getfeedbacklist());



           $this->layout = 'admin';
          return $this->render('index',[
               'users_count' =>$users_count
               ,'posts_count' =>$posts_count
               ,'category_count' =>$category_count
               // ,'contractors_count' =>$contractors_count
               // ,'organization_users_count'=>$organization_users_count
               // ,'count'=>$count
               // // ,'facility_count'=>$facility_count
               // // ,'feelingstation_count'=>$feelingstation_count
               // // ,'feedbacks_count'=>$feedbacks_count
               // // ,'advertise_count'=>$advertise_count
               // // ,'role_count'=>$role_count
               // // ,'subadmin_count'=>$subadmin_count
               // // ,'reviews_count'=>$reviews_count
               // ,'feedbacks'=>$feedbacks
               ]);

     }

    /*
     * Verify Email ID
     */
    public function actionEmailverification()
    {
        $this->layout = 'emailverification';
        if(isset($_REQUEST['token']) && $_REQUEST['token'] != null)
        {
            $type = array('U');
            $token = $_REQUEST['token'];
            $findUser = Users::find()->where(['user_type'=>$type,'email_verification_token'=>$token])->one();
            if(isset($findUser))
            {
                if($findUser->is_email_verified == 'N'){
                    Yii::$app->db->createCommand()->update('user_master', [
                        'is_email_verified' => 'Y',
                        'i_date' => time(),
                        'u_date' => time()
                    ], 'id = '.$findUser->id)->execute();

                    $msg = "Your email is verified now.";
                    $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
	                return $this->redirect('emailverified');
                }else{
                    $msg = "Email already verified.";
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                    //return $this->redirect(['default/index/']);
                    return $this->redirect('emailverified');
                }
            }else{
                $msg = "Email verification token is not valid";
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                //return $this->redirect(['default/index/']);
                return $this->redirect('emailverified');
            }
        }else{
            $msg = "You don't have permission to access this page.";
            $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
            \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

            return $this->redirect(['emailverified']);
        }
    }


    public function actionSocialemailverification()
    {
        $this->layout = 'emailverification';
        if(isset($_REQUEST['token']) && $_REQUEST['token'] != null)
        {
            $u_type = array('U');
            $token = $_REQUEST['token'];
            if(isset($_REQUEST['type']) && $_REQUEST['type'] != null)
                {
                    if($_REQUEST['type'] == 'T')
                    {

                        $type = 'is_twitter_verified';

                        $pre_type = 'twitter_token';
                        $findUser = Users::find()->where(['user_type'=>$u_type,'is_deleted'=>'N','twitter_token'=>$token])->one();

                    }

                    if($_REQUEST['type'] == 'G')
                    {
                        $type = 'is_google_verified';
                        $pre_type = 'google_token';
                        $findUser = Users::find()->where(['user_type'=>$u_type,'is_deleted'=>'N','google_token'=>$token])->one();
                    }
                    if($_REQUEST['type'] == 'F')
                    {
                        $type = 'is_facebook_verified';
                        $pre_type = 'facebook_token';
                               $findUser = Users::find()->where(['user_type'=>$u_type,'is_deleted'=>'N','facebook_token'=>$token])->one();
                    }

                }else{
                      $msg = "You don't have permission to access this page.";
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                    return $this->redirect(['emailverified']);

                }


            if(isset($findUser))
            {



                if($findUser->$type == 'N'){
                    Yii::$app->db->createCommand()->update('user_master', [

                         $type=>'Y',
                        'i_date' => time(),
                        'u_date' => time()
                    ], 'id = '.$findUser->id)->execute();

                    $msg = "Your email is verified now.";
                    $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
	                return $this->redirect('emailverified');
                }else{
                    $msg = "Email already verified.";
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                    //return $this->redirect(['default/index/']);
                    return $this->redirect('emailverified');
                }
            }else{
                $msg = "Email verification token is not valid";
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                //return $this->redirect(['default/index/']);
                return $this->redirect('emailverified');
            }
        }else{
            $msg = "You don't have permission to access this page.";
            $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
            \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

            return $this->redirect(['emailverified']);
        }
    }

    /*
     * Verify Email ID
     */
    public function actionAcknowledgement()
    {
        $this->layout = 'login';
        return $this->render('acknowledgement');
    }

    /*
     * Reset password request
     */
    public function actionResetpassword()
    {
        if(isset($_REQUEST['args']) && $_REQUEST['args'] != null)
        {
            //echo $_REQUEST['args'];die;
            $data = Users::find()->where(['forgot_password_token'=>$_REQUEST['args'],'is_deleted'=>'N'])->one();
            if(!$data)
            {
               $msg = Yii::$app->params['error_forgot_password_link_expired'];
               $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
               \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
               return $this->redirect('acknowledgement');
            }

            $oldmodel = $data;
            $data->scenario='resetpassword';
            $data->password= "";

           if (Yii::$app->request->isAjax && $data->load(Yii::$app->request->post())) {
               echo  json_encode(ActiveForm::validate($data));
               die;
            }
            if($data)
            {
                if($data->forgot_password_token_timeout == '' || $data->forgot_password_token_timeout+(60*60) < time())
                {
                    $msg = Yii::$app->params['error_forgot_password_link_expired'];
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                    return $this->redirect('acknowledgement');
                }
                else
                {

                    $this->layout='login';
                    $data->scenario='resetpassword';
                    if(isset($_POST['Users']) && $_POST['Users']!=array())
                    {

                        if(isset($_POST['Users']['password'])&& $_POST['Users']['password']!=null)
                        {
                            $data->password=md5($_POST['Users']['password']);
                            //$data->PasswordConfirm=md5($_POST['User']['PasswordConfirm']);
                        }
                        else
                        {
                            $data->password=$oldmodel->password;
                        }
                         $data->forgot_password_token = null;
                         $data->forgot_password_token_timeout = null;
                         $data->u_date=time();
                       if($data->save(false))
                       {
                            //return $this->render('resetpassword',['model'=>$model]);
                            $msg = 'Password has been successfully changed.';
                            $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                            \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                            return $this->redirect('acknowledgement');
                       }
                       else
                       {
                            $msg = 'Something went wrong please try again.';
                            $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                            \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                            return $this->render('acknowledgement');
                       }
                    }
                    else
                        return $this->render('resetpassword',['model'=>$data]);
                }
            }
            else
            {
                //echo '2';die;
                //$flash_msg = \Yii::$app->params['msg_error'].' '.Yii::$app->params['error_forgot_password_link_expired'].\Yii::$app->params['msg_end'];
                //\Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                //return $this->render('login');
                $msg = Yii::$app->params['error_forgot_password_link_expired'];
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                return $this->redirect('acknowledgement');
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
            return $this->redirect('acknowledgement');
        }

    }


    public function actionLogin()
    {

        $this->layout = 'login';
        //print_r(\Yii::$app->user->isGuest); die;
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        $user = new Users();
        $user1 = new Users();

        //print_r($model->login()); die;
        if($model->load(Yii::$app->request->post()) && $model->login())
        {




             $params = Yii::$app->request->post();



             $user_not_active = Users::find()->where(['email'=>$params['LoginForm']['email'],
                                                      'password' => md5($params['LoginForm']['password']),'is_deleted'=>'N','is_active'=>'N'])->one();



               if(!empty($user_not_active)){
                $msg = " This user currently inactive";
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
               }


            if(isset($_POST['LoginForm']['rememberMe']) && $_POST['LoginForm']['rememberMe'] =="1")
            {
                $cookies = Yii::$app->response->cookies;
                // add a new cookie to the response to be sent

                $no = rand(1,9);

                $v1 = $_POST['LoginForm']['email'];
                $v2 = $_POST['LoginForm']['password'];

                for($i=1;$i<=$no;$i++){
                    $v1 = base64_encode($v1);
                    $v2 = base64_encode($v2);
                }

                $cookies->add(new \yii\web\Cookie([
                    'name' => Yii::$app->params['appcookiename'].'email',
                    'value' => $v1,
                ]));

                $cookies->add(new \yii\web\Cookie([
                    'name' => Yii::$app->params['appcookiename'].'password',
                    'value' => $v2,
                ]));

                $cookies->add(new \yii\web\Cookie([
                    'name' => Yii::$app->params['appcookiename'].'turns',
                    'value' => $no,
                ]));

            }else{
                $cookies = Yii::$app->response->cookies;
                $cookies->remove(Yii::$app->params['appcookiename'].'email');
                unset($cookies[Yii::$app->params['appcookiename'].'email']);
                 $cookies->remove(Yii::$app->params['appcookiename'].'password');
                unset($cookies[Yii::$app->params['appcookiename'].'password']);
                $cookies->remove(Yii::$app->params['appcookiename'].'turns');
                unset($cookies[Yii::$app->params['appcookiename'].'turns']);

            }
            return $this->redirect(["/site/index"]);
            //return $this->goBack();
        } else {
            if($model->load(Yii::$app->request->post()))
            {
                $msg = "Email address or password are wrong";
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

            }
            return $this->render('login', [
                'model' => $model,
                'user'=>$user,
                'user1'=>$user1,
            ]);
        }
    }

    //public function beforeAction()
    //{
    //    //$result = parent::afterAction($action, $result);
    //    //// your custom code here
    //    //return $result;
    //    //echo '<pre>';
    //    //print_r('asd');
    //    return true;
    //}

    public function actionForgotpassword()
    {


        $this->layout = 'login';
        $model = new Users();
        //echo "1";die;
        $model->scenario='forgotpassword';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            echo  json_encode(ActiveForm::validate($model));
            die;
        }
        if($model->load(Yii::$app->request->post()))
        {

            $params = Yii::$app->request->post();


            if(isset($params['Users']['user_type'])){
               $post = Users::find()->where(['email'=>$params['Users']['email'],'user_type'=>'A','is_active'=>'Y','is_deleted'=>'N'])->one();
            }
            else{
               $post = Users::find()->where(['email'=>$params['Users']['email'],'user_type'=>'U','is_active'=>'Y','is_deleted'=>'N'])->one();
            }



            if(isset($post->id))
            {
                //$pass = uniqid();
                //$post->password = md5($pass);

                // set forgot password token, which will passed in url
                $random_str = time().rand(10000,99999);
                $post->forgot_password_token = md5($random_str);
                $post->forgot_password_token_timeout = time();


                if($post->save(false))
                {
                    $link = Url::to("@web/site/resetpassword?args=".$post->forgot_password_token,true);
                    Yii::$app->mailer->compose('@app/mail/layouts/forgotpassword', [
                            'username' => $post->user_name,
                            'link_token' => $post->forgot_password_token,
                            'link' => $link,
                    ])
                    ->setTo($params['Users']['email'])
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setSubject(Yii::$app->params['apptitle'].' : Reset Password Request')
                    //->setTextBody("Your new Password is : ".$pass)
                    ->send();

                    $msg = "Password Link has been sent to your email";
                    $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                }
                else
                {
                    //print_r($post->getErrors());die;
                    $msg = "Please try again";
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                    //Yii::$app->getSession()->setFlash('error', 'Failed to send email');
                }
            }
            else
            {
                $msg = "No such email found";
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
            }
            return $this->redirect(['login']);
        }

        return $this->render('forgotpassword',[
                            'model' => $model
                            ]);
    }

     public function actionaAdminforgotpassword()
    {
        $this->layout = 'login';
        $model = new Users();
        //echo "1";die;
        $model->scenario='forgotpassword';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            echo  json_encode(ActiveForm::validate($model));
            die;
        }
        if($model->load(Yii::$app->request->post()))
        {

            /*echo "<pre>";
            print_r($_POST);
            exit;*/

               $post = Users::find()->where(['email'=>$params['Users']['email'],'user_type'=>'A','is_deleted'=>'N'])->one();

            if(isset($post->id))
            {

                // set forgot password token, which will passed in url
                $random_str = time().rand(10000,99999);
                $post->forgot_password_token = md5($random_str);
                $post->forgot_password_token_timeout = time();

                if($post->save())
                {
                    $link = Url::to("@web/site/resetpassword?args=".$post->forgot_password_token,true);
                    Yii::$app->mailer->compose('@app/mail/layouts/forgotpassword', [
                            'username' => $post->user_name,
                            'link_token' => $post->forgot_password_token,
                            'link' => $link,
                    ])
                    ->setTo($params['Users']['email'])
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setSubject(Yii::$app->params['apptitle'].' : Reset Password Request')
                    //->setTextBody("Your new Password is : ".$pass)
                    ->send();

                    $msg = "Password Link has been sent to your email";
                    $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                }
                else
                {
                    //print_r($post->getErrors());die;
                    $msg = "Please try again";
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                    //Yii::$app->getSession()->setFlash('error', 'Failed to send email');
                }
            }
            else
            {
                $msg = "No such email found";
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
            }
            return $this->redirect(['login']);
        }

        return $this->render('forgotpassword',[
                            'model' => $model
                            ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout(false);

         //Yii::$app->user->identity = Null;
        //print_r(Yii::$app->user); die;
        //print_r(Yii::$app->user->identity); die;
        return $this->redirect(['login']);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionDashboardcount()
    {
        if($_REQUEST['tm']=="d")
        {
            $t1 = strtotime(date('m/d/Y'));
            $t2 = $t1+86400;
        }
        elseif($_REQUEST['tm']=="w")
        {
            $ts = strtotime(date('m/d/Y'));
            $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
            $t1 = strtotime(date('Y-m-d', $start));
            $t2 = strtotime(date('Y-m-d', strtotime('next saturday', $t1)));
        }
        elseif($_REQUEST['tm']=="m")
        {
            $y = date('Y');
            $m = date('m');
            $t1 = strtotime("01-".$m."-".$y);
            $t2 = strtotime(date("Y-m-t", $t1));
        }
        elseif($_REQUEST['tm']=="y")
        {
            $t1 = strtotime("01-01-".date('Y'));
            $t2 = strtotime("31-12-".date('Y'));
        }else{
               $time = explode(',',$_REQUEST['tm']);
               $t1 = strtotime($time[0]);
               $t2 = strtotime($time[1]);
        }

        $count = Yii::$app->mycomponent->dashboardcount1($t1,$t2);
        echo json_encode($count);
        die;
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionUseremailverification()
    {
        if(isset($_REQUEST['token']) && $_REQUEST['token'] != null)
        {
            $token = $_REQUEST['token'];
            $findUser = Users::find()->where(['email_verification_token'=>$token])->one();
            if(isset($findUser))
            {
                if($findUser->email_verified == 'N'){
                    Yii::$app->db->createCommand()->update('user_master', [
                        'email_verified' => 'Y',
                        'i_date' => time(),
                        'u_date' => time()
                    ], 'id = '.$findUser->id)->execute();
                    $msg = "Your email is verified now.";
                    $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];

                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                    return $this->redirect('useremailverified');
                }else{
                    $msg = "Email already verified.";
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                    return $this->redirect('useremailverified');
                }
            }else{
                $msg = "Email verification token is not valid";
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                return $this->redirect('useremailverified');
            }
        }else{
            $msg = "You don't have permission to access this page.";
            $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
            \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

            return $this->redirect('useremailverified');
        }
    }

    public function actionEmailverified()
    {
        $this->layout = 'login';
        return $this->render('useremailverification');
    }

    public function actionCms($page=null)
    {
        $this->layout = false;
        $cms = Cmspage::find()->where(['page_name'=>$page])->one();
        return $this->render('cms',['cms'=>$cms]);
    }





}
