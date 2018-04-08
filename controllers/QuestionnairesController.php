<?php

namespace app\controllers;

use Yii;
use app\models\Questionnaires;
use app\models\QuestionnairesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\Answer;

/**
 * QuestionnairesController implements the CRUD actions for Questionnaires model.
 */
class QuestionnairesController extends Controller
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
     * Lists all Questionnaires models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionnairesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Questionnaires model.
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
     * Creates a new Questionnaires model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Questionnaires();
        $answerModel = new Answer();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();

            //$purposeModel_post = Yii::$app->request->post()['Purpose'];
            //$purposeModel_purpose_array = Yii::$app->request->post()['Purpose']['purpose'];
            $answerModel_post = Yii::$app->request->post()['Answer'];
            // echo "<pre>";
            // print_r($answerModel_post);

            $answerModel_post_array = Yii::$app->request->post()['Answer']['answer_value'];

            if($model->save())
            {
                //$new_charity_id = $model->id;
                $new_question_id = $model->id;
                for($count = 0; $count < count($answerModel_post_array); $count++){
                  $answerModel = new Answer();
                  $answerModel->answer_value = $answerModel_post_array[$count];
                  $answerModel->question_id = $new_question_id;
                  // echo "<pre>";
                  // print_r($answerModel_post_array);die;
                  $answerModel->save(false);
                }
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,

                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'answerModel' => $answerModel,
            ]);
        }
    }

    /**
     * Updates an existing Questionnaires model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $answerModel = new Answer();
        $answers = Answer :: find()->where(['question_id'=>$id])->all();
        //$purposes = Purpose :: find()->where(['charity_id'=>$id])->all();
        $count_old_answerModel = count($answers);

        if ($model->load(Yii::$app->request->post()))
          {
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();

            if($model->save(false)){
                $answer_count = count($_POST['Answer']['id']);
                 //echo "<pre>";
                 //print_r($_POST['Answer']['id']);die;
                for($i=0;$i<=$answer_count-1;$i++)
                { //echo $_POST['Answer']['id'][$i];
                  if(isset($_POST['Answer']['id'][$i]) && $_POST['Answer']['id'][$i] != ""){
                      $Answer = Answer :: findOne($_POST['Answer']['id'][$i]);
                      $Answer->answer_value = $_POST['Answer']['answer_value'][$i];
                      $Answer->question_id = $model->id;
                      // echo "<pre>";
                      // print_r($Answer);exit;
                      $Answer->update();
                  }else {

                      $Answer = new Answer();
                      $Answer->answer_value = $_POST['Answer']['answer_value'][$i];
                      //echo $_POST['Answer']['answer_value'][$i];exit;
                      $Answer->question_id = $model->id;
                      $Answer->save();

                  }
                }
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
                'answerModel' => $answerModel,
                'answers' => $answers,
            ]);
        }
    }

    /**
     * Deletes an existing Questionnaires model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // $answer = new Answer();
        // $answer = $this->findModel($_REQUEST['id']);
        // if(isset($_REQUEST['id'])){
        //   $this->findModel($id)->deleted();
        // }
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

    /**
     * Finds the Questionnaires model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Questionnaires the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Questionnaires::findOne($id)) !== null) {
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
   public function actionAns_delete($id)
   {
       $pid = $_REQUEST['id'];
       $model= Answer::find()->where(['id' => $pid])->one();
       $model->delete();
   }
   public function actionExport()
   {
           $date=time();
           $query = Questionnaires::find();


           $params=$_REQUEST;

          //  if(isset($params['QuestionnairesSearch']['status']) && $params['QuestionnairesSearch']['status']!=null){
          //      $status=$params['QuestionnairesSearch']['status'];
          //      $query->andFilterWhere(['like', 'is_active', $status]);
          //  }
           $data=$query->orderBy('id desc')->all();
           $filename='Questionnaires_'.time().'.csv';

           header('Content-Encoding: UTF-8');
           header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
           header('Content-Disposition: attachment;filename="'.$filename.'"');  //File name extension was wrong
           header('Pragma: public');
           header("Expires: 0");
           header('Content-Transfer-Encoding: binary');
           header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
           header("Cache-Control: private",false);
           echo "NO,Question,Type,Status \n";
           if(isset($data) && $data!=array())
           {
               $i=1;
               foreach($data as $model)
               {
                   //$service_type_title=$abbrevation=$requirements=$status='-';
                   $question_value=$type=$status='-';
                   $question_value=(isset($model->question_value) && $model->question_value !="")?$model->question_value:"-";
                   $type=(isset($model->type) && $model->type!= "")?$model->type:"-";
                   if($type=="T"){
                     $type = "T&F";
                   }else{
                     $type = "MCQ";
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
                   echo $i.",".$question_value.",".$type.",".$status;
                   echo "\n";
                   $i++;
               }
           }
           exit();
   }
}
