<?php

namespace app\controllers;

use Yii;
use app\models\Role;
use app\models\Rolesearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Authitem;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
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
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Rolesearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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

    /**
     * Displays a single Role model.
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
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role();


        if ($model->load(Yii::$app->request->post()))
        {
            // echo "<pre>";
            // print_r($_POST);
            // //print_r($auth_itemlist);
            // exit;

            foreach($_POST['to'] as $each_auth)
            {
                $controller_name = Authitem::find()->select('controller,action')->where(['is_deleted'=>'N','id'=> $each_auth])->one();





                 $auth_index_id = Authitem::find()->select('id')
                                                 ->where(['is_deleted'=>'N','controller'=> $controller_name->controller,'action'=>'index'])->one();



                 $auth_index_id_arr[] = $auth_index_id->id;

                 if(($controller_name->controller == 'feelingstation') &&($controller_name->action == 'reviewsindex')){
                  array_pop($auth_index_id_arr);
                 }
            }

            $final_auth_array = array_merge($auth_index_id_arr,$_POST['to']);
            $final_auth_array = array_unique($final_auth_array);


            $auth_itemlist = implode(',',$final_auth_array);

            //$model->is_active = $auth_itemlist;
            $model->auth_item = $auth_itemlist;
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
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {

           foreach($_POST['to'] as $each_auth)
            {
                $controller_name = Authitem::find()->select('controller,action')->where(['is_deleted'=>'N','id'=> $each_auth])->one();
                $auth_index_id = Authitem::find()->select('id')
                                                      ->where(['is_deleted'=>'N','controller'=> $controller_name->controller,'action'=>'index'])->one();
                $auth_index_id_arr[] = $auth_index_id->id;

                if(($controller_name->controller == 'feelingstation') &&  ($controller_name->action == 'reviewsindex')){
                    array_pop($auth_index_id_arr);
                 }

            }
            $final_auth_array = array_merge($auth_index_id_arr,$_POST['to']);
            $final_auth_array = array_unique($final_auth_array);

            $auth_itemlist = implode(',',$final_auth_array);

            //$auth_itemlist = implode(',',$_POST['to']);

            $model->auth_item = $auth_itemlist;
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
     * Deletes an existing Role model.
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

            if(Role::updateAll($cond,'id IN('.$str.')'))
            {
                if($_REQUEST['field'] == 'is_deleted')
                {
                    $msg = 'Data successfully deleted';
                    Role::updateAll($cond,'id IN('.$str.')');
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
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
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
