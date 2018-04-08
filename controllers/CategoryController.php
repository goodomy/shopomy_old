<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionSubindex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->subcategory_search(Yii::$app->request->queryParams);

        return $this->render('subindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionAjaxupdate()
    {
        // echo "<pre>";
        // print_r($_REQUEST);
        // die;
        $id = $_REQUEST['id'];

         $model = Category::findOne($id);

        return $this->render('_ajaxupdate', [
                'model' => $model,
            ]);

    }
    public function actionCreate()
    {
        $this->layout = false;
        $model = new Category();
        if(Yii::$app->request->isAjax)
        {
            
            return $this->render('create', [
                'model' => $model,
            ]);
        }

        if ($model->load(Yii::$app->request->post()))
        {
            //die(print_r(Yii::$app->request->post()));
            
            // if($_REQUEST['Category']['parent_id']=='')
            // {
            //     $model->parent_id=0;
                
            // }
            // else
            // {
            //     $model->parent_id=$_REQUEST['Category']['parent_id'];
            // }
            $model->parent_id=0;
            
            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
        
            if($model->save())
            {
                $msg="Category has been successfully added";
                $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

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


    public function actionCreate_sub()
    {
        $this->layout = false;
        $model = new Category();
        if(Yii::$app->request->isAjax)
        {
            
            return $this->render('create_sub', [
                'model' => $model,
            ]);
        }

        if ($model->load(Yii::$app->request->post()))
        {
            //die(print_r(Yii::$app->request->post()));
            
            if($_REQUEST['Category']['parent_id']=='')
            {
                $model->parent_id=0;
                
            }
            else
            {
                $model->parent_id=$_REQUEST['Category']['parent_id'];
            }
            //$model->parent_id=0;
            
            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
        
            if($model->save())
            {
                $msg="Subcategory has been successfully added";
                $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

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
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        
        if(Yii::$app->request->isAjax)
        {
            $model = $this->findModel($_REQUEST['id']);
        }else{
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post()))
                {
                    $model->u_by = Yii::$app->user->id;
                    $model->u_date = time();
                    if($model->save()){
                        $msg="Category has been successfully updated";
                        $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                        \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
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
        return $this->render('update', [
                'model' => $model,
            ]);
       

        
    }


    public function actionUpdate_sub($id)
    {
        
        if(Yii::$app->request->isAjax)
        {
            $model = $this->findModel($_REQUEST['id']);
        }else{
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post()))
                {
                    $model->u_by = Yii::$app->user->id;
                    $model->u_date = time();
                    if($model->save()){
                        $msg="Subcategory has been successfully updated";
                        $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                        \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                        return $this->redirect(['index']);
                    }
                    else{
                        return $this->render('update', [
                            'model' => $model,
                        ]); 
                    }
                } else {
                    return $this->render('update_sub', [
                        'model' => $model,
                    ]);
                }
        }
        return $this->render('update_sub', [
                'model' => $model,
            ]);
       

        
    }

    /**
     * Deletes an existing Category model.
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
            //$model->save(false);

            if($model->save())
            {
                $msg="Category has been successfully deleted";
                $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                return $this->redirect(['index']);
            }
        }
        //$this->findModel($id)->delete();

        //return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
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

    public function actionChange()
    {
        $str = $_REQUEST['str'];
        $field =$_REQUEST['field'];
        $val = $_REQUEST['val'];
        
        if($str!= null)
        {
            $cond = [$field => $val];
                
            if(Category::updateAll($cond,'id IN('.$str.')'))
            {
                if($_REQUEST['field'] == 'is_deleted')
                {
                    $msg = 'Data successfully deleted';
                    Category::updateAll($cond,'id IN('.$str.')');
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

    public function actionCheckname(){
        if($_REQUEST['id'] > 0)
        {
            $exist = Category::find()->where(['name'=>$_REQUEST['val'],'parent_id'=>0,'is_deleted'=>'N'])->andwhere('id <>'.$_REQUEST['id'])->one();
        }else{
            $exist = Category::find()->where(['name'=>$_REQUEST['val'],'parent_id'=>0,'is_deleted'=>'N'])->one();
        }
        if($exist)
            echo 1;
        else
            echo 0;
        die;
        
    }

    public function actionChecksubcategoryname(){
        if($_REQUEST['id'] > 0)
        {
            $exist = Category::find()->where(['name'=>$_REQUEST['val'],'parent_id'=>$_REQUEST['parent_id'],'is_deleted'=>'N'])->andwhere('id <>'.$_REQUEST['id'])->one();
        }else{
            $exist = Category::find()->where(['name'=>$_REQUEST['val'],'parent_id'=>$_REQUEST['parent_id'],'is_deleted'=>'N'])->one();
        }
        if($exist)
            echo 1;
        else
            echo 0;
        die;
        
    }
}
