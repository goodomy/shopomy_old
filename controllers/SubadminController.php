<?php

namespace app\controllers;

use Yii;
use app\models\Subadmin;
use app\models\Subadminsearch;
use app\models\Users;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Role;

/**
 * SubadminController implements the CRUD actions for Subadmin model.
 */
class SubadminController extends Controller
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
     * Lists all Subadmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Subadminsearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subadmin model.
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
     * Creates a new Subadmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subadmin();
        
            
        
        if ($model->load(Yii::$app->request->post()))
        {
            $post_array = Yii::$app->request->post();
            
           /* echo "<pre>";
            print_r($_POST);
            print_r($post_array);
             print_r($model);
           exit;*/
            
            $post_array = Yii::$app->request->post();
            
            //$roles_list = implode(',',$post_array['Subadmin']['role_list']);
            $roles_list = $post_array['Subadmin']['role_id'];
            
            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->user_type = 'S';
            $model->password = md5($post_array['Subadmin']['password']);
            $model->role_id = $roles_list;
            
             
             //$role_name = Role::find()->select('name')->where(['is_deleted'=>'N','id'=>$model->role_id])->one();
             //$role_name  = $role_name->name;
                    
            if($model->save())
            {
                Yii::$app->mailer->compose('@app/mail/layouts/adminemail', [
                             'name' => $model->full_name,
                             'email' => $model->email,
                             'password' => $post_array['Subadmin']['password'],
                             //'role'=>$role->name,
                        ])
                     ->setTo($model->email)
                     ->setFrom(Yii::$app->params['adminEmail'])
                     ->setSubject(Yii::$app->params['apptitle'].' : Login Details')
                     ->send();
                
                return $this->redirect(['index']);
            } else {
                return $this->render('create',[
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
     * Updates an existing Subadmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

         $post_array = Yii::$app->request->post();
        
        if ($model->load(Yii::$app->request->post()))
        {
           
           //$roles_list = implode(',',$post_array['Subadmin']['role_list']);
            $roles_list = $post_array['Subadmin']['role_id'];
            
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->role_id = $roles_list;
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
     * Deletes an existing Subadmin model.
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
            $username = Users::find()->where(["email"=>$_REQUEST["email"],"is_deleted"=>"N"]);//,"user_type"=>"S"
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
    
    

    /**
     * Finds the Subadmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subadmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subadmin::findOne($id)) !== null) {
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
    
    public function actionPage()
    {
        if(isset($_REQUEST['size']) && $_REQUEST['size']!=null)
        {
            \Yii::$app->session->set('user.size',$_REQUEST['size']);
        }
    }
}
