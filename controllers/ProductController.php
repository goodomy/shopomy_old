<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Productsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Productsearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();

                if(isset($_FILES['Product']['name']['image']) && $_FILES['Product']['name']['image'] != null)
                {
                    list($width, $height) = getimagesize($_FILES['Product']['tmp_name']['image']);

                    $new_image['name'] = $_FILES['Product']['name']['image'];
                    $new_image['type'] = $_FILES['Product']['type']['image'];
                    $new_image['tmp_name'] = $_FILES['Product']['tmp_name']['image'];
                    $new_image['error'] = $_FILES['Product']['error']['image'];
                    $new_image['size'] = $_FILES['Product']['size']['image'];
                    $image = $new_image;

                    $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['productimage'], $width, $width);
                    $model->image = Yii::$app->params['productimage'].$name['image'];

                        if($model->save())
                        {
                           return $this->redirect(['index']);
                        }
                }
                 /*else{
                    $file_msg = 'Please Select File';
                     \Yii::$app->getSession()->setFlash('flashfilr_msg', $file_msg);
                    return $this->render('create', [
                    'model' => $model,
                    ]);
                }*/

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
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_media_path = $model->image;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();

                if(isset($_FILES['Product']['name']['image']) && $_FILES['Product']['name']['image'] != null)
                {
                    if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path))
                    {
                        unlink(Yii::getAlias('@webroot')."/".$old_media_path);
                    }

                    list($width, $height) = getimagesize($_FILES['Product']['tmp_name']['image']);

                    $new_image['name'] = $_FILES['Product']['name']['image'];
                    $new_image['type'] = $_FILES['Product']['type']['image'];
                    $new_image['tmp_name'] = $_FILES['Product']['tmp_name']['image'];
                    $new_image['error'] = $_FILES['Product']['error']['image'];
                    $new_image['size'] = $_FILES['Product']['size']['image'];
                    $image = $new_image;

                    $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['productimage'], $width, $width);
                    $model->image = Yii::$app->params['productimage'].$name['image'];

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
                 else{
                    $model->image = $old_media_path;
                }

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
     * Deletes an existing Product model.
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


     public function actionDeleteimage(){

        if(isset($_REQUEST['id']) && $_REQUEST['id'] != '' && isset($_REQUEST['image_name']) && $_REQUEST['image_name'] != ''
           && isset($_REQUEST['image_path']) && $_REQUEST['image_path'] != ''){

            $old_media_path = $_REQUEST['image_path'];
            if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path)){

                unlink(Yii::getAlias('@webroot')."/".$old_media_path);
            }

            $model = Product::find()->where(['id'=>$_REQUEST['id']])->one();
            $model->image = null;
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->save(false);
            echo json_encode(array('id'=>$_REQUEST['id']));
            die;
        }

    }



    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
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

            if(Product::updateAll($cond,'id IN('.$str.')'))
            {
                if($_REQUEST['field'] == 'is_deleted')
                {
                    $msg = 'Data successfully deleted';
                    Product::updateAll($cond,'id IN('.$str.')');
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
