<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Feelingstation;
use app\models\Stationreview;

/**
 * Feelingstationsearch represents the model behind the search form about `app\models\Feelingstation`.
 */
class Feelingstationsearch extends Feelingstation
{
	public $user_id;
	public $feeling_station_id;
	public $rate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'station_id', 'i_by', 'i_date', 'u_by', 'u_date','user_id','feeling_station_id'], 'integer'],
            [['name', 'region', 'phone_number', 'address', 'latitude', 'longitude', 'info', 'services', 'products', 'facilities',
			  'is_active', 'is_deleted','map_link','working_hours','rate'], 'safe'],
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
        $query = Feelingstation::find();
        $query->where(['is_deleted'=>'N']);
        
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
			'station_id' => $this->station_id,
            'i_by' => $this->i_by,
            'i_date' => $this->i_date,
            'u_by' => $this->u_by,
            'u_date' => $this->u_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'region', $this->region])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'services', $this->services])
            ->andFilterWhere(['like', 'products', $this->products])
            ->andFilterWhere(['like', 'facilities', $this->facilities])
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }
	
	public function reviews($params)
    {
		/*echo "<pre>";
		print_r($params);
		exit;*/
	  
	    $query = Stationreview::find();
		if(isset($params['id']))
		{
			$query->where(['is_deleted'=>'N','feeling_station_id'=>$params['id']]);	
		}else{
			$query->where(['is_deleted'=>'N']);
		}
        
        $session = Yii::$app->session;
        $size = $session->get('user.size');
        $dataProvider = new ActiveDataProvider([
        		'query' => $query,
        		'pagination'=>['pagesize'=> 20],
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
			'user_id' => $this->user_id,
			'feeling_station_id'=>$this->feeling_station_id,
			//'rate' => $this->rate,
        ]);
		
		if(isset($params['Feelingstationsearch']['rate']) && $params['Feelingstationsearch']['rate']!=null)
        {
			
			
          $query->andFilterWhere([
            'AND',
            ['>=','rate',$params['Feelingstationsearch']['rate']],
          ]);
        }
		
        return $dataProvider;
    }
}
