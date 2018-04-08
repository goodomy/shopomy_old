<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Feedback;

/**
 * Feedbacksearch represents the model behind the search form about `app\models\Feedback`.
 */
class Feedbacksearch extends Feedback
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'feeling_station_id', 'i_by', 'i_date', 'u_by', 'u_date'], 'integer'],
            [[ 'user_id','email', 'subject', 'comment', 'is_active', 'is_deleted'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
      
	    $query = Feedback::find();
        $query->where(['feedback_master.is_deleted'=>'N'])
				->andwhere('feeling_station_id IS NULL OR feeling_station_id = ""');
		$query->leftjoin("user_master","user_master.id=feedback_master.user_id");
			
        $session = Yii::$app->session;
        $size = $session->get('user.size');
        
         $dataProvider = new ActiveDataProvider([
        		'query' => $query,
        		'pagination'=>['pagesize'=> isset($size)?$size:5],
        		'sort' => ['defaultOrder' => ['id' => SORT_DESC] ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            //'user_id' => $this->user_id,
            'feeling_station_id' => $this->feeling_station_id,
            'i_by' => $this->i_by,
            'i_date' => $this->i_date,
            'u_by' => $this->u_by,
            'u_date' => $this->u_date,
        ]);

        $query->andFilterWhere(['like', 'user_master.email', $this->email])
			->andFilterWhere(['like', 'user_master.full_name', $this->user_id])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
	
	public function fsearch($params)
    {
        
		$query = Feedback::find();
        $query->where(['feedback_master.is_deleted'=>'N'])->andwhere('feeling_station_id > 0');
        $query->leftjoin("user_master","user_master.id=feedback_master.user_id");
		
        $session = Yii::$app->session;
        $size = $session->get('user.size');
        
         $dataProvider = new ActiveDataProvider([
        		'query' => $query,
        		'pagination'=>['pagesize'=> isset($size)?$size:5],
        		'sort' => ['defaultOrder' => ['id' => SORT_DESC] ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            //'user_id' => $this->user_id,
            'feeling_station_id' => $this->feeling_station_id,
            'i_by' => $this->i_by,
            'i_date' => $this->i_date,
            'u_by' => $this->u_by,
            'u_date' => $this->u_date,
        ]);

        $query->andFilterWhere(['like', 'user_master.email', $this->email])
			->andFilterWhere(['like', 'user_master.full_name', $this->user_id])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
}
