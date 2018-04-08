<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contractortypes;

/**
 * ContractortypesSearch represents the model behind the search form about `app\models\Contractortypes`.
 */
class ContractortypesSearch extends Contractortypes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_type_id'], 'integer'],
            [['service_type_title', 'service_type_description', 'abbrevation', 'requirements', 'is_active'], 'safe'],
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
        $query = Contractortypes::find();
        $session = Yii::$app->session;
        $size = $session->get('user.size');
        // $dataProvider = new ActiveDataProvider([
        //     'query' => $query,
        // ]);

        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination'=>['pagesize'=> isset($size)?$size:5],
                'sort' => ['defaultOrder' => ['service_type_id' => SORT_DESC], 'attributes'=>['service_type_id']],
        ]);
        if(isset($params['ContractortypesSearch']['status']) && $params['ContractortypesSearch']['status']!=null){
            $status=$params['ContractortypesSearch']['status'];
            $query->andFilterWhere(['like', 'is_active', $status]);
        }
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'service_type_id' => $this->service_type_id,
        ]);

        $query->andFilterWhere(['like', 'service_type_title', $this->service_type_title])
            ->andFilterWhere(['like', 'service_type_description', $this->service_type_description])
            ->andFilterWhere(['like', 'abbrevation', $this->abbrevation])
            ->andFilterWhere(['like', 'requirements', $this->requirements])
            ->andFilterWhere(['like', 'is_active', $this->is_active]);

        return $dataProvider;
    }
}
