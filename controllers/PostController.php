<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Category;
use app\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post()))
        {
            

            $model->i_by = Yii::$app->user->id;
            $model->i_date = time();
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();

            // if(isset($_FILES['Post']['name']['image']) && $_FILES['Post']['name']['image'] != null)
            // {
            //     list($width, $height) = getimagesize($_FILES['Post']['tmp_name']['image']);

            //     $new_image['name'] = $_FILES['Post']['name']['image'];
            //     $new_image['type'] = $_FILES['Post']['type']['image'];
            //     $new_image['tmp_name'] = $_FILES['Post']['tmp_name']['image'];
            //     $new_image['error'] = $_FILES['Post']['error']['image'];
            //     $new_image['size'] = $_FILES['Post']['size']['image'];
            //     $image = $new_image;

            //     $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['postimage'], $width, $width);
            //     $model->post_image = Yii::$app->params['postimage'].$name['image'];

            //         /*if($model->save())
            //         {
            //            return $this->redirect(['index']);
            //         }*/
            // }

            // if(isset($_FILES['Post']['name']['video']) && $_FILES['Post']['name']['video'] != null)
            // {
            //     $new_video['name'] = $_FILES['Post']['name']['video'];
            //     $new_video['type'] = $_FILES['Post']['type']['video'];
            //     $new_video['tmp_name'] = $_FILES['Post']['tmp_name']['video'];
            //     $new_video['error'] = $_FILES['Post']['error']['video'];
            //     $new_video['size'] = $_FILES['Post']['size']['video'];
            //     $name = Yii::$app->common->normalUpload($new_video, Yii::$app->params['postvideo']);
            //     $model->post_video = $name;
            // }

            
        
            if($model->save())
            {
                $msg="Post has been successfully added";
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
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $old_media_path = $model->post_image;

        // if(isset($_FILES['Post']['name']['image']) && $_FILES['Post']['name']['image'] != null)
        // {
        //     if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path))
        //     {
        //         unlink(Yii::getAlias('@webroot')."/".$old_media_path);
        //     }

        //     list($width, $height) = getimagesize($_FILES['Post']['tmp_name']['image']);

        //     $new_image['name'] = $_FILES['Post']['name']['image'];
        //     $new_image['type'] = $_FILES['Post']['type']['image'];
        //     $new_image['tmp_name'] = $_FILES['Post']['tmp_name']['image'];
        //     $new_image['error'] = $_FILES['Post']['error']['image'];
        //     $new_image['size'] = $_FILES['Post']['size']['image'];
        //     $image = $new_image;

        //     $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['postimage'], $width, $width);
        //     $model->post_image = Yii::$app->params['postimage'].$name['image'];
        // }
        // else
        // {
        //     $model->post_image = $old_media_path;
        // }

        

        // if(isset($_FILES['Post']['name']['video']) && $_FILES['Post']['name']['video'] != null)
        // {
        //     $new_video['name'] = $_FILES['Post']['name']['video'];
        //     $new_video['type'] = $_FILES['Post']['type']['video'];
        //     $new_video['tmp_name'] = $_FILES['Post']['tmp_name']['video'];
        //     $new_video['error'] = $_FILES['Post']['error']['video'];
        //     $new_video['size'] = $_FILES['Post']['size']['video'];
        //     $name = Yii::$app->common->normalUpload($new_video, Yii::$app->params['postvideo']);
        //     $model->post_video = $name;
        // }

        

        

        if(isset($model->expiry_date) && $model->expiry_date != '')
            $model->expiry_date = date('d-m-Y',strtotime($model->expiry_date));



        if ($model->load(Yii::$app->request->post()))
        {
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            if($model->save()){
                $msg="Post has been successfully updated";
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

    /**
     * Deletes an existing Post model.
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
            // $model->save(false);
            if($model->save())
            {
                $msg="Post has been successfully deleted";
                $flash_msg = \Yii::$app->params['msg_success'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                return $this->redirect(['index']);
            }
        }
        //$this->findModel($id)->delete();

        //return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
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

    public function actionDeleteimage(){


        if(isset($_REQUEST['id']) && $_REQUEST['id'] != '' && isset($_REQUEST['image_name']) && $_REQUEST['image_name'] != ''
           && isset($_REQUEST['image_path']) && $_REQUEST['image_path'] != ''){
            

            $old_media_path = $_REQUEST['image_path'];
            if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path)){

                unlink(Yii::getAlias('@webroot')."/".$old_media_path);
            }

            $model = Post::find()->where(['id'=>$_REQUEST['id']])->one();
            $model->post_image = null;
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            $model->save(false);
            echo json_encode(array('id'=>$_REQUEST['id']));
            die;
        }

    }

    public function actionGetsubcategory()
    {  
    
     
      $html= "";
        
        if(isset($_REQUEST['category_id']) && $_REQUEST['category_id']!=null)
        {
          $html.= "<option value=''>Select</option>";
          $sub_category_id="";
          if(isset($_REQUEST['id']) && $_REQUEST['id'] != null){
                $post_data=Post::find()->select('sub_category_id')->where(['id'=>$_REQUEST['id']])->one();
                $data=Category::find()->select('name')->where(['id'=>$post_data->sub_category_id])->one();
                $sub_category_id=$data->sub_category_id;
          }
          $data=Category::find()->select('id, name')->where(['is_deleted'=>'N','is_active'=>'Y','parent_id'=>$_REQUEST['category_id']])->all();

          foreach ($data as $value)
          {
            $getSubcategoryList=$value['name'];



            // $html.= "<option value='".$value["id"]."'>".$value["cuisine_id"]."</option>";
            $selected=$sub_category_id==$value['id']?"selected='selected'":"";
            $html.= "<option value='".$value['id']."' ".$selected.">".$value['name']."</option>";
          }
          
        }
      // }
      echo $html;
          exit;
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

    public function actionGetdata()
    {
        $output='';
        
        $list=Yii::$app->mycomponent->getSubcategoryList_basedonCategory($_REQUEST['category_id']);
        $output.='<option value="">Select Subcategory</option>';//$output.='<option value="">All</option>';
        
        
        if($list!=array()){
            foreach($list as $key=>$value)
                $output.='<option value="'.$key.'">'.$value.'</option>';
        }
        
        echo json_encode(array("output"=>$output));
        die;
        
    }

    

    public function actionChange()
    {
        $str = $_REQUEST['str'];
        $field =$_REQUEST['field'];
        $val = $_REQUEST['val'];
        
        if($str!= null)
        {
            $cond = [$field => $val];
                
            if(Post::updateAll($cond,'id IN('.$str.')'))
            {
                if($_REQUEST['field'] == 'is_deleted')
                {
                    $msg = 'Data successfully deleted';
                    Post::updateAll($cond,'id IN('.$str.')');
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

    public function actionExportexcel()
   {

    $query=Post::find()->where(['is_deleted'=>'N']);

    $params=$_REQUEST;

    if(isset($params['Postsearch']['post_type']) && $params['Postsearch']['post_type']!=null){
        $post_type=$params['Postsearch']['post_type'];
        $query->andFilterWhere(['like', 'post_type', $post_type]);
    }

    if(isset($params['Postsearch']['status']) && $params['Postsearch']['status']!=null){
        $status=$params['Postsearch']['status'];
        $query->andFilterWhere(['like', 'is_active', $status]);
    }

    if(isset($params['Postsearch']['category_id']) && $params['Postsearch']['category_id']!=null)
    {
        $category_id=$params['Postsearch']['category_id'];
        $query->andFilterWhere(['like', 'category_id', $category_id]);
    }

    if(isset($params['Postsearch']['sub_category_id']) && $params['Postsearch']['sub_category_id']!=null){
        $sub_category_id=$params['Postsearch']['sub_category_id'];
        $query->andFilterWhere(['like', 'sub_category_id', $sub_category_id]);
    }

    if(isset($params['Postsearch']['date']) && $params['Postsearch']['date']!=null)
    {
        $arr=explode('-',$params['Postsearch']['date']);

        $date1 = $arr[0];
        $date1 = str_replace('/', '-', $date1);
        $start =  date('Y-m-d', strtotime($date1));

        $date2 = $arr[1];
        $date2 = str_replace('/', '-', $date2);
        $end =  date('Y-m-d', strtotime($date2));

       $query->andFilterWhere([
          'AND',
          ['>=','date(from_unixtime(i_date))',$start],
          ['<=','date(from_unixtime(i_date))',$end],
        ]);
    }

    

    if(isset($params['Postsearch']['keyword']) && $params['Postsearch']['keyword']!=null)
    {
        $keyword=$params['Postsearch']['keyword'];
        $query->andFilterWhere([
            'or',
            ['like', 'post_title', $keyword],
            ['like', 'store_name', $keyword],
            //['like', 'u.mobile_number', $keyword],
            ['like', 'store_location', $keyword],
        ]);
    }

     
     

     $data=$query->orderBy('id DESC')->all();



     include Yii::getAlias('@vendor').'/PHPExcel/Classes/PHPExcel/IOFactory.php';
     include Yii::getAlias('@vendor').'/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

     $filename='post_report-'.date('_d-m-Y').'.xls';
     $lastCol='F';
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
     $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
     $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
     $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    //  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
     $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,'Post Report');
     $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':'.$lastCol.$rowCount);
     $objSheet->getStyle('A'.($rowCount))->getFont()->setBold(true);
     $rowCount++;

     $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,'Post Type');
     $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,'Post Title');
     $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,'Store Name');
     $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,'Store Location');
     $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,'Posted Date');
     $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,'Status');
    //  $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,'Date of Joining');
     $objSheet->getStyle('A'.$rowCount.':'.$lastCol.$rowCount)->getFont()->setBold(true);

     $rowCount++;

     if(isset($data) && $data!=null)
     {
       foreach ($data as $list)
       {
         $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,ucfirst($list['post_type']=='M'?"Share Something From My Store":"Share Something I Found"));
         $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$list['post_title']);
         $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$list['store_name']);
         $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$list['store_location']);
         $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,date(Yii::$app->params['dateformat'],$list['i_date']));
         $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$list['is_active']=='Y'?"Active":"Inactive");
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

   public function actionVideo($id)
    {
        return $this->render('video', [
            'model' => $this->findModel($id),
        ]);
    }

}
