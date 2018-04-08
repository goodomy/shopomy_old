<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Certificationcontractorpastexperience;

/**
 * Certificationcontractorpastexperiencesearch represents the model behind the search form about `app\models\Certificationcontractorpastexperience`.
 */
class Certificationcontractorpastexperiencesearch extends Certificationcontractorpastexperience
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['certification_contractor_past_experience_id', 'certification_contractor_id'], 'integer'],
            [['certification_contractor_past_experience_place', 'certification_contractor_past_experience_detail', 'certification_contractor_past_experience_from_year_month', 'certification_contractor_past_experience_till_year_month'], 'safe'],
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
        $query = Certificationcontractorpastexperience::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'certification_contractor_past_experience_id' => $this->certification_contractor_past_experience_id,
            'certification_contractor_id' => $this->certification_contractor_id,
            'certification_contractor_past_experience_from_year_month' => $this->certification_contractor_past_experience_from_year_month,
            'certification_contractor_past_experience_till_year_month' => $this->certification_contractor_past_experience_till_year_month,
        ]);

        $query->andFilterWhere(['like', 'certification_contractor_past_experience_place', $this->certification_contractor_past_experience_place])
            ->andFilterWhere(['like', 'certification_contractor_past_experience_detail', $this->certification_contractor_past_experience_detail]);

        return $dataProvider;
    }
}
